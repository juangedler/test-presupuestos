<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends CmsAppController {
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
		$this->User->recursive = 0;
		$this->set('users', $this->User->find('all'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->loadModel('GroupMember');
		$groupMembers = $this->GroupMember->findAllByuser_id($id);
		$group = array();
		foreach ($groupMembers as &$gm) {
			$group[] = $gm['Group'];
		}
		$this->set(compact('group'));

		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}


	private function randomPassword() {
		    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		    $pass = array(); //remember to declare $pass as an array
		    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		    for ($i = 0; $i < 8; $i++) {
		        $n = rand(0, $alphaLength);
		        $pass[] = $alphabet[$n];
		    }
		    return implode($pass); //turn the array into a string
		}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Group');
		$groups = $this->Group->find('list');
		$this->set(compact('groups'));

		if ($this->request->is('post')) {

			$user_type;

			switch ($this->request->data['User']['user_type_id']) {
				case 1:
					$user_type = 'Administrador';
					break;
				case 2:
					$user_type = 'Concesionario';
					break;
				case 3:
					$user_type = 'Agencia';
					break;
				case 4:
					$user_type = 'Ford';
					break;
				case 6:
					$user_type = 'Mindshare';
					break;
			}

			$pass = $this->randomPassword();
			App::uses('Security', 'Utility');
			$sha1 = Security::hash($pass, 'sha1', true);

			$this->User->create();
			$this->request->data['User']["password"] = $sha1;

			try{
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved.'));

					$this->loadModel('GroupMember');

					if(isset($this->request->data['User']['groups']) && $this->request->data['User']['groups']!= NULL)
					foreach ($this->request->data['User']['groups'] as &$g) {
						$this->GroupMember->create();
						$datos['GroupMember']['user_id'] = $this->User->id;
						$datos['GroupMember']['group_id'] = $g;
						$this->GroupMember->save($datos);
					}

					$mensaje= 'Bienvenido '.$this->request->data['User']['first_name'].' '.$this->request->data['User']['last_name'].'. <br>
							   Su cuenta de usuario de tipo <b>'.$user_type.'</b> ha sido creada exitosamente.<br><br>
							   Su usuario es: '.$this->request->data['User']["username"].'<br>
							   Su contraseña es: '.$pass.'<br><br>
							   Puede acceder a su cuenta haciendo click en este enlace: ';
					if($user_type == 'Administrador')
						$mensaje = $mensaje.'http://fprford.com/cms';
					else
						$mensaje = $mensaje.'http://fprford.com/web';

					App::uses('CakeEmail', 'Network/Email');
					$Email = new CakeEmail();
					$Email->config('default');
					$Email->to($this->request->data['User']['email']);
					$Email->subject('[ FORD : Nuevo Ingreso de Usuario ]');
					$Email->send($mensaje);

					return $this->redirect(array('action' => 'index'));
				} else {
					throw new Exception(__('Falla en eliminación de usuario'));
				}
			} catch (Exception $e){
				$this->Session->setFlash(__('Falla en creación de usuario. <br>Asegúrese de que no exista ningún usuario con este username o que no exista usuario cuyo tipo coincida con el correo electrónico provisto.'));
				return $this->redirect(array('action' => 'error'));
			}

		}
		$userTypes = $this->User->UserType->find('list');
		$this->set(compact('userTypes'));
	}

	public function reset_password($id = null) {

		$pass = $this->randomPassword();
		App::uses('Security', 'Utility');
		$sha1 = Security::hash($pass, 'sha1', true);

		$this->User->id = $id;
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		else {
			if ($this->User->saveField('password', $sha1)) {
				$this->User->recursive = -1;
				$user = $this->User->find('first', array(
					'conditions' => array('User.id' => $id),
					));

				$mensaje= 'Hola '.$user['User']['first_name'].' '.$user['User']['last_name'].'. <br>
						   Hemos restablecido su contraseña.<br><br>
						   Su usuario es: '.$user['User']["username"].'<br>
						   Su contraseña es: '.$pass.'<br><br>
						   Puede acceder a su cuenta haciendo click en este enlace: <br><br>
						   http://fprford.com/web
						   <br><br>
						   Le recomendamos cambiar su contraseña accediendo a su perfil de usuario.
						   <br><br>
						   Saludos.
						   ';
			
				App::uses('CakeEmail', 'Network/Email');
				$Email = new CakeEmail();
				$Email->config('default');
				$Email->to($user['User']['email']);
				$Email->subject('[ FORD : Restablecimiento de Contraseña ]');
				$Email->send($mensaje);

				$this->Session->setFlash(__('Se ha reestablecido la contraseña correctamente.'));
				return $this->redirect(array('action' => 'index'));
			}
		}
	}

	public function validar($in){
		$this->autoRender = false;
		$data = $this->User->findByusername($in);
		if (count($data) > 0) echo 'exist';
		else echo 'ok';
	}

	public function pertenece($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Group');
		$this->loadModel('GroupUserType');

		$this->GroupUserType->recursive = -1;
		$gt = $this->GroupUserType->find('all', array(
			'conditions' => array('GroupUserType.user_type_id' => $in),
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

		foreach ($gs as $g) {
			$grm['GroupsByType'][] = $g['Group']['id'];
		}

		$jsonstring = json_encode($grm);
			echo $jsonstring;
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			try{
				if ($this->User->save($this->request->data)) {
					$this->Session->setFlash(__('The user has been saved.'));
					return $this->redirect(array('action' => 'index'));
				} else {
					throw new Exception(__('Falla en eliminación de usuario'));
				}
			} catch (Exception $e){
				$this->Session->setFlash(__('Falla en edición de usuario. <br>Asegúrese de que no exista ningún usuario con este username o que no exista usuario cuyo tipo coincida con el correo electrónico provisto.'));
				return $this->redirect(array('action' => 'error'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$userTypes = $this->User->UserType->find('list');
		$this->set(compact('userTypes'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$error = 0;

		$master = $this->User->find('first',array('conditions' => array('User.id' => $id)));
		if($master['User']['user_type_id'] == 5){
			$this->Session->setFlash(__('Falla en eliminación de usuario.<br> Este usuario es el maestro del sistema. <br><br>No puede ser eliminado.'));
			return $this->redirect(array('action' => 'error'));
		}

		
		
		if ($this->request->is(array('post')) && count($this->request->data) > 0) {
			$this->loadModel('Request');
			$this->loadModel('RequestNote');
			$this->loadModel('RequestFileNote');
			$this->loadModel('RequestSupport');

			foreach ($this->request->data as $key => $value) {
				$this->Request->updateAll(
				    array('Request.user_id' => $value),
				    array('Request.group_id' => $key, 'Request.user_id' => $id)
				);

				$this->RequestNote->updateAll(
				    array('RequestNote.user_id' => $value),
				    array('RequestNote.user_id' => $id)
				);
				
				$this->RequestFileNote->updateAll(
				    array('RequestFileNote.user_id' => $value),
				    array('RequestFileNote.user_id' => $id)
				);
	
				$this->RequestSupport->updateAll(
				    array('RequestSupport.user_id' => $value),
				    array('RequestSupport.user_id' => $id)
				);
				
			}
		}

		try{
			$this->User->id = $id;
			if (!$this->User->exists()) {
				$error = 1;
				throw new NotFoundException(__('Invalid user'));
			}
			$this->request->allowMethod('post', 'delete');

			$this->loadModel('Request');
			$this->loadModel('RequestNote');

			$this->Request->recursive = -1;
			$requests = $this->Request->find('all', array(
				'conditions' => array('Request.user_id' => $id),
				));

			$this->RequestNote->recursive = -1;
			$requestsNotes = $this->RequestNote->find('all', array(
				'conditions' => array('RequestNote.user_id' => $id),
				));

			if(count($requests) == 0 && count($requestsNotes) == 0)
			{
				$this->loadModel('GroupMember');
				$this->GroupMember->deleteAll(array('GroupMember.user_id' => $id),false,false);
			}

			if ($this->User->delete()) {
				$error = 2;
				$this->Session->setFlash(__('The user has been deleted.'));
			} else {
				$error = 3;
				throw new Exception(__('Falla en eliminación de usuario'));
			}
		} catch (Exception $e){
			if($error == 0){
				$error = 9;
				$this->Session->setFlash(__('Falla en eliminación de usuario. <br>Asegúrese de que el usuario no pertenezca a ningún grupo antes de eliminarlo.'));
			
				$this->loadModel('GroupMember');
				$groups = $this->GroupMember->find('all', array(
					'conditions' => array('GroupMember.user_id' => $id),
					'fields' => array('Group.id','Group.name')
					));

				foreach ($groups as &$g) {
					$g['Users'] = $this->GroupMember->find('all', array(
						'conditions' => array('GroupMember.group_id' => $g['Group']['id']),
						'fields' => array('User.id','User.first_name','User.last_name')
						));
				}

				$this->set(compact('groups','id'));
			}
			
			else return $this->redirect(array('action' => 'error'));
		}

		if($error == 2 || $error == 1) return $this->redirect(array('action' => 'index'));
	}

	public function error(){}
}
