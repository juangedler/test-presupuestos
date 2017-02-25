<?php
App::uses('AppModel', 'Model');
/**
 * Transition Model
 *
 * @property Process $Process
 * @property CurrentState $CurrentState
 * @property NextState $NextState
 * @property RequestAction $RequestAction
 * @property Action $Action
 */
class Transition extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'transition';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'process_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'current_state_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'next_state_id' => array(
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
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'CurrentState' => array(
			'className' => 'CurrentState',
			'foreignKey' => 'current_state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'NextState' => array(
			'className' => 'NextState',
			'foreignKey' => 'next_state_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'RequestAction' => array(
			'className' => 'RequestAction',
			'foreignKey' => 'transition_id',
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
			'joinTable' => 'transition_action',
			'foreignKey' => 'transition_id',
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

}
