<?php
App::uses('AppController', 'Controller');
App::uses('State', 'Model');
/**
 * Groups Controller
 *
 * @property Group $Group
 * @property PaginatorComponent $Paginator
 */
class GroupsController extends CmsAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Group->recursive = 0;
		$this->set('groups', $this->Group->find('all'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
		$this->set('group', $this->Group->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Group->create();
			if ($this->Group->save($this->request->data)) {
				$this->loadModel('Balance');
				$datos['Balance']['group_id'] = $this->Group->id;
				$datos['Balance']['balance'] = 0;
				$datos['Balance']['pending'] = 0;
				$datos['Balance']['nacional'] = 0;
				$this->Balance->create();
				$this->Balance->save($datos);
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		}
		$groupTypes = $this->Group->GroupType->find('list');
		//$processes = $this->Group->Process->find('list');
		$this->set(compact('groupTypes', 'processes'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Group->exists($id)) {
			throw new NotFoundException(__('Invalid group'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Group->save($this->request->data)) {
				$this->Session->setFlash(__('The group has been saved.'));
				return $this->redirect(array('action' => 'view/'.$this->Group->id));
			} else {
				$this->Session->setFlash(__('The group could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Group.' . $this->Group->primaryKey => $id));
			$this->request->data = $this->Group->find('first', $options);
		}
		$groupTypes = $this->Group->GroupType->find('list');
		//$processes = $this->Group->Process->find('list');
		$this->set(compact('groupTypes', 'processes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Group->id = $id;
		
		if (!$this->Group->exists()) {
			throw new NotFoundException(__('Invalid group'));
		}
		
		$this->loadModel('Balance');
		$balance = $this->Balance->findBygroup_id($id);
		$datasource = $this->Group->getDataSource();
		
		try{
		    $datasource->begin();
			if(!$this->Balance->delete($balance['Balance']['id']))
				throw new Exception();
			$this->request->allowMethod('post', 'delete');

			/*$this->Request->updateAll(
			    array('Request.current_state_id' => State::STATE_BORRADO),
			    array('Request.group_id' => $id)
			);*/
			
			/*$this->Group->id = $id;
		    $this->Group->set(array('state_id' => State::STATE_BORRADO));
			$this->Group->save();*/

			if ($this->Group->delete()) {
				$this->Session->setFlash(__('The group has been deleted.'));
			} else {
				throw new Exception();
				$this->Session->setFlash(__('The group could not be deleted. Please, try again.'));
			}
			$datasource->commit();
		} catch(Exception $e) {
		    $datasource->rollback();
		}
		return $this->redirect(array('action' => 'index'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function asignar() {
		$this->loadModel('User');
		$data = $this->User->find('all');
		$this->set('user',$data);
		$this->loadModel('Group');
		$data = $this->Group->find('all');
		$this->set('group',$data);
	}
}
