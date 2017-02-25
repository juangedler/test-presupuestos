<?php
App::uses('AppModel', 'Model');
/**
 * GroupUserType Model
 *
 * @property UserType $UserType
 * @property GroupType $GroupType
 */
class GroupUserType extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'group_user_type';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'UserType' => array(
			'className' => 'UserType',
			'foreignKey' => 'user_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'GroupType' => array(
			'className' => 'GroupType',
			'foreignKey' => 'group_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
