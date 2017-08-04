<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 *
 * @property Photo $Photo
 */
class User extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'username';


//public  $actsAs = array('Containable');

	public function getRandomId() {

		$randomAdjectives = array(
			"erratic",
			"same",
			"equal",
			"clear",
			"regular",
			"shallow",
			"glamorous",
			"purple",
			"neat",
			"thinkable",
			"quirky",
			"sick",
			"obsequious",
			"flagrant",
			"crazy",
			"enchanted",
			"aloof",
			"lively",
			"delicious",
			"miniature",
			"deeply",
			"confused",
			"public",
			"possible",
			"wooden",
			"rough",
			"blushing",
			"steady",
			"sudden",
			"closed",
			"fierce",
			"uptight",
			"busy",
			"judicious",
			"bite-sized",
			"brash",
			"relieved",
			"impossible",
			"second",
			"ajar",
			"rigid",
			"swift",
			"giant",
			"wanting",
			"imperfect",
			"groovy",
			"deafening",
			"guarded",
			"psychotic",
			"shiny"
		);

		$randomNouns = array(
			"animal",
			"laugh",
			"crate",
			"school",
			"farm",
			"trains",
			"mom",
			"flesh",
			"ray",
			"train",
			"touch",
			"statement",
			"houses",
			"parcel",
			"trip",
			"hour",
			"credit",
			"laborer",
			"direction",
			"wall",
			"sign",
			"purpose",
			"bone",
			"book",
			"spoon",
			"cable",
			"drop",
			"play",
			"curtain",
			"lace",
			"wound",
			"move",
			"turn",
			"bead",
			"trees",
			"horse",
			"person",
			"dock",
			"food",
			"hose",
			"effect",
			"chicken",
			"hole",
			"sock",
			"poison",
			"distance",
			"hope",
			"example",
			"payment",
			"brother"
		);

		return ucfirst($randomAdjectives[array_rand($randomAdjectives)]).ucfirst($randomAdjectives[array_rand($randomAdjectives)]).ucfirst($randomNouns[array_rand($randomNouns)]);

	}




	public function sendValidationEmail($email = null, $validationCode = null){
		App::uses('CakeEmail', 'Network/Email');
		$Email = new CakeEmail("zoho");
		$Email->from(array('admin@floatrapp.com' => 'floatr admin'))
	    ->to($email)
	    ->subject('Access your account')
	    ->send('Go to '.FULL_BASE_URL.$this->webroot."/users/sessionStart/".$validationCode.' to recover your account');
	}

	public function tempUser(){
			$group_id = 2;

			$validationCode = md5(uniqid(rand(), true));

			$newUser = array("User"=>array(
				"group"=>$group_id,
				"ip"=>$_SERVER["REMOTE_ADDR"],
				"photo_count"=>0,
				"username"=>$this->getRandomId(),
				"validationCode"=>$validationCode,
				"validated"=>0
				));

			$this->set($newUser);
			for($i=0; $i < 100; $i++) {
				if ($this->validates()) {
					break;
				} else {
					$newUser['User']['username'] = $this->getRandomId();
					$this->set($newUser);
				}
			}

			$this->create();
			if(!$this->save($newUser)){
				debug($this->validationErrors);
				die("invalid user");
			}else{
				$newUser["User"]["id"] = $this->getLastInsertId();
			}
			return $newUser;
	}

	public function beforeValidate($options = array()){
		
        return true;
	}

    public function beforeSave($options = array()) {
        return parent::beforeSave($options);
    }

    public function afterSave($newUser = null){
    	if(!$newUser){
    	    $user = $this->find("first", array(
	    		"conditions"=>array(
	    			"User.id"=>$this->data["User"]["id"]
	    			),
	    		"recursive"=>-1
	    		)
	    	);	
	    	CakeSession::write("Auth", $user);
    	}
    }











/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'Please try another username',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Sorry that username is already taken',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'email' => array(
			'email' => array(
				'rule' => array('email'),
				'message' => 'Please enter a valid email address',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Sorry that email address is already taken',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'validated' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'validationCode' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'group' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'photo_count' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Photo' => array(
			'className' => 'Photo',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => '',
			'counterCache'=>true
		)
	);

}
