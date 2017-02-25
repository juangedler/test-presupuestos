<?php

class PerfilController extends AppController {

	public function index(){
		$this->loadModel('User');

		$actualizacion = 0;

		$this->Session->setFlash(__('Nombre y Apellido actualizados con éxito.'));

		if($this->request->is('post')){
			if(isset($this->request->data['password'])){
				App::uses('Security', 'Utility');
				$this->request->data['password'] = Security::hash($this->request->data['password'], 'sha1', true);
				$this->Session->setFlash(__('Contraseña actualizada con éxito.'));
			}
			else if(isset($_FILES['signature'])){
				$rand = mt_rand(1000000,1000000000);
				$firma_path = 'images/firmas/'. $rand .basename($_FILES['signature']['name']);
				move_uploaded_file($_FILES['signature']['tmp_name'], $firma_path);
				$this->request->data['signature'] = $firma_path;
				$this->Session->setFlash(__('Firma actualizada con éxito.'));
			}

			$this->User->id = $this->Session->read('id');
			$this->User->save($this->request->data);

			$actualizacion = 1;
		}

		$this->User->recursive = -1;
		$user = $this->User->find('first',array(
			'conditions' => array('User.id' => $this->Session->read('id')),
			));

		$this->loadModel('GroupMember');
		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$this->set(compact('user', 'actualizacion','group_types'));
	}
}