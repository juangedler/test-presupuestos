<?php
App::uses('AppController', 'Controller');
/**
 * GroupTypes Controller
 *
 * @property GroupType $GroupType
 * @property PaginatorComponent $Paginator
 */
class GroupTypesController extends AppController {

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
		$this->GroupType->recursive = 0;
		$this->set('groupTypes', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GroupType->exists($id)) {
			throw new NotFoundException(__('Invalid group type'));
		}
		$options = array('conditions' => array('GroupType.' . $this->GroupType->primaryKey => $id));
		$this->set('groupType', $this->GroupType->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GroupType->create();
			if ($this->GroupType->save($this->request->data)) {
				$this->Session->setFlash(__('The group type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group type could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->GroupType->exists($id)) {
			throw new NotFoundException(__('Invalid group type'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupType->save($this->request->data)) {
				$this->Session->setFlash(__('The group type has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GroupType.' . $this->GroupType->primaryKey => $id));
			$this->request->data = $this->GroupType->find('first', $options);
		}
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->GroupType->id = $id;
		if (!$this->GroupType->exists()) {
			throw new NotFoundException(__('Invalid group type'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->GroupType->delete()) {
			$this->Session->setFlash(__('The group type has been deleted.'));
		} else {
			$this->Session->setFlash(__('The group type could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
