<?php
App::uses('AppController', 'Controller');
/**
 * GroupMembers Controller
 *
 * @property GroupMember $GroupMember
 * @property PaginatorComponent $Paginator
 */
class GroupMembersController extends AppController {

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
		$this->GroupMember->recursive = 0;
		$this->set('groupMembers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->GroupMember->exists($id)) {
			throw new NotFoundException(__('Invalid group member'));
		}
		$options = array('conditions' => array('GroupMember.' . $this->GroupMember->primaryKey => $id));
		$this->set('groupMember', $this->GroupMember->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->GroupMember->create();
			if ($this->GroupMember->save($this->request->data)) {
				$this->Session->setFlash(__('The group member has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group member could not be saved. Please, try again.'));
			}
		}
		$users = $this->GroupMember->User->find('list');
		$groups = $this->GroupMember->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->GroupMember->exists($id)) {
			throw new NotFoundException(__('Invalid group member'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->GroupMember->save($this->request->data)) {
				$this->Session->setFlash(__('The group member has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The group member could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('GroupMember.' . $this->GroupMember->primaryKey => $id));
			$this->request->data = $this->GroupMember->find('first', $options);
		}
		$users = $this->GroupMember->User->find('list');
		$groups = $this->GroupMember->Group->find('list');
		$this->set(compact('users', 'groups'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->GroupMember->id = $id;
		if (!$this->GroupMember->exists()) {
			throw new NotFoundException(__('Invalid group member'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->GroupMember->delete()) {
			$this->Session->setFlash(__('The group member has been deleted.'));
		} else {
			$this->Session->setFlash(__('The group member could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
