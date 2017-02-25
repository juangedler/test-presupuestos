<?php

App::uses('State', 'Model');
App::uses('Media', 'Model');

class ConcesionarioController extends WebAppController {
	
	public $uses = array("Media", "RequestSupport");

	public function index(){
		$this->redirect('/web');
	}

	public function actividad($in){
		$this->layout = null;
		$medias = $this->Media->find('all', array(
			'conditions'=> array('Media.show_flag' => 1),
			'fields' => array('Media.id','Media.name'),
			'recursive'=> -1
		));
		
		$this->set(compact('medias'));
		//$this->autoRender = false;
		$this->set(compact('in'));
	}

	public function solicitud(){
		$this->loadModel('Balance');
		$this->loadModel('GroupMember');

		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('User.username' => $this->Session->read('current_user'), 'Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id','Group.city')
			));

		foreach($groups as &$g){
			$balance = $this->Balance->find('all', array(
				'conditions' => array('Group.id' => $g['Group']['id']),
				));
			$bal = (float)($balance[0]['Balance']['balance'] - $balance[0]['Balance']['pending']);
			$mer = (float)($balance[0]['Balance']['merchandising'] - $balance[0]['Balance']['mpending']);
			$g['Balance'] = number_format($bal, 0,',','.');
			$g['Merchandising'] = number_format($mer, 0,',','.');
			if($mer > $bal) $g['Merchandising'] = $g['Balance'];
		}
		
		$this->set(compact('groups'));
		
	}

	public function crear(){
		$this->layout = null;
		$this->autoRender = false;

		$activities = json_decode($this->request->data['actividades']);

		$this->loadModel('Group');
		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestEvent');
		$this->loadModel('Event');
		$this->loadModel('Date');
		$this->loadModel('Balance');
		$this->loadModel('Merchandising');

		$this->User->recursive = -1;
		$user = $this->User->find('first', array(
			'conditions' => array('User.username' => $this->Session->read('current_user')),
			'fields' => array('User.id')
		));

		$dataRequest['Request']['request_type_id'] = 1;
		$dataRequest['Request']['user_id'] = $user['User']['id'];
		$dataRequest['Request']['group_id'] = $this->request->data['grupo'];
		$dataRequest['Request']['process_id'] = 1;
		$dataRequest['Request']['title'] = $this->request->data['nombre'];
		$dataRequest['Request']['amount'] = $this->request->data['total'];
		$dataRequest['Request']['mamount'] = $this->request->data['mtotal'];
		$dataRequest['Request']['date'] = date_format(DateTime::createFromFormat('d/m/Y', $this->request->data['fecha']),'Y-m-d H:i:s');
		$dataRequest['Request']['current_state_id'] = 1;
		$dataRequest['Request']['created'] = date('Y-m-d H:i:s');
		$dataRequest['Request']['updated'] = date('Y-m-d H:i:s');

		$balance = $this->Balance->findBygroup_id($this->request->data['grupo']);

		$this->Balance->id = $balance['Balance']['id'];

		$dataRequestEvent['RequestEvent']['city'] = $this->request->data['ciudad'];
		$dataRequestEvent['RequestEvent']['found'] = $this->request->data['fondo'];
		$dataRequestEvent['RequestEvent']['objective'] = $this->request->data['objetivo'];

		$datasource = $this->Request->getDataSource();
		try{
		    $datasource->begin();
		    if(!$this->Request->save($dataRequest))
		        throw new Exception();

			$dataRequestEvent['RequestEvent']['request_id'] = $this->Request->id;

		    if(!$this->RequestEvent->save($dataRequestEvent))
		        throw new Exception();

		    if(!$this->Balance->saveField('pending', $balance['Balance']['pending'] + $this->request->data['total']))
		        throw new Exception();

		    if(($balance['Balance']['pending'] + $this->request->data['total']) > $balance['Balance']['balance'])
		        throw new Exception();

		    if(!$this->Balance->saveField('mpending', $balance['Balance']['mpending'] + $this->request->data['mtotal']))
		        throw new Exception();

		    if(($balance['Balance']['mpending'] + $this->request->data['mtotal']) > $balance['Balance']['merchandising'])
		        throw new Exception();

    		foreach($activities as &$a) {
				$dataEvent['Event']['request_event_id'] = $this->RequestEvent->id;
				$dataEvent['Event']['line'] = $a->vehiculo;
				$dataEvent['Event']['activity'] = $a->actividad;
				$dataEvent['Event']['media_id'] = $a->medio;
				$dataEvent['Event']['media'] = '';
				$dataEvent['Event']['amount'] = $a->monto;
				$dataEvent['Event']['description'] = $a->observaciones;

				$this->Event->create();
				if(!$this->Event->save($dataEvent))
		        	throw new Exception();

				foreach ($a->fechasI as $fi) {
					$dataFechaI['Date']['event_id'] = $this->Event->id;
					$dataFechaI['Date']['start'] = date_format(DateTime::createFromFormat('d/m/Y', $fi),'Y-m-d H:i:s');
					$dataFechaI['Date']['end'] = date_format(DateTime::createFromFormat('d/m/Y', $fi),'Y-m-d H:i:s');

					$this->Date->create();
					if(!$this->Date->save($dataFechaI))
			        	throw new Exception();
				}
				foreach ($a->fechasR as $fr) {
					$dataFechaR['Date']['event_id'] = $this->Event->id;
					$dataFechaR['Date']['start'] = date_format(DateTime::createFromFormat('d/m/Y', $fr[0]),'Y-m-d H:i:s');
					$dataFechaR['Date']['end'] = date_format(DateTime::createFromFormat('d/m/Y', $fr[1]),'Y-m-d H:i:s');

					$this->Date->create();
					if(!$this->Date->save($dataFechaR))
			        	throw new Exception();
				}
				foreach ($a->merchandising as $m) {
					$merchandising['Merchandising']['event_id'] = $this->Event->id;
					$merchandising['Merchandising']['name'] = $m[0];
					$merchandising['Merchandising']['price'] = $m[1];
					$merchandising['Merchandising']['quantity'] = $m[2];

					$this->Merchandising->create();
					if(!$this->Merchandising->save($merchandising))
			        	throw new Exception();
				}
			}

		    $datasource->commit();

		    $req['request'] = $this->Request->id;
		    $req['grupoName'] = $this->request->data['grupoName'];

		    $this->send_email(4,$req,1);

		} catch(Exception $e) {
		    $datasource->rollback();
		    var_dump($e);
		}
	}

	public function ver($in){
		$this->loadModel('GroupMember');
		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('User.username' => $this->Session->read('current_user'), 'Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id','Group.city')
			));

		$this->loadModel('Balance');

		foreach($groups as &$g){
			$balance = $this->Balance->find('all', array(
				'conditions' => array('Group.id' => $g['Group']['id']),
				));
			$g['Balance'] = number_format((float)($balance[0]['Balance']['balance'] - $balance[0]['Balance']['pending']), 0,',','.');
			$g['Pending'] = number_format($balance[0]['Balance']['pending'],0,',','.');
		}

		$option = $in;

		$this->set(compact('groups'));
		$this->set(compact('option'));
	}

	public function solicitudes($in){
		$this->layout = null;
		$this->autoRender = false;

		if($in == 1) $option = array(1,2);
		else if($in == 4 || $in == 5) $option = array(4,5);
		else $option = $in;

		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestEvent');
		$this->loadModel('RequestNote');

		$requests = $this->Request->find('all', array(
			'conditions' => array('Request.group_id' => $this->request->data['grupo'], 'Request.current_state_id' => $option),
			'order' => array('Request.id' => 'DESC'),
			'fields' => array('Request.id', 'Request.created', 'Request.title', 'Request.amount', 'Request.current_state_id', 'User.first_name', 'User.last_name'),
			));

		$requestsId = array();
		$requestsUser = array();

		foreach($requests as $r) {
			$requestsId[] = $r['Request']['id'];
			$requestsUser[] = $r['User']['first_name'].' '.$r['User']['last_name'];
		}

		$this->RequestEvent->recursive = -1;
		$requestEvents = $this->RequestEvent->find('all', array(
			'conditions' => array('RequestEvent.request_id' => $requestsId),
			'order' => array('RequestEvent.request_id' => 'DESC'),
			));

		$this->RequestNote->recursive = -1;
		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $requestsId),
			));

		$notes = array();

		foreach ($requestNote as $rn) {
			$notes[$rn['RequestNote']['request_id']][] = $rn['RequestNote']['note'];
		}

		$i=0;
		foreach ($requests as $r){
			echo '
			<div class="panel panel-default">
	            <div class="panel-heading" role="tab" id="headingThree">
	                <h4 class="panel-title">
	                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$r['Request']['id'].'" aria-expanded="false" aria-controls="collapseFive">
	                        Solicitud '.str_pad($r['Request']['id'], 8, "0", STR_PAD_LEFT).' - '.date('d/m/Y',strtotime($r['Request']['created'])).' - 
	                        '.$r['Request']['title'].' - $<span class="money">'.$r['Request']['amount'].'</span>';
	                    if($r['Request']['current_state_id'] == 2) echo '<i class="request_id pe-7s-timer pull-right" style="color:blue;font-size: 20px;margin-top: -4px;" title="Esperando Aprobación de Ford"/>';
	                    echo'
	                    </a>
	                </h4>
	            </div>
	            <div id="collapse'.$r['Request']['id'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
	                <div class="panel-body">
	                	<div class="col-sm-12">
	                		<u>Creador</u>: '.$requestsUser[$i].'<br>
	                		<u>Detalle</u>: '.$requestEvents[$i]['RequestEvent']['objective'].'
	                	</div>';
	                	if($in == 1){
	                		if($r['Request']['current_state_id'] == 2 && isset($notes[$r['Request']['id']][0])) echo '<div class="col-sm-12"><u>Observaciones JWT</u>: '.$notes[$r['Request']['id']][0].'</div>';
		                	echo '	
			                	<div class="col-sm-12"><br></div>
			                	<div class="col-sm-12">';

			                if($r['Request']['current_state_id'] == 1)
			                echo		
			                	'<div class="col-sm-6">
		                			<a id="modificar" type="button" class="btn btn-primary btn-sm pull-right" href="'.Router::url(array('controller'=>'concesionario', 'action'=>'modificar')).'/'.$r['Request']['id'].'">Modificar</a>
		                		</div>
		                		<div class="col-sm-6">
		                			<a id="consultar" type="button" class="btn btn-primary btn-sm pull-left" value="'.$r['Request']['id'].'" data-toggle="modal" data-target="#myModal">Consultar</a>
		                		</div>';

		                	else echo '
		                		<div class="col-sm-12" align="center">
		                			<a id="consultar" type="button" class="btn btn-primary btn-sm" value="'.$r['Request']['id'].'" data-toggle="modal" data-target="#myModal">Consultar</a>
		                		</div>';
	                	}
	                	else{
	                		switch ($r['Request']['current_state_id']) {
	                			case 3:
	                				if(isset($notes[$r['Request']['id']][0])) echo '<div class="col-sm-12"><u>Observaciones JWT</u>: '.$notes[$r['Request']['id']][0].'</div>';
	                				if(isset($notes[$r['Request']['id']][1])) echo '<div class="col-sm-12"><u>Observaciones Ford</u>: '.$notes[$r['Request']['id']][1].'</div>';
	                				break;
	                			case 4:
	                				if(isset($notes[$r['Request']['id']][0])) echo '<div class="col-sm-12"><u>Observaciones JWT</u>: '.$notes[$r['Request']['id']][0].'</div>';
	                				break;
	                			case 5:
			                		if(isset($notes[$r['Request']['id']][0])) echo '<div class="col-sm-12"><u>Observaciones JWT</u>: '.$notes[$r['Request']['id']][0].'</div>';
			                		if(isset($notes[$r['Request']['id']][1])) echo '<div class="col-sm-12"><u>Observaciones Ford</u>: '.$notes[$r['Request']['id']][1].'</div>';
	                				break;
	                		}
		                	echo '	
								<div class="col-sm-12"><br></div>
			                	<div class="col-sm-12">
		                		<div class="col-sm-6">
		                			<a id="descargar" href="../ver_pdf/'.$r['Request']['id'].'" type="button" class="btn btn-primary btn-sm pull-right" value="'.$r['Request']['id'].'" target="blank">Descargar</a>
		                		</div>
		                		<div class="col-sm-6">
		                			<a id="consultar" type="button" class="btn btn-primary btn-sm pull-left" value="'.$r['Request']['id'].'" data-toggle="modal" data-target="#myModal">Consultar</a>
		                		</div>';
	                	}
	        			echo '
	                	</div>
	                </div>
	            </div>
	        </div>';
		    $i++;
		}

		if(count($requests) == 0) 
			switch ($in) {
				case 1:
					echo 'No posee solicitudes pendientes.';
					break;
				case 3:
					echo 'No posee solicitudes aprobadas.';
					break;
				case 4:
					echo 'No posee solicitudes rechazadas.';
					break;
			}
	}

	public function consultar($in){
		$this->layout = null;

		$this->loadModel('RequestEvent');
		$this->loadModel('Date');
		$this->loadModel('User');
		$this->loadModel('RequestNote');
		$this->loadModel('Merchandising');
		
		//Seccion medios-----------------------------------------
		$medias = $this->Media->find('all', array(
			'fields' => array('Media.id','Media.name'),
			'recursive'=> -1
		));
		
		$mediasArray=array();
		
		foreach ($medias as $keyMedia => $media) {
			$mediasArray[$media['Media']['id']]=$media['Media']['name'];
		}
		
		$this->set(compact('mediasArray'));
		//-------------------------------------------------------
		
		$requestEvents = $this->RequestEvent->find('first', array(
			'conditions' => array('RequestEvent.request_id' => $in),
		));

		$this->User->recursive = -1;
		$requestEvents['User'] = $this->User->find('first', array(
			'conditions' => array('User.id' => $requestEvents['Request']['user_id']),
			'fields' => array('User.first_name', 'User.last_name'),
		));
		
		//
		
		$supports=$this->RequestSupport->find('all', array(
			'conditions' => array(
				'RequestSupport.request_id' => $requestEvents['Request']['id'],
				'RequestSupport.active_flag'=>1
			),
			'fields' => array('RequestSupport.id', 'RequestSupport.file'),
		));
		
		//$this->printWithFormat($requestEvents,true);

		foreach($requestEvents['Event'] as $e) $event_ids[] = $e['id'];

		$this->Date->recursive = -1;
		$eventsDates = $this->Date->find('all', array(
			'conditions' => array('Date.event_id' => $event_ids),
		));

		$this->Merchandising->recursive = -1;
		$merchandising = $this->Merchandising->find('all', array(
			'conditions' => array('Merchandising.event_id' => $event_ids),
		));

		foreach($requestEvents['Event'] as &$e){
			foreach($eventsDates as $ed){
				if($ed['Date']['event_id'] == $e['id']) {
					$aux['start']= $ed['Date']['start'];
					$aux['end']= $ed['Date']['end'];
					$e['Date'][] = $aux;
				}
			}
			foreach($merchandising as $m){
				if($m['Merchandising']['event_id'] == $e['id']) {
					$aux['name']= $m['Merchandising']['name'];
					$aux['price']= $m['Merchandising']['price'];
					$aux['quantity']= $m['Merchandising']['quantity'];
					$e['Merchandising'][] = $aux;
				}
			}
		}

		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 1),
			'order' => array('RequestNote.created ASC'),
			'fields' => array('RequestNote.note','RequestNote.created','User.first_name','User.last_name'),
			));

		$requestEvents['Note'] = $requestNote;


		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 3),
			'order' => array('RequestNote.created ASC'),
			'fields' => array('RequestNote.note','RequestNote.created','User.first_name','User.last_name'),
			));

		$requestEvents['MotivoAnulacion'] = $requestNote;

		$is_supportable = $this->Request->is_supportable($in);

		$this->set(compact('requestEvents'));
		$this->set(compact('supports'));
		$this->set(compact('is_supportable'));
	}

	public function en_revision(){}

	public function modificar($in){
		$this->loadModel('RequestEvent');
		$this->loadModel('Date');
		$this->loadModel('Request');
		$this->loadModel('Balance');
		$this->loadModel('Merchandising');
		
		//Seccion medios-----------------------------------------
		$medias = $this->Media->find('all', array(
			'conditions'=> array('Media.show_flag' => '1'),
			'fields' => array('Media.id','Media.name'),
			'recursive'=> -1
		));
		
		$this->set(compact('medias'));
		//------------------------------------------------------
		
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
		));

		if($request['Request']['blocked'] != 0 && $request['Request']['blocked'] != $this->Session->read('id')){
			$this->redirect('en_revision');
		}

		$this->Balance->recursive = -1;
		$balance = $this->Balance->find('first', array(
			'conditions' => array('Balance.group_id' => $request['Group']['id']),
			'fields' => array('Balance.balance','Balance.pending','Balance.merchandising','Balance.mpending'),
			));

		$requestEvents = $this->RequestEvent->find('first', array(
			'conditions' => array('RequestEvent.request_id' => $in),
		));

		$event_ids = array();

		foreach($requestEvents['Event'] as $e) $event_ids[] = $e['id'];

		$this->Date->recursive = -1;
		$eventsDates = $this->Date->find('all', array(
			'conditions' => array('Date.event_id' => $event_ids),
			));

		$this->Merchandising->recursive = -1;
		$eventsMerchandising = $this->Merchandising->find('all', array(
			'conditions' => array('Merchandising.event_id' => $event_ids),
			));

		$this->set(compact('request','requestEvents','eventsDates','balance','eventsMerchandising'));
		$this->block_request($in);
	}

	public function block_request($in){
		$this->loadModel('Request');
		$this->Request->id = $in;

		$this->Request->saveField('blocked', $this->Session->read('id'));
	}

	public function unblock_request($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->Request->id = $in;

		$this->Request->saveField('blocked', 0);
	}

	public function actualizar(){
		$this->layout = null;
		$this->autoRender = false;
		$activities = json_decode($this->request->data['actividades']);

		$this->loadModel('Request');
		$this->loadModel('RequestEvent');
		$this->loadModel('Event');
		$this->loadModel('Date');
		$this->loadModel('Balance');
		$this->loadModel('Merchandising');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $this->request->data['request']),
			));

		$pendiente_anterior = $request['Request']['amount'];
		$mpendiente_anterior = $request['Request']['mamount'];
		$this->Request->id = $request['Request']['id'];

		$requestEvents = $this->RequestEvent->find('first', array(
			'conditions' => array('RequestEvent.request_id' => $this->request->data['request']),
			));
		$this->RequestEvent->id = $requestEvents['RequestEvent']['id'];

		$event_ids = array();

		foreach($requestEvents['Event'] as $e) $event_ids[] = $e['id'];

		$balance = $this->Balance->findBygroup_id($request['Request']['group_id']);
		$this->Balance->id = $balance['Balance']['id'];

		$datasource = $this->Request->getDataSource();
		try{
		    $datasource->begin();

			if(!$this->RequestEvent->saveField('objective', $this->request->data['objetivo']))
			        throw new Exception();

			if(!$this->Balance->saveField('pending', $balance['Balance']['pending']+$this->request->data['total']-$pendiente_anterior))
		        throw new Exception();

		    if(!$this->Balance->saveField('mpending', $balance['Balance']['mpending']+$this->request->data['mtotal']-$mpendiente_anterior))
		        throw new Exception();

			if(!$this->Request->saveField('title', $this->request->data['nombre']))
			        throw new Exception();

			if(!$this->Request->saveField('amount', $this->request->data['total']))
			        throw new Exception();
			
			if(!$this->Request->saveField('mamount', $this->request->data['mtotal']))
			        throw new Exception();

			if(!$this->Request->saveField('date', date_format(DateTime::createFromFormat('d/m/Y', $this->request->data['fecha']),'Y-m-d H:i:s')))
			        throw new Exception();

			if(!$this->Request->saveField('updated', date('Y-m-d H:i:s')))
			        throw new Exception();

			if(!$this->Date->deleteAll(array('Date.event_id' => $event_ids)))
				throw new Exception();

			if(!$this->Merchandising->deleteAll(array('Merchandising.event_id' => $event_ids)))
				throw new Exception();

			if(!$this->Event->deleteAll(array('Event.request_event_id' => $requestEvents['RequestEvent']['id'])))
				throw new Exception();

			foreach($activities as &$a) {
				$dataEvent['Event']['request_event_id'] = $this->RequestEvent->id;
				$dataEvent['Event']['line'] = $a->vehiculo;
				$dataEvent['Event']['activity'] = $a->actividad;
				$dataEvent['Event']['media_id'] = $a->medio;
				$dataEvent['Event']['amount'] = $a->monto;
				$dataEvent['Event']['description'] = $a->observaciones;

				$this->Event->create();
				if(!$this->Event->save($dataEvent))
		        	throw new Exception();

				foreach ($a->fechasI as $fi) {
					$dataFechaI['Date']['event_id'] = $this->Event->id;
					$dataFechaI['Date']['start'] = date_format(DateTime::createFromFormat('d/m/Y', $fi),'Y-m-d H:i:s');
					$dataFechaI['Date']['end'] = date_format(DateTime::createFromFormat('d/m/Y', $fi),'Y-m-d H:i:s');

					$this->Date->create();
					if(!$this->Date->save($dataFechaI))
			        	throw new Exception();
				}
				foreach ($a->fechasR as $fr) {
					$dataFechaR['Date']['event_id'] = $this->Event->id;
					$dataFechaR['Date']['start'] = date_format(DateTime::createFromFormat('d/m/Y', $fr[0]),'Y-m-d H:i:s');
					$dataFechaR['Date']['end'] = date_format(DateTime::createFromFormat('d/m/Y', $fr[1]),'Y-m-d H:i:s');

					$this->Date->create();
					if(!$this->Date->save($dataFechaR))
			        	throw new Exception();
				}
				foreach ($a->merchandising as $m) {
					$merchandising['Merchandising']['event_id'] = $this->Event->id;
					$merchandising['Merchandising']['name'] = $m[0];
					$merchandising['Merchandising']['price'] = $m[1];
					$merchandising['Merchandising']['quantity'] = $m[2];

					$this->Merchandising->create();
					if(!$this->Merchandising->save($merchandising))
			        	throw new Exception();
				}
			}
			$datasource->commit();

		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function ver_pdf($in){
		$this->layout = null;
		$this->autoRender = false;

		require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'ReporteFord.php');

		$this->loadModel('RequestEvent');
		$this->loadModel('Date');
		$this->loadModel('Merchandising');
		$this->loadModel('Group');
		$this->loadModel('Balance');
		$this->loadModel('RequestNote');

		$requestEvents = $this->RequestEvent->find('first', array(
			'conditions' => array('RequestEvent.request_id' => $in),
			));

		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 1),
			'order' => array('RequestNote.created ASC'),
			'fields' => array('User.first_name', 'User.last_name','User.user_type_id','User.signature','RequestNote.note'),
			));

		$requestEvents['Request']['approvers'] = $requestNote;

		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 3),
			'order' => array('RequestNote.created ASC'),
			'fields' => array('User.first_name', 'User.last_name','User.user_type_id','User.signature','RequestNote.note'),
			));

		$requestEvents['Request']['nullers'] = $requestNote;

		$group = $this->Group->find('first', array(
			'conditions' => array('Group.id' => $requestEvents['Request']['group_id']),
			));

		$balance = $this->Balance->find('first', array(
			'conditions' => array('Balance.group_id' => $requestEvents['Request']['group_id']),
			));

		$requestEvents['Request']['group_name'] = $group['Group']['name'];
		$requestEvents['Request']['balance'] = number_format((float)($balance['Balance']['balance'] - $balance['Balance']['pending'] + $requestEvents['Request']['amount']), 0,',','.');
		if($requestEvents['Request']['current_state_id'] == 4 || $requestEvents['Request']['current_state_id'] == 5)
		$requestEvents['Request']['after'] = $requestEvents['Request']['balance'];
		else
		$requestEvents['Request']['after'] = number_format((float)($balance['Balance']['balance'] - $balance['Balance']['pending']), 0,',','.');

		foreach($requestEvents['Event'] as $e) $event_ids[] = $e['id'];

		$this->Date->recursive = -1;
		$eventsDates = $this->Date->find('all', array(
			'conditions' => array('Date.event_id' => $event_ids),
			));

		$this->Merchandising->recursive = -1;
		$merchandising = $this->Merchandising->find('all', array(
			'conditions' => array('Merchandising.event_id' => $event_ids),
			));
		
		//Seccion medios-----------------------------------------	
		$medias = $this->Media->find('all', array(
			'fields' => array('Media.id','Media.name'),
			'recursive'=> -1
		));
		
		$mediasArray=array();
		
		foreach ($medias as $keyMedia => $media) {
			$mediasArray[$media['Media']['id']]=$media['Media']['name'];
		}
		//--------------------------------------------------------
		foreach($requestEvents['Event'] as &$e){
			$e['media'] = $mediasArray[$e['media_id']];
			
			foreach($eventsDates as $ed){
				if($ed['Date']['event_id'] == $e['id']) {
					$aux['start']= $ed['Date']['start'];
					$aux['end']= $ed['Date']['end'];
					$e['Date'][] = $aux;
				}
			}
			foreach($merchandising as $m){
				if($m['Merchandising']['event_id'] == $e['id']) {
					$aux['name']= $m['Merchandising']['name'];
					$aux['price']= $m['Merchandising']['price'];
					$aux['quantity']= $m['Merchandising']['quantity'];
					$e['Merchandising'][] = $aux;
				}
			}
		}

		$this->response->type('pdf');

		$json = json_encode($requestEvents);

		$pdf = new Reporte();
		$pdf->draw($json);
		$pdf->Output();
	}

	public function cuenta(){
		$this->loadModel('GroupMember');
		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('User.username' => $this->Session->read('current_user'), 'Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id','Group.city')
			));

		$grupos;

		foreach ($groups as $g) {
			$grupos[] = $g['Group']['id'];
		}

		$this->loadModel('Group');
		$this->Group->recursive = -1;
		$groups = $this->Group->find('all', array(
			'conditions' => array('Group.id' => $grupos),
			'fields' => array('Group.id', 'Group.name'),
			));

		$this->loadModel('Balance');

		foreach($groups as &$g){
			$balance = $this->Balance->find('all', array(
				'conditions' => array('Group.id' => $g['Group']['id']),
				));
			$g['Balance'] = number_format((float)($balance[0]['Balance']['balance'] - $balance[0]['Balance']['pending']), 0,',','.');
			$g['Pending'] = number_format((float)$balance[0]['Balance']['pending'],0,',','.');
		}

		$this->set(compact('groups'));
	}

	public function loadGroup($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Group');
		$groups = $this->Group->findAllByid($in);

		$this->loadModel('Movements');
		$movements = $this->Movements->find('all',array(
			'conditions' => array('group_id' => $in),
			'fields'=>array('created','abono_mes','YEAR(created) as year','MONTH(created) as month','amount','balance_before','type'),
			'order' => array('created' => 'DESC')
			)
		);

		foreach ($movements as &$mov) {
			if(!isset($mov['Movements']['abono_mes']) && $mov['Movements']['abono_mes'] == NULL) 
				$mov['Movements']['abono_mes'] = $mov[0]['month'];
			$mov['Movements']['created'] = date('d/m/Y',strtotime($mov['Movements']['created']));
		}

		if(count($groups) > 0) $json['Balance'] = number_format((float)($groups[0]['Balance'][0]['balance'] - $groups[0]['Balance'][0]['pending']), 0,',','.');
		else $json['Balance'] = '';

		$json['Movements'] = $movements;

		$jsonstring = json_encode($json);
 		echo $jsonstring;
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	public function saveSupport(){
		$this->autoRender=false;
		$success=-1;
		/*$this->printWithFormat($_FILES);
		$this->printWithFormat($_POST,true);*/
		$requestId= array_key_exists("request-id", $_POST) ? $_POST['request-id'] : NULL;
		
		if($requestId!=NULL && $_FILES['support']['name']!=""){
			$rand = mt_rand(1000000,1000000000);
			$fileName=$_FILES['support']['name'];
			$fileName = preg_replace("/[^a-z0-9\.]/", "", strtolower($fileName));
			$filePath = $rand . basename($fileName);
			
			$data = array(
	       		'RequestSupport' => array(
		         	'request_id' => $requestId,
		         	'file'=> $filePath,
		         	'user_id'=> $this->Session->read('id'),
		         	'active_flag'=> 1,
		         	'type'=> $_FILES['support']['type']
		        )
			);
		
			try{
				if(!$this->RequestSupport->save($data))
	        		throw new Exception();
				
				move_uploaded_file($_FILES['support']['tmp_name'], 'files/soportes/'.$filePath);
				$success=$requestId;
				$this->Session->setFlash(__('Factura o soporte enviado con éxito.'));
				
			}catch(Exception $e) {
				$this->Session->setFlash(__('Ha ocurrido un error. Por favor intente nuevamente.'));
			}
		}
		return $success;
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	public function getSupports(){
		$this->layout=false;
		$supports=array();
		$requestId= array_key_exists("request-id", $_POST) ? $_POST['request-id'] : NULL;
		
		if($requestId!=NULL){
			$supports=$this->RequestSupport->find('all', array(
				'conditions' => array(
					'RequestSupport.request_id' => $requestId,
					'RequestSupport.active_flag'=>1
				),
				'fields' => array('RequestSupport.id', 'RequestSupport.file'),
				'recursive' => -1
			));
		}
		$this->set(compact('supports'));

		$is_supportable = $this->Request->findById($requestId)['Request']['supportable'];
		$this->set(compact('is_supportable'));
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	public function deleteSupport(){
		$this->autoRender=false;
		$success=0;
		$supportId= array_key_exists("supportId", $_POST) ? $_POST['supportId'] : NULL;
		//$this->printWithFormat($_POST,true);
		if($supportId!=NULL){
			$this->RequestSupport->id = $supportId;
		    $this->RequestSupport->set(array('active_flag' => 0));
			try{
				if(!$this->RequestSupport->save())
	        		throw new Exception();
				$success=1;
				$this->Session->setFlash(__('La actura o soporte fue eliminada.'));
				
			}catch(Exception $e) {
				$this->Session->setFlash(__('Ha ocurrido un error. Por favor intente nuevamente.'));
			}
		}
		
		return $success;
	}

}