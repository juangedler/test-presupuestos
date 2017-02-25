<?php
App::uses('AppModel', 'Model');
/**
 * Date Model
 *
 * @property Event $Event
 */
class Date extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'date';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
