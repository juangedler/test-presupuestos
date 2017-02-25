<?php
App::uses('State', 'Model');
App::uses('Request', 'Model');

class AdministradorController extends WebAppController {
	
	public $uses = array("Media", "RequestSupport");

	public function index(){
		$this->redirect('/web');
	}

	public function presupuesto(){
		$this->loadModel('Group');
		$this->loadModel('Movements');
		$this->Group->recursive = -1;
		$groups = $this->Group->findAllBygroup_type_id(2, array('id','name'));

		$cero = 1;

		if(date('m') == 12){
			$movements = $this->Movements->find('all', array(
				'conditions' => array(	"Movements.type" => 'CERO', 
										"MONTH(Movements.created)" => 12, 
										"YEAR(Movements.created)" => date('Y')),
				'fields' => array('count(*) as count')
				));
			$cero = $movements[0][0]['count'];
		}
		$this->set(compact('groups','cero'));
	}

	public function ceroSaldos(){
		$this->loadModel("ActionType");
		$this->loadModel("Balance");
		$this->loadModel("Movements");
		$this->loadModel("Request");

		$cero = $this->Movements->find('all', array(
			'conditions' => array(	"Movements.type" => 'CERO', 
									"MONTH(Movements.created)" => 12, 
									"YEAR(Movements.created)" => date('Y')),
			'fields' => array('count(*) as count')
			)) ;
			
			$cero=$cero[0][0]['count'];
		
		if(date('m') == 12 && !$cero){

			$i = 0;

			$request = $this->Request->find('all', array(
				'conditions' => array('Group.group_type_id' => 2, 'Request.current_state_id' => array(1,2)),
				'fields' => array('Group.name', 'Group.group_type_id', 'Request.id', 'Request.group_id', 'Request.current_state_id', 'Request.amount')
				));

			$balances = $this->Balance->find('all', array(
				'conditions' => array('Group.group_type_id' => 2)
				));

			$group_balance;
			$group_balance_mas_pending;

			foreach ($balances as $b) {
				$group_balance[$b['Balance']['group_id']] = $b['Balance']['balance'];
				$group_balance_mas_pending[$b['Balance']['group_id']] = $b['Balance']['balance'] + $b['Balance']['pending'];
				$balances_mas_pendings[] = array('Balance' => array('id' => $b['Balance']['id'], 'balance' => 0, 'pending' => 0, 'nacional' => $b['Balance']['balance'] + $b['Balance']['pending'] + $b['Balance']['nacional']));
			}

			foreach ($request as $r) {
				$movements['Movements'][$i]['group_id'] = $r['Request']['group_id'];
				$movements['Movements'][$i]['type'] = 'RECHAZADA';
				$movements['Movements'][$i]['amount'] = $r['Request']['amount'];
				$movements['Movements'][$i]['balance_before'] = $group_balance[$r['Request']['group_id']];
				$movements['Movements'][$i]['nacional'] = 0;
				$group_balance[$r['Request']['group_id']] += $r['Request']['amount'];
				$i++;
			}

			foreach ($balances as $b) {
				$movements['Movements'][$i]['group_id'] = $b['Group']['id'];
				$movements['Movements'][$i]['type'] = 'CERO';
				$movements['Movements'][$i]['amount'] = $b['Balance']['balance'];
				$movements['Movements'][$i]['balance_before'] = $b['Balance']['balance'];
				$movements['Movements'][$i]['nacional'] = $b['Balance']['balance'];
				$i++;
			}

			$datasource = $this->Request->getDataSource();
			try{
			    $datasource->begin();

			    //Paso 1: Cerrando solicitudes abiertas	
				//TO DO: Enviar correos notificando los cierres de las solicitudes
				if(!$this->Request->updateAll(
				    array('Request.current_state_id' => 1),
				    array('Request.current_state_id' => array(1,2), 'Group.group_type_id' => 2)
				)) throw new Exception();

				//Paso 2: Crear movimientos para cuentas en cero
				if(!$this->Movements->saveAll($movements['Movements']))
					throw new Exception();

				//Paso 3: Actualizar los balances privados y nacionales
				if(!$this->Balance->saveMany($balances_mas_pendings))
					throw new Exception();

			    $datasource->commit();
			} catch(Exception $e) {
			    $datasource->rollback();
			}
		}

		$this->redirect('/web/administrador/presupuesto');
	}

