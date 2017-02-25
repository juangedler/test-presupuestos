<?php
App::uses('AppModel', 'Model');
/**
 * Activity Model
 *
 * @property ActivityType $ActivityType
 * @property Process $Process
 * @property State $State
 */
class Activity extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'activity';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'activity_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
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
		'name' => array(
			'notEmpty' => array(
				'rule' => array('notEmpty'),
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
		'ActivityType' => array(
			'className' => 'ActivityType',
			'foreignKey' => 'activity_type_id',
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
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'State' => array(
			'className' => 'State',
			'joinTable' => 'state_activity',
			'foreignKey' => 'activity_id',
			'associationForeignKey' => 'state_id',
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
