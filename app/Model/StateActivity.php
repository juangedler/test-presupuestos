<?php
App::uses('AppModel', 'Model');
/**
 * StateActivity Model
 *
 * @property Activity $Activity
 */
class StateActivity extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'state_activity';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'state_id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'activity_id' => array(
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
		'Activity' => array(
			'className' => 'Activity',
			'foreignKey' => 'activity_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
