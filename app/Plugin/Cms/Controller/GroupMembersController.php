<?php
App::uses('AppController', 'Controller');

class GroupMembersController extends CmsAppController {

	public $components = array('Paginator');


	public function index() {
		$this->GroupMember->recursive = 0;
		$this->set('groupMembers',$this->GroupMember->find('all'));
	}

	public function view($id = null) {
		if (!$this->GroupMember->exists($id)) {
			throw new NotFoundException(__('Invalid group member'));
		}
		$options = array('conditions' => array('GroupMember.' . $this->GroupMember->primaryKey => $id));
		$this->set('groupMember', $this->GroupMember->find('first', $options));
	}

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

	public function pertenece($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('User');
		$this->loadModel('Group');
		$this->loadModel('GroupUserType');

		$this->GroupMember->recursive = -1;
		$gm = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $in),
			'fields' => array('GroupMember.group_id')
			));

		$this->User->recursive = -1;
		$user = $this->User->find('first', array(
			'conditions' => array('User.id' => $in),
			'fields' => array('User.user_type_id'),
			));

		$this->GroupUserType->recursive = -1;
		$gt = $this->GroupUserType->find('all', array(
			'conditions' => array('GroupUserType.user_type_id' => $user['User']['user_type_id']),
			'fields' => array('GroupUserType.group_type_id'),
			));

		$gts = array();

		foreach ($gt as $ggtt) {
			$gts[] = $ggtt['GroupUserType']['group_type_id'];
		}

		$this->Group->recursive = -1;
		$gs = $this->Group->find('all', array(
			'conditions' => array('Group.group_type_id' => $gts),
			));

		$grm = array();

		foreach ($gm as $g) {
			$grm['GroupsIsIn'][] = $g['GroupMember']['group_id'];
		}

		foreach ($gs as $g) {
			$grm['GroupsByType'][] = $g['Group']['id'];
		}

		$jsonstring = json_encode($grm);
 		echo $jsonstring;
	}

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