	public function todos(){
		$this->loadModel('Group');
		$this->loadModel('Movements');
		$this->Group->recursive = -1;
		$this->Movements->recursive = -1;
		$groups = $this->Group->findAllBygroup_type_id(2, array('id','name'));

		foreach ($groups as &$g) {
			$g['Movements'] = $this->Movements->find('all', array(
				'conditions' => array('Movements.group_id' => $g['Group']['id'], 'Movements.type' => 'ABONO')
				));
		}

		$this->set(compact('groups'));
	}

	public function movimientos(){
		$this->loadModel('Group');
		$this->loadModel('Movements');
		$this->Group->recursive = -1;
		$this->Movements->recursive = -1;
		$groups = $this->Group->findAllBygroup_type_id(2, array('id','name'));

		foreach ($groups as &$g) {
			$g['Movements'] = $this->Movements->find('all', array(
				'conditions' => array('Movements.group_id' => $g['Group']['id'])
				));
		}

		$this->set(compact('groups'));
	}

	public function balances(){
		$this->loadModel('Group');
		$this->loadModel('Balance');
		$this->Group->recursive = -1;
		$this->Balance->recursive = -1;
		$groups = $this->Group->findAllBygroup_type_id(2, array('id','name'));

		foreach ($groups as &$g) {
			$g['Balances'] = $this->Balance->find('all', array(
				'conditions' => array('Balance.group_id' => $g['Group']['id'])
				));
		}

		$this->set(compact('groups'));
	}

	public function reporte($m){
		$this->loadModel('Request');
		$this->loadModel('RequestEvent');
		$request = $this->Request->find('all', array(
			'conditions' => array('MONTH(Request.date)' => $m),
			));

		foreach ($request as &$r) {
			$r['Request']['detail'] = $this->RequestEvent->find('first', array(
				'conditions' => array('RequestEvent.request_id' => $r['Request']['id']),
				'fields' => array('objective'),
				));
		}

		$this->set(compact('request'));
	}

	public function loadGroup($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Group');
		$groups = $this->Group->findAllByid($in);

		$this->loadModel('Movements');
		$movements = $this->Movements->find('all',array(
			'conditions' => array('group_id' => $in, 'type'=>'ABONO'),
			'fields'=>array('abono_mes','YEAR(created) as year','amount','balance_before'),
			'order' => array('created' => 'DESC')
			)
		);

		if(count($groups) > 0) $json['Balance'] = $groups[0]['Balance'][0]['balance']-$groups[0]['Balance'][0]['pending'];
		else $json['Balance'] = '';

		if(count($groups) > 0) $json['Merchandising'] = $groups[0]['Balance'][0]['merchandising']-$groups[0]['Balance'][0]['mpending'];
		else $json['Merchandising'] = '';

		$json['Movements'] = $movements;

		$jsonstring = json_encode($json);
 		echo $jsonstring;
	}

	public function asignarSaldo(){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Balance');
		$this->loadModel('Movements');
		$this->loadModel('GroupMember');
		$this->loadModel('Group');
		
		$asignacionExistente = $this->Movements->find('first', array(
			'conditions' => array(
				'Movements.group_id' => $this->request->data['group'],
				'Movements.type' => 'ABONO', 
				'Movements.abono_mes' => $this->request->data['mes'], 
				"YEAR(Movements.created)" => date('Y'))
		));


		echo 'Asignacion existente: '. count($asignacionExistente);
		if(count($asignacionExistente) != 0) exit;

		$datos['Movements']['group_id'] = $this->request->data['group'];
		$datos['Movements']['type'] = 'ABONO';
		$datos['Movements']['abono_mes'] = $this->request->data['mes'];
		$datos['Movements']['amount'] = $this->request->data['privado'];
		$datos['Movements']['balance_before'] = $this->request->data['disponible'];
		$datos['Movements']['nacional'] = $this->request->data['nacional'];
		$datos['Movements']['percentage'] = $this->request->data['percentage'];
		$datos['Movements']['created'] = date('Y-m-d H:i:s');

		$balance = $this->Balance->findBygroup_id($this->request->data['group']);

		$this->Balance->id = $balance['Balance']['id'];

		$datasource = $this->Movements->getDataSource();
		try{
		    $datasource->begin();
		    if(!$this->Movements->save($datos))
		        throw new Exception();

		    if(!$this->Balance->saveField('balance', $balance['Balance']['balance']+$this->request->data['privado']))
		        throw new Exception();

		    if(!$this->Balance->saveField('nacional', $balance['Balance']['nacional']+$this->request->data['nacional']))
		        throw new Exception();

		    if(!$this->Balance->saveField('merchandising', $balance['Balance']['merchandising']+($this->request->data['privado'] * $this->request->data['percentage'] / 100)))
		        throw new Exception();

		    $req['privado'] = $this->request->data['privado'];
		    $req['mes_nombre'] = $this->request->data['mes_nombre'];

		    $this->send_email($this->request->data['group'],$req,4);

		    $datasource->commit();
		} catch(Exception $e) {
		    $datasource->rollback();
		}
	}

