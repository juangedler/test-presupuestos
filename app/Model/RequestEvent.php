<?php
App::uses('AppModel', 'Model');
/**
 * RequestEvent Model
 *
 * @property Request $Request
 * @property Event $Event
 */
class RequestEvent extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'request_event';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Request' => array(
			'className' => 'Request',
			'foreignKey' => 'request_id',
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
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'request_event_id',
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

}
