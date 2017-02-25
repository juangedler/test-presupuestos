<?php
App::uses('AppModel', 'Model');

class Media extends AppModel {
	
	
	const MEDIA_MERCHANDISING = 8;

	public $useTable = 'medias';
	public $displayField = 'name';


	public $hasMany = array(
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'media_id',
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
