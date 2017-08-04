<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
var $components = array('Email');

public function beforeFilter() {
			parent::beforeFilter();
			//$this->Auth->loginRedirect = array('controller' => '', 'action' => '');
			$this->Auth->allow('login'
				, 'admin_view', 'admin_edit', 'admin_add', "admin_index", "validate"
				);
	}
/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}
/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set('user', $this->User->read(null, $id));
	}
/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->find("first", array(
				"conditions"=>array(
					"User.id"=>$id
					),
				"recursive"=>-1
				)
			);
		}
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}


	// users

	public function profile($id = false) {
		//die();
		$this->layout = 'floatr';
		
		if (!empty($this->data) && $this->Auth->user("id") == $this->data["User"]["id"]) {

			if ($this->User->save($this->data)) {
				$this->Session->setFlash(__('The user has been saved'));

			//	$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}else{
			if(!$id){
				$id = $this->Auth->user("id");
			}
			$user = $this->User->find("first", array(
				"conditions"=>array(
					"User.id"=>$id
					),
				/*"contain"=>array(
					"Photo"=>array(
						"order"=>"Photo.rating DESC",
						"Tag"
						),

					),*/
				"recursive"=>2
				));
			//debug($user);
			unset($user["User"]["validationCode"]);
				$query = "SELECT id, rating, FIND_IN_SET(
                                     rating
                                ,  (SELECT  GROUP_CONCAT(
                                    DISTINCT rating
                                    ORDER BY rating  DESC
                                 )
                             FROM    photos)
                           ) as rank
		        FROM  photos;";
		        $result = $this->User->query($query);

			foreach($user["Photo"] as &$photo){
				foreach($result as $q){
					if($q["photos"]["id"] == $photo["id"]){
						$photo["rank"] = $q[0]["rank"];
					}
				}

			}
			$photoCount = $this->User->Photo->find("count");
			$this->set(compact("photoCount"));

			$user["Photo"] = Set::sort($user["Photo"], '{n}.rank', 'asc');
			$this->data = $user;
			//debug($user["Photo"]);
		}
	//	$this->set(compact("user"));

	}

	public function session() {
		$this->layout = 'floatr';
		if ( !empty($this->data) && !empty($this->data["User"]["email"]) ) {
			$user = $this->User->find('first', array(
				"conditions"=>array(
					"User.email"=>$this->data['User']['email']
					),
				"recursive" => -1
				));
			if ( $user ) {
				$validationCode = md5(uniqid(rand(), true));
				$user["User"]["validationCode"] = $validationCode;
				if($this->User->save($user)){
					$this->User->sendValidationEmail($user["User"]["email"], $validationCode);	
				}
			}else{
				$this->Session->setFlash("Sorry, no user with that email address");
			}

		}
	}

	public function sessionStart($validationCode = false){
		if(!$validationCode){
			$this->redirect(array("controller"=>"profile"));
		}
		$this->layout = "ajax";
		$user = $this->User->find("first", array(
			"conditions"=>array(
				"User.validationCode"=>$validationCode
				),
			"recursive"=>-1
		));
		if($user){
			$this->Auth->login($user);
			$this->Cookie->write("User.id", $user["User"]["id"]);
			$this->Session->setFlash("Welcome Back ".$user["User"]["username"]."!");
		}else{
			$this->Session->setFlash("Sorry we don't have a user with that validation code in our databases");
		}

		$this->redirect(array("controller"=>"profile"));
		$this->render(false);
	}

	// public function profile() {
	// 	$this->layout = 'floatr';

	// 	$user = $this->User->find('first', array(
	// 		"conditions"=>array(
	// 			"User.id"=>$this->Auth->user("id")
	// 			),
	// 		"recursive"=>0
	// 		));
	// 	unset($user["User"]["validationCode"]);
	// 	$this->data = $user;
	// }


	public function val(){
		$params = array();
		parse_str($this->data, $params);
		$this->data = $params;
		//$this->data = array("user_id"=>4, "email"=>"billy.rennekamp@gmail.com", "validationCode"=>"564b8fe12ed1f");
		$user = $this->User->find("first", array(
			"conditions"=>array(
				"User.id"=>$this->data["user_id"]
				),
			"recursive"=>-1
			));

		if(isset($this->data["validationCode"]) && $this->data["validationCode"] != ""){
			if($this->data["validationCode"] == $user["User"]["validationCode"]){
				$user["User"]["emailValidation"] = 1;
				if($this->User->save($user)){
					$return = array("success"=>true, "error"=>false);
				}else{
					$return = array("success"=>false, "error"=>$this->User->validationErrors);
				}
			}else{
				$return = array("success"=>false, "error"=>"Validation Code is not correct");
			}
		}else{
			$newValidation = md5(uniqid(rand(), true));

			$user["User"]["validationCode"] = $newValidation;
			$user["User"]["email"] = $this->data["email"];
			if($this->User->save($user)){
				$this->User->sendValidationEmail($this->data["email"], $this->User->field("validationCode"));
				$return = array("success"=>true, "error"=>false);
			}else{
				$return = array("success"=>false, "error"=>$this->User->validationErrors);
			}
		}
		echo json_encode($return);


		$this->render(false);
	}


	public function view($user_id = null){
		if(!$user_id){
			$ext = false;
			$user_id = $this->Auth->user("id");
		}else{
			$ext = true;
		}
		if($this->RequestHandler->isAjax()){
			$this->layout = "ajax";
		}
		$user = $this->User->find("first", array(
			"conditions"=>array(
				"User.id"=>$user_id
				),
			//"recursive"=>-1,
			)
		);
		//debug($user);
		$this->set(compact("user"));
	}



	public function login(){
		$this->Auth->logout();
		if(!empty($this->data)){
			$user = $this->User->find("first", array(
				"conditions"=>array(
					"User.username"=>$this->data["User"]["username"]
					),
				"recursive"=>-1
				));
			if ($this->Auth->login()) {
	            $this->Cookie->write("User.id", $this->Auth->user("id"));
				return $this->redirect($this->Auth->redirect());
			} else {
			    $this->Session->setFlash(__('Username or password is incorrect'));
			}
		}
		$this->redirect(array("controller"=>"photos", "action"=>"index"));
	}


	public function logout(){
		$this->Cookie->destroy("User.id");
		if($this->Auth->logout()){
			return $this->redirect($this->Auth->logoutRedirect);
		}else{
			die("??");
		}
	}





}
