<?php

class WebController extends WebAppController {
	public function index(){
		$this->loadModel('Request');
		$this->loadModel('GroupMember');

		switch ($this->Session->read('type')) {
			case 'Concesionario':{
				$groups = $this->GroupMember->find('all', array(
					'conditions' => array('User.username' => $this->Session->read('current_user')),
					'fields' => array('Group.name', 'Group.id')
				));
		
				$g_id = array();
		
				foreach ($groups as $g) {
					$g_id[] = $g['Group']['id'];
				}
		
				$this->loadModel('Balance');
				$balances = $this->Balance->find('all', array(
					'conditions' => array('Balance.group_id' => $g_id),
					'fields' => array('Balance.group_id','Balance.balance','Balance.pending','Group.name')
				));

				$requests['Pendientes'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id, 'Request.current_state_id' => array(1,2)),
				));

				$requests['Aprobadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id, 'Request.current_state_id' => array(3)),
				));

				$requests['Rechazadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id, 'Request.current_state_id' => array(4,5)),
				));

				$this->set(compact('balances'));
				
				}
				break;
			case 'JWT':{
				$groups = $this->GroupMember->find('all', array(
					'conditions' => array('Group.group_type_id' => 2),
					'fields' => array('Group.name', 'Group.id')
				));
				
				$g_id = array();
		
				foreach ($groups as $g) {
					$g_id[] = $g['Group']['id'];
				}
				
				$requests['Pendientes'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(1)),
				));

				$requests['Aprobadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(2)),
				));

				$requests['Rechazadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(4)),
				));
				$requests['PendientesJWT'] = $this->Request->find('count', array(
					'conditions' => array('Request.current_state_id' => array(6,11,7), 'Request.user_id' => $this->Session->read('id')),
				));

				$requests['AprobadasJWT'] = $this->Request->find('count', array(
					'conditions' => array('Request.current_state_id' => array(8), 'Request.user_id' => $this->Session->read('id')),
				));

				$requests['RechazadasJWT'] = $this->Request->find('count', array(
					'conditions' => array('Request.current_state_id' => array(9,10), 'Request.user_id' => $this->Session->read('id')),
				));
			}
			break;
			case 'Ford':{

				$groups = $this->GroupMember->find('all', array(
					'conditions' => array('Group.group_type_id' => 2),
					'fields' => array('Group.name', 'Group.id')
				));
				
				$g_id = array();
		
				foreach ($groups as $g) {
					$g_id[] = $g['Group']['id'];
				}
				
				$requests['Pendientes'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(2)),
				));

				$requests['Aprobadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(3)),
				));

				$requests['Rechazadas'] = $this->Request->find('count', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(5)),
				));

				$aprobadas = $this->Request->find('all', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(3)),
					'group'=>array('Group.name'),
					'fields' => array('SUM(Request.amount) as suma','Group.name'),
				));

				$total = $this->Request->find('all', array(
					'conditions' => array('Request.group_id' => $g_id,'Request.current_state_id' => array(3)),
					'fields' => array('SUM(Request.amount) as total'),
				));

				$total = number_format((float)($total[0][0]['total']), 0,',','.');

				$pendientes = $this->Request->find('all', array(
					'conditions' => array('Request.group_id' => $g_id, 'Request.current_state_id' => array(1)),
					'fields' => array('Request.id','Group.name', 'Request.created'),
				));

				$this->loadModel('Balance');
				$nacional = $this->Balance->find('all', array(
					'conditions'=> array('Balance.group_id' => $g_id),
					'fields' => array('SUM(Balance.nacional) as nacional')
				));
				$nacional = number_format((float)($nacional[0][0]['nacional']), 0,',','.');

				$this->set(compact('total'));
				$this->set(compact('nacional'));
				$this->set(compact('aprobadas'));
				$this->set(compact('pendientes'));
				
				}
				break;
		}

		$this->loadModel('GroupMember');
		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$this->set(compact('group_types','requests'));
	}

	public function error(){
		
	}

	public function monto_medios(){
		$this->layout = null;

		$this->loadModel('Request');
		$this->loadModel('RequestEvent');
		$this->loadModel('Event');
		$this->loadModel('Media');

		$query = $this->RequestEvent->find('all', array(
			'conditions' => array('Request.current_state_id' => 3, 
									'YEAR(Request.created)' => $this->request->data['year'], 
									'MONTH(Request.created)' => $this->request->data['month'], 
									),
			'contain' => array('Request'),
			'fields' => array('RequestEvent.id')
			));

		$requests = Hash::extract($query,'{n}.RequestEvent.id');

		$query = $this->Event->find('all', array(
			'conditions' => array('Media.show_flag' => 1, 'Event.request_event_id' => $requests
									),
			'contain' => array('Event', 'Request'),
			'group' => 'Event.media_id',
			'fields' => array('Media.name','SUM(Event.amount) as total')
			));

		$this->set(compact('query'));
	}
}
