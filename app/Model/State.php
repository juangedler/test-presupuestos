<?php
App::uses('AppModel', 'Model');

class State extends AppModel {
	
	const STATE_CREADO = 1;
	const STATE_APROBADO_JWT = 2;
	const STATE_APROBADO_FORD = 3;
	const STATE_RECHAZADO_JWT = 4;
	const STATE_RECHAZADO_FORD = 5;
	const STATE_BORRADO = 10;
	const STATE_ANULADO = 999;
	const JWT_APROBADO_FORD = 8;
	const JWT_ANULADO = 998;


	public $useTable = 'state';

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
		'StateType' => array(
			'className' => 'StateType',
			'foreignKey' => 'state_type_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasAndBelongsToMany associations
 *
 * @var array
 */
	public $hasAndBelongsToMany = array(
		'Activity' => array(
			'className' => 'Activity',
			'joinTable' => 'state_activity',
			'foreignKey' => 'state_id',
			'associationForeignKey' => 'activity_id',
			'unique' => 'keepExisting',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
		)
	);

}
