<?php
App::uses('AppModel', 'Model');
/**
 * Balance Model
 *
 * @property Group $Group
 */
class Balance extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'balance';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Group',
			'foreignKey' => 'group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
