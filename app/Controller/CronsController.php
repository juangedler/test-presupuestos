<?php
App::uses('AppController', 'Controller');
App::uses('GroupType', 'Model');
App::uses('State', 'Model');
App::uses('UserType', 'Model');

class CronsController extends AppController {
	
	public $uses = array("Group", "Request", "User", "HistoricalBalance", "Balance", "GroupMember");
	
	//------------------------------------------------------------------
	public function pendingRequestNotifications(){
		$this->autoRender=false;
		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id')
		));

		$g_id = array();

		foreach ($groups as $g) {
			$g_id[] = $g['Group']['id'];
		}
		
		$requests = $this->Request->find('count', array(
			'conditions'=> array('Request.group_id' => $g_id,'Request.current_state_id' => State::STATE_APROBADO_JWT),
			'recursive'=> -1
		));

		$this->sendEmail(1,$requests,1);
	}
	
	//------------------------------------------------------------------
	public function saveHistoricalBalances(){
		$this->autoRender=false;
		
		$balances = $this->Balance->find('all', array(
			'recursive'=> -1
		));
		
		foreach ($balances as $keyBalance => $balance) {
			$this->HistoricalBalance->create();
			$data = array(
				'HistoricalBalance' => array(
					'group_id' => $balance['Balance']['group_id'],
			         'balance'=>$balance['Balance']['balance'],
			         'pending'=>$balance['Balance']['pending'],
			         'nacional'=>$balance['Balance']['nacional'],
			         'mpending'=>$balance['Balance']['mpending'],
		             'merchandising'=>$balance['Balance']['merchandising']
	        	)
			);
			
			try{
				if(!$this->HistoricalBalance->save($data))
	        		throw new Exception();
				
			}catch(Exception $e) {
				$this->log('Error al guardar los historicos de los balances. Balance: '.$balance['Balance']['id'].'Excepcion:'. $e);
			}
		}
	}
	
	//------------------------------------------------------------------
	public function sendEmail($grupo,$request,$tipo){
		switch ($grupo) {
			case 1: //Correo solicitudes pendientes (Ford)
				$usersMail = $this->Group->find('all', array(
					'conditions' => array(
						'Group.group_type_id' => array(GroupType::GROUP_TYPE_FORD_NIVEL_1, GroupType::GROUP_TYPE_FORD_NIVEL_2),
						/*'User.id' => '155'*/),
					'joins' => array(
						array(
				            'table' => 'group_member',
				            'alias' => 'GroupMember',
				            'type' => 'INNER',
				            'conditions' => array(
				           		'GroupMember.group_id = Group.id'
							)
						),
				        array(
				            'table' => 'user',
				            'alias' => 'User',
				            'type' => 'INNER',
				            'conditions' => array(
				            	'User.id = GroupMember.user_id'
							)
						)
					),
					'fields' => array('User.id', 'User.email', 'User.first_name', 'User.last_name'),
					'recursive' => -1,
					'group'=>array('User.id')
				));
			break;
		}

		App::uses('CakeEmail', 'Network/Email');

		$subject = '';
		$content = '';

		switch ($tipo) {
			case 1:
				$subject = '[ Recordatorio de solicitudes pendientes ]';
				$copyRequest="solicitudes";
				$copyReview="revisarlas";
				
				if($request==1){
					$copyRequest ="solicitud";
					$copyReview="revisarla";
				}
				
				$content = '<br><br>El día de hoy hay <b>'.$request.' </b> '.$copyRequest.' sin procesar.<br><br>Para '.$copyReview.' haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
		}

	    foreach ($usersMail as $um) {
			$Email = new CakeEmail();
			$Email->config('default');
			$Email->to($um['User']['email']);
			$Email->subject($subject);
			$Email->send('Hola '.$um['User']['first_name'].' '.$um['User']['last_name'].$content);
	    }
	}
	
	
}