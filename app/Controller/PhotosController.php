<?php
App::uses('AppController', 'Controller');
/**
 * Photos Controller
 *
 * @property Photo $Photo
 */
class PhotosController extends AppController {

/* public $uses = array(
    'MyAws'
  );*/



public function beforeFilter() {
			parent::beforeFilter();
			//$this->Auth->loginRedirect = array('controller' => '', 'action' => '');
			$this->Auth->allow('index'
				//, 'admin_view', 'admin_edit', 'admin_add', "admin_index"
				);
	}

	public function getToken () {

			$url = Configure::read("eyeem");
			$foo = array(
				"Content-Type: application/x-www-form-urlencoded",
				"Accept: application/json");
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_HTTPHEADER, $foo);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch,CURLOPT_POSTFIELDS, array('foo'=>'bar'));
			$results = curl_exec($ch);
			$results = json_decode($results);
			curl_close($ch);
			$token = Cache::write('eyeem-token', $results->access_token);
			return $results;
	}

	public function eyeEm ($amazonURL, $token) {
		$url = "https://vision-api.eyeem.com/v1/analyze";
    $content = '{
	   "requests":[
	      {
	         "tasks":[
	            {
	               "type":"TAGS"
	            },
	            {
	               "type":"CAPTIONS"
	            },
	            {
	               "type":"AESTHETIC_SCORE"
	            }
	         ],
	         "image":{
	            "url": "'. $amazonURL . '"
	         }
	      }
	   ]
	  }';

		$data = array(
			"Content-Type: application/json",
			"Accept: application/json",
			'Authorization: Bearer ' . $token);

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// debug(curl_getinfo($ch));
		$results = curl_exec($ch);
		curl_close($ch);
		$results = json_decode($results);
		return $results;
	}


	public function upload(){
		$this->layout = "ajax";

		if(isset($this->data["Photo"]["image"]["error"]) && $this->data["Photo"]["image"]["error"] == 0){
			App::uses('File', 'Utility');

			$file = new File($this->data["Photo"]["image"]["tmp_name"]);
			$foo = explode(".", $this->data["Photo"]["image"]["name"]);
			$foo = $foo[count($foo)-1];// ? $foo[1] : 

			$newFilePath = APP . "webroot/files/";
			
			$newFileName = md5(uniqid(rand(), true));
			$smallFileName = $newFileName."-small";
			
			$newFileName = $newFileName.".".$foo;
			$smallFileName = $smallFileName.".".$foo;


			$newFile = $newFilePath.$newFileName;
			$smallFile = $newFilePath.$smallFileName;
			if($file->copy($newFile)){
				$command = 'convert '.$newFile.' -resize 600x600^ '.$smallFile;
				App::uses('AmazonS3', 'AmazonS3.Lib');
				$amazonConfig = Configure::read('Amazon');
				$AmazonS3 = new AmazonS3($amazonConfig);
				$AmazonS3->amazonHeaders = array(
				    'x-amz-acl' => 'public-read',
				);
				
				$AmazonS3->put($newFile);

				$amazonUrl = "https://s3-eu-west-1.amazonaws.com/phd4/".$newFileName;

				$photo = array("Photo"=>array(
					"amazonUrl"=>$amazonUrl,
					//"size"=>$file->size(),
					"size"=>$this->data["Photo"]["image"]["size"],
					"filename"=>$this->data["Photo"]["image"]["name"],
					"user_id"=>$this->Auth->user("id")
					));
				if(!$this->Photo->save($photo)){
					debug($this->validationErrors);
					die();
				}else{
					$photo["Photo"]["id"] = $this->Photo->getLastInsertID();
					$file2 = new File($newFile);
					if(!$file2->delete()){
						debug("did not delete: ".$newFile);
					}
					$this->autoRender = false;
					echo json_encode($photo);
				}
			}else{
				debug($file);
				die(":(");
			}
		}else{
			echo json_encode(array("error"=>true, "photo"=>$this->data));
		}
		$this->render(false);
	}




	public function pollRating($photo_id = null){





		$this->layout = "ajax";

		$photo = $this->Photo->find("first", array(
			"conditions"=>array(
				"Photo.id"=>$photo_id
				),
			"recursive"=>-1
			));

		$amazonUrl = $photo["Photo"]["amazonUrl"];

		$token = Cache::read('eyeem-token');
		if (!$token){
			$token = $this->getToken();
		}

		$eyeEmResults = $this->eyeEm($amazonUrl, $token);
		if (!$eyeEmResults) {
			$token = $this->getToken();
			$eyeEmResults = $this->eyeEm($amazonUrl, $token);
		}

		$responses = $eyeEmResults->responses[0];

		$tags = $responses->tags;
		$rating = $responses->aesthetic_score->score;

		$photo["Photo"]["results"] = json_encode($eyeEmResults);
		$photo["Photo"]["rating"] = $rating;

		//debug($results);
		$tag_ids = array();
		if(isset($tags) && is_array($tags)):
			foreach($tags as $concept):
				$tag = $this->Photo->Tag->find("first", array(
					"conditions"=>array(
						"Tag.name"=>$concept->text
						),
					"recursive"=>-1
					));
				if(empty($tag)){
					$tag = array("Tag"=>array(
						"name"=>$concept->text
						));
					$this->Photo->Tag->create();
					if(!$this->Photo->Tag->save($tag)){
						debug($this->Photo->Tag->validaitonErrors);
						die();
					}
					$tag_id = $this->Photo->Tag->getLastInsertId();
				}else{
					$tag_id = $tag["Tag"]["id"];
				}
				$tag_ids[] = $tag_id;
			endforeach;
		endif;

		$photo["Tag"] = $tag_ids;
		if(!$this->Photo->save($photo)){
			debug($photo);
			debug($this->Photo->validationErrors);
			die();
			$this->render(false);
		}else{
			$photo = $this->Photo->find("first", array(
				"conditions"=>array(
					"Photo.id"=>$photo["Photo"]["id"]
					),
				"recursive"=>2
				));
			$photoCount = $this->Photo->find("count");
			$this->set(compact("photo", "photoCount"));

		}
	}


	public function test(){

	}



	public function view($id = null){

	}

