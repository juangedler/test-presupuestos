<?php
App::uses('AppModel', 'Model');
/**
 * Request Model
 *
 * @property RequestType $RequestType
 * @property User $User
 * @property Group $Group
 * @property Process $Process
 * @property CurrentState $CurrentState
 * @property AdsFlow $AdsFlow
 * @property Movement $Movement
 * @property Action $Action
 * @property Event $Event
 */
class Request extends AppModel {

	const SUPPORTABLE_ENABLED = 1;
	const SUPPORTABLE_DISABLED = 1;

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'request';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'title';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'RequestType' => array(
			'className' => 'RequestType',
			'foreignKey' => 'request_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'AdsFlow' => array(
			'className' => 'AdsFlow',
			'foreignKey' => 'request_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Movement' => array(
			'className' => 'Movement',
			'foreignKey' => 'request_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Action' => array(
			'className' => 'Action',
			'joinTable' => 'request_action',
			'foreignKey' => 'request_id',
			'associationForeignKey' => 'action_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

	public function enable($id){
		$query = $this->findById($id);
		if($query['Request']['current_state_id'] != 3) return 0;
		$query['Request']['supportable'] = 1;
		$this->save($query);
	}

	public function disable($id){
		$query = $this->findById($id);
		if($query['Request']['current_state_id'] != 3) return 0;
		$query['Request']['supportable'] = 0;
		$this->save($query);
	}

	public function is_supportable($id){
		$query = $this->findById($id);
		if($query['Request']['current_state_id'] != 3) return 0;
		return $query['Request']['supportable'];
	}
}
