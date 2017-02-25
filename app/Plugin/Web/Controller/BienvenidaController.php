<?php

class BienvenidaController extends AppController {

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

		$firma_path = '';

		$rand = mt_rand(1000000,1000000000);

        if(in_array($this->Session->read('type'), array('JWT','Ford','Mindshare')))
			$firma_path = 'images/firmas/'. $rand .basename($_FILES['firma']['name']);

		$datasource = $this->User->getDataSource();
		try{
		    $datasource->begin();

			if(!$this->User->saveField('password', $sha1))
				throw new Exception();

			if(!$this->User->saveField('activated', 'SI'))
				throw new Exception();

			if(!$this->User->saveField('signature', $firma_path))
				throw new Exception();

        	if(in_array($this->Session->read('type'), array('JWT','Ford','Mindshare')))
			move_uploaded_file($_FILES['firma']['tmp_name'], 'images/firmas/'. $rand .basename($_FILES['firma']['name']));

			$this->Session->write('activated', 'SI');

			$datasource->commit();
		} catch(Exception $e) {
		    $datasource->rollback();
			$this->Session->write('activated', 'NO');
		}

		$this->redirect('/web');
	}
}