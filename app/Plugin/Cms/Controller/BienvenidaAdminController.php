<?php

class BienvenidaAdminController extends AppController {

	public function index(){
		$this->layout = false;
	}

	public function agencia(){
		$this->layout = false;
	}

	public function activar($in){
		$this->layout = false;
		$this->autoRender = false;

		App::uses('Security', 'Utility');
		$sha1 = Security::hash($this->request->data['password1'], 'sha1', true);

		$this->loadModel('User');
		$this->User->id = $in;

		$datasource = $this->User->getDataSource();
		try{
		    $datasource->begin();

			if(!$this->User->saveField('password', $sha1))
				throw new Exception();

			if(!$this->User->saveField('activated', 'SI'))
				throw new Exception();

			$this->Session->write('activated', 'SI');

			$datasource->commit();
		} catch(Exception $e) {
		    $datasource->rollback();
			$this->Session->write('activated', 'NO');
		}

		$this->redirect('/cms');
	}
}