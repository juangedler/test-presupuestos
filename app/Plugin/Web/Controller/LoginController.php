<?php

App::uses('AppController', 'Controller');

class LoginController extends AppController {
	
	public function beforeFilter() {
	 	parent::beforeFilter();
    }
	
	public function index(){
		$this->layout = false;
	}

	public function login(){
		App::uses('Security', 'Utility'); 
		$this->loadModel('User');
		$this->layout = false;
		$data = $this->User->findByusername($this->request->data['username']);
		if(count($data) == 0){
			$this->set('err', '1');
			$this->render('index');
		}
		else {
			if($data['User']['password'] != Security::hash($this->request->data['password'], 'sha1', true)){
				$this->set('err', '1');
				$this->render('index');
			}
			else {
				if($data['User']['activated'] == 'SI'){
					$this->Session->write('current_user', $data['User']['username']);
					$this->Session->write('name', $data['User']['first_name'].' '.$data['User']['last_name']);
					$this->Session->write('type', $data['UserType']['name']);
					$this->Session->write('type_id', $data['UserType']['id']);
					$this->Session->write('id', $data['User']['id']);
					$this->Session->write('activated', $data['User']['activated']);
					$this->redirect('/web');
				}
				else {
					$this->Session->write('current_user', $data['User']['username']);
					$this->Session->write('name', $data['User']['first_name'].' '.$data['User']['last_name']);
					$this->Session->write('type', $data['UserType']['name']);
					$this->Session->write('type_id', $data['UserType']['id']);
					$this->Session->write('id', $data['User']['id']);
					$this->Session->write('activated', $data['User']['activated']);
					$this->redirect('/web/bienvenida/');
				}
			}
		}
	}

	public function out(){
		$this->layout = false;
		$this->Session->delete('current_user');
		$this->Session->delete('name');
		$this->Session->delete('type');
		$this->Session->delete('id');
		$this->Session->delete('activated');
		$this->set('out', '1');
		$this->render('index');
	}
}
