<?php

App::uses('AppController', 'Controller');

class LoginAController extends AppController {
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
				if($data['UserType']['id'] == 1 || $data['UserType']['id'] == 5){
					if($data['User']['activated'] == 'SI'){
						$this->Session->write('current_admin', $data['User']['username']);
						$this->Session->write('admin_name', $data['User']['first_name'].' '.$data['User']['last_name']);
						$this->Session->write('admin_type', $data['UserType']['name']);
						$this->Session->write('admin_id', $data['User']['id']);
						$this->Session->write('admin_activated', $data['User']['activated']);
						$this->redirect('/cms');
					}
					else {
						$this->Session->write('current_admin', $data['User']['username']);
						$this->Session->write('admin_name', $data['User']['first_name'].' '.$data['User']['last_name']);
						$this->Session->write('admin_type', $data['UserType']['name']);
						$this->Session->write('admin_id', $data['User']['id']);
						$this->Session->write('admin_activated', $data['User']['activated']);
						$this->redirect('/cms/bienvenidaAdmin');
					}
				}
				else{
					$this->set('err', '1');
					$this->render('index');
				}
			}
		}
	}
	public function out(){
		$this->layout = false;
		$this->Session->delete('current_admin');
		$this->set('out', '1');
		$this->render('index');
	}
}
