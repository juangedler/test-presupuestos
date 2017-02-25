<?php
App::uses('AppModel', 'Model');
/**
 * Merchandising Model
 *
 * @property Event $Event
 */
class Merchandising extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'merchandising';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


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
