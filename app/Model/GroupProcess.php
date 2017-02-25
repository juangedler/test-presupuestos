<?php
App::uses('AppModel', 'Model');
/**
 * GroupProcess Model
 *
 * @property Process $Process
 */
class GroupProcess extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'group_process';

/**
 * Primary key field
 *
 * @var string
 */
	public $primaryKey = 'group_id';

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
		)
	);
}
