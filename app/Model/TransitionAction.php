<?php
App::uses('AppModel', 'Model');
/**
 * TransitionAction Model
 *
 * @property Action $Action
 */
class TransitionAction extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'transition_action';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'transition_id';

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'action_id' => array(
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
		'Action' => array(
			'className' => 'Action',
			'foreignKey' => 'action_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
