<?php
App::uses('AppModel', 'Model');
/**
 * NationalSingleMovement Model
 *
 */
class NationalSingleMovement extends AppModel {

	const CREATE = 'DEDUCCION';
	const DELETE = 'REEMBOLSO';


/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'national_single_movement';

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
		),
		'NationalMovement' => array(
			'className' => 'NationalMovement',
			'foreignKey' => 'national_movement_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
