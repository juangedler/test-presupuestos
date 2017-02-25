<?php

class MontoNacionalController extends WebAppController {

	public function beforeFilter(){
		parent::beforeFilter();

		if(!in_array($this->Session->read('type'), array('Ford', 'Administrador', 'Master'))) {
			$this->Session->setFlash(__('Permisos insuficientes para entrar en esta sección.'));
			$this->redirect('/web/error');
		}
	}

	public function index(){
		$this->redirect('/web/administrador/nacional/listar');
	}

	public function crear(){
		$actualizacion = 0;
		$this->loadModel('Balance');
		$this->loadModel('NationalMovement');
		$this->loadModel('NationalSingleMovement');

		$balance = $this->Balance->find('all',array(
			'fields'=>array('SUM(nacional) as total')
		));

		$balance = $balance[0][0]['total'];

		$balances = $this->Balance->find('all');

		$movements = [];


		if($this->request->is('post')){
			$nationalMovement['NationalMovement']['user_id'] = $this->Session->read('id');
			$nationalMovement['NationalMovement']['title'] = $this->request->data['nombre'];
			$nationalMovement['NationalMovement']['description'] = $this->request->data['descripcion'];
			$nationalMovement['NationalMovement']['amount'] = $this->request->data['monto'];

			$sum = 0;

			foreach ($balances as &$b) {
				$auxi = intval($b['Balance']['nacional'] / $balance * $this->request->data['monto']);
				$sum += $auxi;

				$single_movement[] = [
					'group_id' => $b['Balance']['group_id'], 
					'national' => $auxi, 
					'national_before' => $b['Balance']['nacional'], 
					'type' => NationalSingleMovement::CREATE
				];

				$b['Balance']['nacional'] -= $auxi; 
			}

			$single_movement[0]['national'] += ($this->request->data['monto']-$sum);

			$balances[0]['Balance']['nacional'] -= ($this->request->data['monto']-$sum);

			$datasource = $this->NationalMovement->getDataSource();
			try{
				if(!$this->NationalMovement->save($nationalMovement))
		        	throw new Exception();

		        foreach ($single_movement as &$sm) {
		        	$sm['national_movement_id'] = $this->NationalMovement->id;
		        }

		        if(!$this->NationalSingleMovement->saveAll($single_movement))
		        	throw new Exception();

		        if(!$this->Balance->saveAll($balances))
		        	throw new Exception();

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}
			$actualizacion = 1;
		}

		/*$movement = $this->NationalMovement->find('all',array(
			'fields'=>array('SUM(amount) as total')
		));
		
		$movement = $movement[0][0]['total'];

		$disponible = $balance - $movement;*/


		$disponible = $balance;

		$this->set(compact('actualizacion','disponible'));
	}

	public function listar(){
		$this->loadModel('NationalMovement');

		$nationalMovement = $this->NationalMovement->find('all', array(
			'conditions' => ['NationalMovement.status' => 1],
			'order' => array('NationalMovement.id' => 'DESC'),
			));

		$this->set(compact('nationalMovement'));
	}

	public function eliminadas(){
		$this->loadModel('NationalMovement');

		$nationalMovement = $this->NationalMovement->find('all', array(
			'conditions' => ['NationalMovement.status' => 0],
			'order' => array('NationalMovement.id' => 'DESC'),
			));

		$this->set(compact('nationalMovement'));
	}

	public function eliminar($id){
		$this->loadModel('Balance');
		$this->loadModel('NationalMovement');
		$this->loadModel('NationalSingleMovement');

		$this->NationalMovement->id = $id;
		$this->Balance->recursive = -1;


		$single_movements = $this->NationalSingleMovement->find('all', [
			'conditions' => ['national_movement_id' => $id, 'type' => NationalSingleMovement::CREATE]
			]);

		$balances = [];

		foreach ($single_movements as $sm) {
			$balance = $this->Balance->findBygroup_id($sm['NationalSingleMovement']['group_id']);

			$new_single_movements[] = [
				'national_movement_id' => $id,
				'group_id' => $sm['NationalSingleMovement']['group_id'], 
				'national' => $sm['NationalSingleMovement']['national'], 
				'national_before' => $balance['Balance']['nacional'], 
				'type' => NationalSingleMovement::DELETE
			];

			$balance['Balance']['nacional'] += $sm['NationalSingleMovement']['national'];

			$balances[] = $balance;
		}

		$movement = $this->NationalMovement->findByid($id);

		$datasource = $this->NationalMovement->getDataSource();

		try{
		    $datasource->begin();
			$movement['NationalMovement']['status'] = 0;
			if (!$this->NationalMovement->save($movement))
				throw new Exception();

			if(!$this->NationalSingleMovement->saveAll($new_single_movements))
	        	throw new Exception();

		    if(!$this->Balance->saveAll($balances))
	        	throw new Exception();

			$datasource->commit();
		} catch(Exception $e) {
		    $datasource->rollback();
		}
		return $this->redirect(array('action' => 'listar'));
	}
}