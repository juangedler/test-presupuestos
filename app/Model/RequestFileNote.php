<?php
App::uses('AppModel', 'Model');
/**
 * RequestFileNote Model
 *
 */
class RequestFileNote extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'request_file_note';



	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'RequestFile' => array(
			'className' => 'RequestFile',
			'foreignKey' => 'request_file_id',
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
		)
	);
}