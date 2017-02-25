<?php
App::uses('AppModel', 'Model');

class Event extends AppModel {

	public $useTable = 'event';

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'RequestEvent' => array(
			'className' => 'RequestEvent',
			'foreignKey' => 'request_event_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Media' => array(
			'className' => 'Media',
			'foreignKey' => 'media_id',
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
		'Date' => array(
			'className' => 'Date',
			'foreignKey' => 'event_id',
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
