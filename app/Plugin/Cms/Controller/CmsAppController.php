<?php

App::uses('AppController', 'Controller');

class CmsAppController extends AppController {
	public function beforeFilter(){
		parent::beforeFilter();
	    if($this->Session->read('current_admin') == NULL) {
	        $this->redirect('/cms/loginA');
	    }
	}
	
	//-----------------------------------------------------------------------------------------------------------------	
	public function printWithFormat($var, $withDie = false) { //Funcion de control para imprimir resultados
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		
		if($withDie) {
			die();
		}
	}
}