/**
 * index method
 *
 * @return void
 */
	public function index() {

		$this->layout = 'floatr';
		$this->Photo->recursive = 0;

		for($i=0;$i<24; $i++){

			if (isset($count) && $count > 10) {
				continue;
			}
			$time = '-'.($i+1).' month';
			$count = $this->Photo->find('count', array(
				'conditions'=>array(
					'Photo.created >='=>date('Y-m-d H:i:s', strtotime($time))
					)
				));
		}
		$topPhotos = $this->Photo->find("all", array(
			"limit"=>5,
			"order"=>"Photo.rating DESC",
			"recursive"=>1,
			'conditions'=>array(
				'Photo.created >='=>date('Y-m-d H:i:s', strtotime($time))
				)
			));
		$topPhotoIds = Set::classicExtract($topPhotos, "{n}.Photo.id");
		$bottomPhotos = $this->Photo->find("all", array(
			"limit"=>5,
			"conditions"=>array(
				"NOT"=>array(
					"Photo.id"=>$topPhotoIds
					)
				),
			"order"=>"Photo.rating ASC",
			"recursive"=>1,
			'conditions'=>array(
				'Photo.created >='=>date('Y-m-d H:i:s', strtotime($time))
				)
			));

		$bottomPhotos = array_reverse($bottomPhotos);
		$photos = array_merge($topPhotos, $bottomPhotos);
		$photoCount = $this->Photo->find("count");
		$this->set(compact("photos", "photoCount"));
	}

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Photo->recursive = 1;
		$this->Photo->order = array("Photo.rating DESC");
		$this->set('photos', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		$this->set('photo', $this->Photo->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Photo->create();
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__('The photo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The photo could not be saved. Please, try again.'));
			}
		}
		$users = $this->Photo->User->find('list');
		$tags = $this->Photo->Tag->find('list');
		$this->set(compact('users', 'tags'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Photo->save($this->request->data)) {
				$this->Session->setFlash(__('The photo has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The photo could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Photo->read(null, $id);
		}
		$users = $this->Photo->User->find('list');
		$tags = $this->Photo->Tag->find('list');
		$this->set(compact('users', 'tags'));
	}

/**
 * admin_delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {

		$this->Photo->id = $id;
		if (!$this->Photo->exists()) {
			throw new NotFoundException(__('Invalid photo'));
		}
		if ($this->Photo->delete()) {
			$this->Session->setFlash(__('Photo deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Photo was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