	public function ver($in){

		$this->loadModel('Request');

		$this->loadModel('GroupMember');
		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id')
		));
		
		//$this->printWithFormat($groups,true);

		$groupMid;
		$groupMname;

		foreach ($groups as $gm) {
			$groupMid[] = $gm['Group']['id'];
			$groupMname[$gm['Group']['id']] = $gm['Group']['name'];
		}

		$this->Request->recursive = -1;
		$requests = $this->Request->find('all', array(
			'conditions' => array('Request.group_id' => $groupMid, 'Request.current_state_id' => $in),
			'fields' => array('Request.id', 'Request.title', 'Request.group_id','Request.amount','Request.created'),
			'order' => array('Request.id' => 'DESC'),

			));

		foreach ($requests as &$r) {
			$r['Request']['group_name'] = $groupMname[$r['Request']['group_id']];
		}

		$option = $in;

		$this->set(compact('requests','option'));
	}

	public function consultar($in){
		$this->layout = null;

		$this->loadModel('RequestEvent');
		$this->loadModel('Date');
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
		
		$requestEvents['Request']['supports']=$this->RequestSupport->find('all', array(
			'conditions' => array(
				'RequestSupport.request_id' => $requestEvents['Request']['id'],
				'RequestSupport.active_flag'=>1
			),
			'fields' => array('RequestSupport.id', 'RequestSupport.file'),
		));

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

		$this->set(compact('requestEvents'));
		
	}

	public function aprobar($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');
		$this->loadModel('Balance');
		$this->loadModel('Movements');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));

		$this->Balance->recursive = -1;
		$balance = $this->Balance->find('first', array(
			'conditions' => array('Balance.group_id' => $request['Request']['group_id']),
			));
		$this->Balance->id = $balance['Balance']['id'];
		
		$this->Request->id = $in;

		$dataMovement['Movements']['group_id'] = $request['Request']['group_id'];
		$dataMovement['Movements']['request_id'] = $request['Request']['id'];
		$dataMovement['Movements']['type'] = 'APROBADA';
		$dataMovement['Movements']['amount'] = $request['Request']['amount'];
		$dataMovement['Movements']['mamount'] = $request['Request']['mamount'];
		$dataMovement['Movements']['balance_before'] = $balance['Balance']['balance'];
		$dataMovement['Movements']['created'] = date('Y-m-d H:i:s');

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', 3))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    if(!$this->Movements->save($dataMovement))
		        throw new Exception();

		    $mpend = $balance['Balance']['merchandising']-$request['Request']['mamount'];
		    $pend = $balance['Balance']['balance']-$request['Request']['amount'];

		    if(!$this->Balance->saveField('pending', $balance['Balance']['pending']-$request['Request']['amount']))
		        throw new Exception();

		    if(!$this->Balance->saveField('balance', $balance['Balance']['balance']-$request['Request']['amount']))
		        throw new Exception();

		    if(!$this->Balance->saveField('mpending', $balance['Balance']['mpending']-$request['Request']['mamount']))
		        throw new Exception();

		    if(!$this->Balance->saveField('merchandising', $balance['Balance']['merchandising']-$request['Request']['mamount']))
		        throw new Exception();

		    if($mpend > $pend){
		    	if(!$this->Balance->saveField('merchandising', $pend))
		        	throw new Exception();
		    }
		    	

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['grupoName'] = $this->request->data['grupoName'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(array($request['Request']['group_id'],4),$req,5);

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function rechazar($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('Balance');
		$this->loadModel('Movements');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));

		$this->Balance->recursive = -1;
		$balance = $this->Balance->find('first', array(
			'conditions' => array('Balance.group_id' => $request['Request']['group_id']),
			));
		$this->Balance->id = $balance['Balance']['id'];
		
		$this->Request->id = $in;

		$dataMovement['Movements']['group_id'] = $request['Request']['group_id'];
		$dataMovement['Movements']['request_id'] = $request['Request']['id'];
		$dataMovement['Movements']['type'] = 'RECHAZADA';
		$dataMovement['Movements']['amount'] = $request['Request']['amount'];
		$dataMovement['Movements']['mamount'] = $request['Request']['mamount'];
		$dataMovement['Movements']['balance_before'] = $balance['Balance']['balance'];
		$dataMovement['Movements']['created'] = date('Y-m-d H:i:s');

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Movements->save($dataMovement))
		        throw new Exception();

		    if(!$this->Balance->saveField('pending', $balance['Balance']['pending']-$request['Request']['amount']))
		        throw new Exception();

		    if(!$this->Balance->saveField('mpending', $balance['Balance']['mpending']-$request['Request']['mamount']))
		        throw new Exception();

		    if(!$this->Request->saveField('current_state_id', 5))
		        throw new Exception();

		    if(!$this->RequestNote->save($note))
		        throw new Exception();

			$req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['grupoName'] = $this->request->data['grupoName'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(array($request['Request']['group_id'],4),$req,6);

			$datasource->commit();

		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function anular($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');
		$this->loadModel('Balance');
		$this->loadModel('Movements');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));

		$this->Balance->recursive = -1;
		$balance = $this->Balance->find('first', array(
			'conditions' => array('Balance.group_id' => $request['Request']['group_id']),
			));

		$this->Balance->id = $balance['Balance']['id'];
		$this->Request->id = $in;

		$dataMovement['Movements']['group_id'] = $request['Request']['group_id'];
		$dataMovement['Movements']['request_id'] = $request['Request']['id'];
		$dataMovement['Movements']['type'] = 'ANULADA';
		$dataMovement['Movements']['amount'] = $request['Request']['amount'];
		$dataMovement['Movements']['mamount'] = $request['Request']['mamount'];
		$dataMovement['Movements']['balance_before'] = $balance['Balance']['balance'];
		$dataMovement['Movements']['created'] = date('Y-m-d H:i:s');

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestNote']['type'] = 3;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Movements->save($dataMovement))
		        throw new Exception();

		    if(!$this->Balance->saveField('balance', $balance['Balance']['balance']+$request['Request']['amount']))
		        throw new Exception();

		    if(!$this->Balance->saveField('merchandising', $balance['Balance']['merchandising']+$request['Request']['mamount']))
		        throw new Exception();

		    if(!$this->Request->saveField('current_state_id', 999))
		        throw new Exception();

		    if(!$this->RequestNote->save($note))
		        throw new Exception();

			$req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['grupoName'] = $this->request->data['grupoName'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(array($request['Request']['group_id'],4),$req,999);

			$datasource->commit();

		} catch(Exception $e) {
			var_dump($e);
			$datasource->rollback();
		}
	}

	public function ver_pdf($in){
		$this->layout = null;
		$this->autoRender = false;

		require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'ReporteFord.php');

		$this->loadModel('RequestEvent');
		$this->loadModel('Date');
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
		$requestEvents['Request']['after'] = number_format((float)($balance['Balance']['balance'] - $balance['Balance']['pending']), 0,',','.');

		foreach($requestEvents['Event'] as $e) $event_ids[] = $e['id'];

		$this->Date->recursive = -1;
		$eventsDates = $this->Date->find('all', array(
			'conditions' => array('Date.event_id' => $event_ids),
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
		//-------------------------------------------------------
		foreach($requestEvents['Event'] as &$e){
			$e['media'] = $mediasArray[$e['media_id']];
			foreach($eventsDates as $ed){
				if($ed['Date']['event_id'] == $e['id']) {
					$aux['start']= $ed['Date']['start'];
					$aux['end']= $ed['Date']['end'];
					$e['Date'][] = $aux;
				}
			}
		}

		$this->response->type('pdf');

		$json = json_encode($requestEvents);

		$pdf = new Reporte();
		$pdf->draw($json);
		$pdf->Output();
	}


	public function listar($in){
		$this->loadModel('Request');
		$this->loadModel('GroupMember');

		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$option;

		if(in_array('4', $group_types)){
			if($in <= 5){
				$option = $in + 5;
				$type = 2;
			}
			else{
				$option = $in + 6;
				$type = 3;
			}
		}
		else if(in_array('6', $group_types)){
			switch ($in) {
				case 1:
					$option = 7;
					$type = 2;
					break;
				case 2:
					$option = 8;
					$type = 2;
					break;
				case 3:
					$option = 10;
					$type = 2;
					break;
				case 6:
					$option = 13;
					$type = 3;
					break;
				case 7:
					$option = 14;
					$type = 3;
					break;
				case 8:
					$option = 16;
					$type = 3;
					break;
				case 14:
					$option = 20;
					$type = 2;
					
			}
		}

		$this->Request->recursive = -1;
		$requests = $this->Request->find('all', array(
			'conditions' => array('Request.request_type_id' => $type, 'Request.current_state_id' => $option),
			'fields' => array('Request.id', 'Request.title', 'Request.group_id','Request.amount','Request.created'),
			'order' => array('Request.id' => 'DESC'),
			));

		$this->set(compact('requests','option','type'));
	}

	public function mostrar($in){
		$this->layout = null;

		$this->loadModel('Date');
		$this->loadModel('GroupMember');
		$this->loadModel('RequestFile');
		$this->loadModel('RequestNote');

		$requestFile = $this->RequestFile->find('all', array(
			'conditions' => array('RequestFile.request_id' => $in),
			'order' => array('RequestFile.created' => 'DESC'),
			));

		if($requestFile[0]['Request']['current_state_id'] == 11)
		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 2),
			'order' => array('RequestNote.created' => 'DESC'),
			'fields' => array('RequestNote.note','RequestNote.created','User.first_name','User.last_name'),
			));
		else
		$requestNote = $this->RequestNote->find('all', array(
			'conditions' => array('RequestNote.request_id' => $in, 'RequestNote.type' => 1),
			'fields' => array('RequestNote.note','RequestNote.created','User.first_name','User.last_name'),
			));

		$requestFile['Note'] = $requestNote;

		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$this->set(compact('requestFile','group_types'));
	}

	public function aprobar_presupuesto($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestNote');
		$this->loadModel('RequestFile');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state = 7;

		switch ($request['Request']['current_state_id']) {
			case 6:
				$next_state = 7;
				break;
			case 7:
				$next_state = 8;
				break;
		}

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

		$file_path = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $in),
				'order' => array('RequestFile.created' => 'DESC'),
				'fields' => array('RequestFile.file')
				));

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
			require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

			$pdf =& new FPDI();

			$pdf->SetAutoPageBreak(false);

			$count = $pdf->setSourceFile($file_path[0]['RequestFile']['file']);

			for($i = 1; $i <= $count; $i++){
			    $tplidx = $pdf->importPage($i);
				$specs = $pdf->getTemplateSize($tplidx);
				$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']));
			    $pdf->useTemplate($tplidx, null, null, 0, 0, true);
			}

			$pdf->SetFont('Courier','B',9);
			$pdf->SetTextColor(0,0,128);

			if($next_state == 7) {
				
				/*$pdf->Image($user_sign['User']['signature'],78,270,50,18);
				$pdf->SetXY(83, 290);*/
				$pdf->Image($user_sign['User']['signature'],78,$specs['h']-30,30,18);
               	$pdf->SetXY(78, $specs['h']-10);
				$pdf->Write(0, "APROBADO CLIENTE");
			}
		    else if($next_state == 8) {
		    	/*$pdf->Image($user_sign['User']['signature'],140,270,50,18);
				$pdf->SetXY(145, 290);*/
				$pdf->Image($user_sign['User']['signature'],140,$specs['h']-30,30,18);
               	$pdf->SetXY(140, $specs['h']-10);
				$pdf->Write(0, "APROBADO CLIENTE");
		    }

			$pdf->Output($file_path[0]['RequestFile']['file'],'F');

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    if($next_state == 7) $this->send_email(3,$req,8);
		    else if($next_state == 8) {
		    	$this->send_email(0,$req,9);
		    	$this->send_email(2,$req,9);
		    }

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function rechazar_presupuesto($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state;

		switch ($request['Request']['current_state_id']) {
			case 6:
				$next_state = 9;
				break;
			case 7:
				$next_state = 10;
				break;
		}

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    if($next_state == 9) $this->send_email(0,$req,10);
		    else if($next_state == 10) {
		    	$this->send_email(0,$req,11);
		    	$this->send_email(2,$req,11);
		    }

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function modificar_presupuesto($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state = 11;

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestNote']['type'] = 2;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(0,$req,12);

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function aprobar_pauta($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestNote');
		$this->loadModel('RequestFile');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state = 13;

		switch ($request['Request']['current_state_id']) {
			case 12:
				$next_state = 13;
				break;
			case 13:
				$next_state = 14;
				break;
		}

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

		$file_path = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $in, 'RequestFile.type' => 'Pauta'),
				'order' => array('RequestFile.created' => 'DESC'),
				'fields' => array('RequestFile.file')
				));

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

			switch ($next_state) {
				case 14:
					$fila = 'L'; break;
				
				default:
					$fila = 'O'; break;
			}

			set_time_limit(180);
			ini_set('memory_limit', '-1');
			date_default_timezone_set('America/Bogota');
	        require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'PHPExcel_1.8.0_doc' . DS . 'Classes' . DS . 'PHPExcel.php');

	        $fileType = 'Excel2007';
			$fileName = $file_path[0]['RequestFile']['file'];

			$objPHPExcelReader = PHPExcel_IOFactory::createReader($fileType);
			$objPHPExcel = $objPHPExcelReader->load($fileName);

			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setPath($user_sign['User']['signature']);
			$objDrawing->setCoordinates($fila.'4');
			$objDrawing->setResizeProportional(false);
			$objDrawing->setHeight(80);
			$objDrawing->setWidth(200);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

			$objSheet = $objPHPExcel->getActiveSheet();
			$objSheet->getStyle($fila.'6')->getFont()->getColor()->setRGB('000080');
			$objSheet->getStyle($fila.'6')->getFont()->setName('Courier')->setBold(true)->setSize(11);
			$objSheet->getCell($fila.'6')->setValue('APROBADO CLIENTE');

			$objPHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$fileType);

			$objPHPExcelWriter->save('files/pauta/temp.xlsx');
			unlink($fileName);
			rename('files/pauta/temp.xlsx',$fileName);

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    if($next_state == 13) 
		    	$this->send_email(3,$req,14);
		    else if($next_state == 14) {
		    	$this->send_email(0,$req,15);
		    	$this->send_email(2,$req,15);
		    }

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function rechazar_pauta($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state;

		switch ($request['Request']['current_state_id']) {
			case 12:
				$next_state = 15;
				break;
			case 13:
				$next_state = 16;
				break;
		}

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    if($next_state == 9) 
		    	$this->send_email(0,$req,16);
		    else if($next_state == 10) {
		    	$this->send_email(0,$req,17);
		    	$this->send_email(2,$req,17);
		    }

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function finalizar_pauta($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state = 20;

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestNote']['type'] = 3;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    /*
		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    if($next_state == 9) 
		    	$this->send_email(0,$req,16);
		    else if($next_state == 10) {
		    	$this->send_email(0,$req,17);
		    	$this->send_email(2,$req,17);
		    }
			*/

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function modificar_pauta($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));
		
		$this->Request->id = $in;
		$next_state = 17;

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestNote']['type'] = 2;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', $next_state))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(0,$req,18);

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}
	}

	public function listar_presupuestos($in){
		$this->loadModel('RequestFile');
		$this->loadModel('GroupMember');

		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$option;

		if(in_array('4', $group_types)){
			switch ($in) {
				case 1:
					$option = 5;  break;
				case 2:
					$option = 6;  break;
				case 3:
					$option = 7;  break;
				case 4:
					$option = 9;  break;
				case 5:
					$option = 10; break;
			}
		}
		else if(in_array('6', $group_types)){
			switch ($in) {
				case 1:
					$option = 6;  break;
				case 2:
					$option = 9;  break;
				case 3:
					$option = 10; break;
			}
		}

		$this->RequestFile->recursive = -1;
		$requests = $this->RequestFile->find('all', array(
			'conditions' => array('RequestFile.type' => 'PDF', 'RequestFile.status' => $option),
			'order' => array('RequestFile.id' => 'DESC'),
			));

		$this->set(compact('requests','option','in'));
	}

	public function mostrar_presupuesto_ms($in){
		$this->layout = null;

		$this->loadModel('GroupMember');
		$this->loadModel('RequestFile');
		$this->loadModel('RequestFileNote');

		$requestFile = $this->RequestFile->find('all', array(
			'conditions' => array('RequestFile.id' => $in),
			'order' => array('RequestFile.created' => 'DESC'),
			));

		if($requestFile[0]['RequestFile']['status'] == 8)
		$requestFileNote = $this->RequestFileNote->find('all', array(
			'conditions' => array('RequestFileNote.request_file_id' => $in, 'RequestFileNote.type' => 2),
			'order' => array('RequestFileNote.created' => 'DESC'),
			'fields' => array('RequestFileNote.note','RequestFileNote.created','User.first_name','User.last_name'),
			));
		else
		$requestFileNote = $this->RequestFileNote->find('all', array(
			'conditions' => array('RequestFileNote.request_file_id' => $in, 'RequestFileNote.type' => 1),
			'fields' => array('RequestFileNote.note','RequestFileNote.created','User.first_name','User.last_name'),
			));

		$requestFile['Note'] = $requestFileNote;

		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$this->set(compact('requestFile','group_types'));
	}

	public function aprobar_presupuesto_ms($role,$in){
		$this->loadModel('User');
		$this->loadModel('RequestFile');
		$this->loadModel('RequestFileNote');

		$this->RequestFile->recursive = -1;
		$request = $this->RequestFile->findByid($in);

		switch ($request['RequestFile']['status']) {
			case 5:
				$next_status = 6;
				break;
			case 6:
				$next_status = 9;
				break;
		}

		$file_path = $request['RequestFile']['file'];

		$user_sign = $this->User->find('first', array(
			'conditions' => array('User.id' => $this->Session->read('id')),
			'fields' => array('User.signature')
			));

		$note['RequestFileNote']['request_file_id'] = $in;
		$note['RequestFileNote']['user_id'] = $this->Session->read('id');
		$note['RequestFileNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['type'] = 1;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestFileNote']['note'] = $this->request->data['note'];
		else $note['RequestFileNote']['note'] = 'ninguna';

		$datasource = $this->RequestFile->getDataSource();
		try{
			$this->RequestFile->id = $in;
			if(!$this->RequestFile->saveField('status',$next_status))
	        	throw new Exception();

	       	if(!$this->RequestFileNote->save($note))
		        throw new Exception();

			require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
			require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

			$pdf =& new FPDI();

			$pdf->SetAutoPageBreak(false);

			$count = $pdf->setSourceFile($file_path);

			for($i = 1; $i <= $count; $i++){
			    $tplidx = $pdf->importPage($i);
					$specs = $pdf->getTemplateSize($tplidx);
					$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']));
				    $pdf->useTemplate($tplidx, null, null, 0, 0, true);
			}

			$pdf->SetFont('Courier','B',7);
			$pdf->SetTextColor(0,0,128);

			if($next_status == 6) {
				/*$pdf->Image($user_sign['User']['signature'],130,270,30,18);
				$pdf->SetXY(130, 290);*/
				$pdf->Image($user_sign['User']['signature'],130,$specs['h']-30,30,18);
               	$pdf->SetXY(130, $specs['h']-10);
				$pdf->Write(0, "APROBADO CLIENTE");
			}
		    else if($next_status == 9) {
		    	/*$pdf->Image($user_sign['User']['signature'],170,270,30,18);
				$pdf->SetXY(170, 290);*/
				$pdf->Image($user_sign['User']['signature'],170,$specs['h']-30,30,18);
               	$pdf->SetXY(170, $specs['h']-10);
				$pdf->Write(0, "APROBADO CLIENTE");
		    }

			$pdf->Output($file_path,'F');

	        $req['request'] = $in;
	        $req['title'] = $request['RequestFile']['title'];
	        $req['number'] = $request['RequestFile']['number'];

	        if($next_status == 6)
		    	$this->send_email(3,$req,25);
		    else if($next_status == 9){
		    	$this->send_email(2,$req,26);
		    	$this->send_email(-1,$req,26);
		    }

			$datasource->commit();

			$this->Session->setFlash(__('Solicitud enviada con éxito.'));
		} catch(Exception $e) {
			$datasource->rollback();
			$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
		}

		$this->redirect('listar_presupuestos/'.$role);
	}

	public function rechazar_presupuesto_ms($role,$in){
		$this->loadModel('RequestFile');
		$this->loadModel('RequestFileNote');

		$this->RequestFile->recursive = -1;
		$request = $this->RequestFile->findByid($in);

		switch ($request['RequestFile']['status']) {
			case 5:
				$next_status = 7;
				break;
			case 6:
				$next_status = 10;
				break;
		}

		$note['RequestFileNote']['request_file_id'] = $in;
		$note['RequestFileNote']['user_id'] = $this->Session->read('id');
		$note['RequestFileNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['type'] = 1;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestFileNote']['note'] = $this->request->data['note'];
		else $note['RequestFileNote']['note'] = 'ninguna';


		$datasource = $this->RequestFile->getDataSource();
		try{
			$this->RequestFile->id = $in;
			if(!$this->RequestFile->saveField('status',$next_status))
	        	throw new Exception();

	        if(!$this->RequestFileNote->save($note))
		        throw new Exception();

		    $req['request'] = $in;
	        $req['title'] = $request['RequestFile']['title'];
	        $req['number'] = $request['RequestFile']['number'];

		    if($next_status == 7)
		    	$this->send_email(-1,$req,27);
		    else if($next_status == 10){
		    	$this->send_email(2,$req,28);
		    	$this->send_email(-1,$req,28);
		    }

			$datasource->commit();

			$this->Session->setFlash(__('Solicitud enviada con éxito.'));
		} catch(Exception $e) {
			$datasource->rollback();
			$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
		}

		$this->redirect('listar_presupuestos/'.$role);
	}


	public function modificar_presupuesto_ms($role,$in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('RequestFile');
		$this->loadModel('RequestFileNote');

		$this->RequestFile->recursive = -1;
		$request = $this->RequestFile->find('first', array(
			'conditions' => array('RequestFile.id' => $in),
			));
		
		$this->RequestFile->id = $in;

		$note['RequestFileNote']['request_file_id'] = $in;
		$note['RequestFileNote']['user_id'] = $this->Session->read('id');
		$note['RequestFileNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['updated'] = date('Y-m-d H:i:s');
		$note['RequestFileNote']['type'] = 2;
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestFileNote']['note'] = $this->request->data['note'];
		else $note['RequestFileNote']['note'] = 'ninguna';

		$datasource = $this->RequestFile->getDataSource();
		try{
			if(!$this->RequestFile->saveField('status', 8))
		        throw new Exception();

			if(!$this->RequestFileNote->save($note))
		        throw new Exception();

		    
		    $req['request'] = $this->Request->id;
			$req['title'] = $request['Request']['title'];
			$req['note'] = $this->request->data['note'];

		    $this->send_email(-1,$req,23);

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}

		$this->redirect('listar_presupuestos/1');
	}

	public function habilitar_soportes($id){
		$this->autoRender = false;
		$this->Request->enable($id);
	}

	public function deshabilitar_soportes($id){
		$this->autoRender = false;
		$this->Request->disable($id);
	}

	public function is_supportable($id){
		$this->autoRender = false;
		return $this->Request->is_supportable($id);
	}
}
