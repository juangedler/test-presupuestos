<?php
App::uses('State', 'Model');
class AgenciaController extends WebAppController {
	
	public $uses = array("Media", "RequestSupport");
	
	public function index(){
		$this->redirect('/web');
	}

	public function ver($in){

		$this->loadModel('Request');

		$this->loadModel('GroupMember');
		$groups = $this->GroupMember->find('all', array(
			'conditions' => array('Group.group_type_id' => 2),
			'fields' => array('Group.name', 'Group.id')
			));

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
			));

		foreach ($requests as &$r) {
			$r['Request']['group_name'] = $groupMname[$r['Request']['group_id']];
		}

		$option = $in;

		$this->set(compact('requests'));
		$this->set(compact('option'));
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

		if($requestEvents['Request']['blocked'] != 0 && $requestEvents['Request']['blocked'] != $this->Session->read('id')){
			echo '<p>Disculpe, esta solicitud está siendo modificada en este momento. Por favor intente mas tarde.</p>';
			exit;
		}

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

	public function aprobar($in){
		$this->layout = null;
		$this->autoRender = false;

		$this->loadModel('Request');
		$this->loadModel('RequestNote');

		$this->Request->recursive = -1;
		$request = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));

		$this->Request->id = $in;

		$note['RequestNote']['request_id'] = $in;
		$note['RequestNote']['user_id'] = $this->Session->read('id');
		$note['RequestNote']['created'] = date('Y-m-d H:i:s');
		$note['RequestNote']['updated'] = date('Y-m-d H:i:s');
		if(isset($this->request->data['note']) && $this->request->data['note'] != '') $note['RequestNote']['note'] = $this->request->data['note'];
		else $note['RequestNote']['note'] = 'ninguna';

		$datasource = $this->Request->getDataSource();
		try{
			if(!$this->Request->saveField('current_state_id', 2))
		        throw new Exception();

			if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
		    $req['grupoName'] = $this->request->data['grupoName'];
		    $req['note'] = $this->request->data['note'];
		    $req['title'] = $request['Request']['title'];

		    $this->send_email(array(2,3),$req,2);

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

		    if(!$this->Request->saveField('current_state_id', 4))
		        throw new Exception();

		    if(!$this->RequestNote->save($note))
		        throw new Exception();

		    $req['request'] = $this->Request->id;
		    $req['grupoName'] = $this->request->data['grupoName'];
		    $req['note'] = $this->request->data['note'];
		    $req['title'] = $request['Request']['title'];

		    $this->send_email($request['Request']['group_id'],$req,3);

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

	public function solicitud(){
		$actualizacion = 0;

		if($this->request->is('post')){
			$this->loadModel('User');
			$this->loadModel('Request');
			$this->loadModel('RequestFile');
			$this->loadModel('GroupMember');

			switch ($this->Session->read('type')) {
				case 'JWT':
					$type = 2;
					$gtype = 3;
					$mtype = 7;
					break;

				case 'Mindshare':
					$type = 3;
					$gtype = 8;
					$mtype = 13;
					break;
			}

			$group = $this->GroupMember->find('first', array(
				'conditions' => array('Group.group_type_id' => $gtype, 'GroupMember.user_id' => $this->Session->read('id')),
				'fields' => array('GroupMember.group_id')
				));

			$request['Request']['number'] = $this->request->data['consecutivo'];
			$request['Request']['user_id'] = $this->Session->read('id');
			$request['Request']['group_id'] = $group['GroupMember']['group_id'];
			$request['Request']['process_id'] = 2;
			$request['Request']['request_type_id'] = $type;
			$request['Request']['title'] = $this->request->data['nombre'];
			$request['Request']['amount'] = $this->request->data['monto'];
			$request['Request']['current_state_id'] = 6;

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/tango/' . $rand . basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['number'] = $this->request->data['consecutivo'];
			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['type'] = 'Tango';

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->Request->getDataSource();
			try{
				if(!$this->Request->save($request))
		        	throw new Exception();

				$requestFile['RequestFile']['request_id'] = $this->Request->id;

				if(!$this->RequestFile->saveAll($requestFile))
		        	throw new Exception();

		        move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

				$pdf =& new FPDI();

				$pdf->SetAutoPageBreak(false);

				$count = $pdf->setSourceFile($file_path);

				for($i = 1; $i <= $count; $i++){
				    $tplidx = $pdf->importPage($i);
					$specs = $pdf->getTemplateSize($tplidx);
					$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']+20));
				    $pdf->useTemplate($tplidx, 0, 0, 0, 0, true);
				}

				$pdf->SetFont('Courier','B',9);
				$pdf->SetTextColor(0,0,128);

				/*$pdf->Image($user_sign['User']['signature'],15,270,50,18);
				$pdf->SetXY(20, 290);*/
				$pdf->Image($user_sign['User']['signature'],15,$specs['h']-10,30,18);
               	$pdf->SetXY(20, $specs['h']+10);
				$pdf->Write(0, "ELABORADO EJECUTIVO");

				$pdf->Output($file_path,'F');

		        $req['request'] = $this->Request->id;

			    $this->send_email(2,$req,$mtype);

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}
			$actualizacion = 1;
		}
		$this->set(compact('actualizacion'));
	}

	public function listar($in){

		switch ($this->Session->read('type')) {
			case 'JWT':
				$type = 2;
				switch ($in) {
					case 1:
						$option = array(6,7,11); break;
					case 2:
						$option = 8; break;
					default:
						$option = array(9,10); break;
				}
				break;

			case 'Mindshare':
				$type = 3;
				switch ($in) {
					case 1:
						$option = array(12,13,17); break;
					case 2:
						$option = 14; break;
					default:
						$option = array(15,16); break;
				}
				break;
		}

		$this->loadModel('Request');
		$this->loadModel('RequestFile');
		$this->loadModel('RequestNote');

		$requests = $this->Request->find('all', array(
			'conditions' => array('User.id' => $this->Session->read('id'),'Request.current_state_id' => $option, 'Request.request_type_id' => $type),
			'order' => array('Request.id' => 'DESC'),
			'fields' => array('Request.id', 'Request.request_type_id', 'Request.title', 'Request.number', 'Request.created', 'Request.amount', 'Request.current_state_id', 'User.first_name', 'User.last_name'),
			));

		$this->RequestFile->recursive = -1;
		$this->RequestNote->recursive = -1;

		foreach ($requests as &$r){
			$r['RequestFile'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $r['Request']['id'], 'RequestFile.type'=>'Tango'),
				'order' => array('RequestFile.created' => 'DESC'),
				));
			$r['RequestFile2'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $r['Request']['id'], 'RequestFile.type'=>'Pauta'),
				'order' => array('RequestFile.created' => 'DESC'),
				));
			if(in_array($r['Request']['current_state_id'], array(11,17)))
				$r['RequestNote'] = $this->RequestNote->find('all',array(
				'conditions' => array('RequestNote.request_id' => $r['Request']['id'], 'RequestNote.type' => 2),
				'order' => array('RequestNote.created' => 'DESC'),
				));
			else 
				$r['RequestNote'] = $this->RequestNote->find('all',array(
				'conditions' => array('RequestNote.request_id' => $r['Request']['id'], 'RequestNote.type' => 1),
				));
		}

		switch ($in) {
			case 1:
				$title = 'Pendientes'; break;
			case 2:
				$title = 'Aprobadas'; break;
			case 3:
				$title = 'Rechazadas'; break;
		}

		$this->set(compact('requests','title'));
	}

	public function modificar($in){

		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestFile');
		$actualizacion = 0;

		if($this->request->is('post')){
			$request['Request']['id'] = $this->request->data['id'];
			$request['Request']['number'] = $this->request->data['consecutivo'];
			$request['Request']['user_id'] = $this->Session->read('id');
			$request['Request']['process_id'] = 2;
			$request['Request']['title'] = $this->request->data['nombre'];
			$request['Request']['amount'] = ($this->request->data['monto']);
			$request['Request']['current_state_id'] = 6;

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/tango/'. $rand .basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['number'] = $this->request->data['consecutivo'];
			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['type'] = 'Tango';

			/*
			$prevTangoFile = $this->RequestFile->find('first', array(
				'conditions' => array('RequestFile.request_id' => $in, 'RequestFile.status' => 1)
				));

			$prevTangoFile['RequestFile']['status'] = 2;
			*/

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->Request->getDataSource();
			try{
				if(!$this->Request->save($request))
		        	throw new Exception();

				$requestFile['RequestFile']['request_id'] = $this->Request->id;

				if(!$this->RequestFile->save($requestFile))
		        	throw new Exception();

		        move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

		        require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

				$pdf =& new FPDI();

				$pdf->SetAutoPageBreak(false);

				$count = $pdf->setSourceFile($file_path);

				for($i = 1; $i <= $count; $i++){
				    $tplidx = $pdf->importPage($i);
					$specs = $pdf->getTemplateSize($tplidx);
					$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']+20));
				    $pdf->useTemplate($tplidx, 0, 0, 0, 0, true);
				}

				$pdf->SetFont('Courier','B',9);
				$pdf->SetTextColor(0,0,128);

				/*$pdf->Image($user_sign['User']['signature'],15,270,50,18);
				$pdf->SetXY(20, 290);*/
				$pdf->Image($user_sign['User']['signature'],15,$specs['h']-10,30,18);
               	$pdf->SetXY(20, $specs['h']+10);
				$pdf->Write(0, "ELABORADO EJECUTIVO");

				$pdf->Output($file_path,'F');

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud modificada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al modificar su solicitud. Por favor intente nuevamente.'));
			}
			$actualizacion = 1;
		}

		$requests = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			'order' => array('Request.created' => 'DESC'),
			));

		$requests['RequestFile'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $requests['Request']['id']),
				'order' => array('RequestFile.created' => 'DESC'),
				));

		$this->set(compact('requests','actualizacion'));
	}

	public function validar($in){
		$this->layout = null;
		$this->autoRender = false;
		$this->loadModel('Request');
		$data = $this->Request->findBynumber($in);
		if (count($data) > 0) echo 'exist';
		else echo 'ok';
	}

	public function validar_pdf($in){
		$this->layout = null;
		$this->autoRender = false;
		$this->loadModel('RequestFile');
		$data = $this->RequestFile->find('all', array(
			'conditions' => array('RequestFile.type' => 'PDF', 'RequestFile.number' => $in)
			));
		if (count($data) > 0) echo 'exist';
		else echo 'ok';
	}

	public function nueva_pauta(){
		$actualizacion = 0;

		if($this->request->is('post')){
			set_time_limit(180);
			ini_set('memory_limit', '-1'); 

			$this->loadModel('User');
			$this->loadModel('Request');
			$this->loadModel('RequestFile');
			$this->loadModel('GroupMember');

			$group = $this->GroupMember->find('first', array(
				'conditions' => array('Group.group_type_id' => 8, 'GroupMember.user_id' => $this->Session->read('id')),
				'fields' => array('GroupMember.group_id')
				));

			$request['Request']['user_id'] = $this->Session->read('id');
			$request['Request']['group_id'] = $group['GroupMember']['group_id'];
			$request['Request']['process_id'] = 3;
			$request['Request']['request_type_id'] = 3;
			$request['Request']['title'] = $this->request->data['nombre'];
			$request['Request']['amount'] = $this->request->data['monto'];
			$request['Request']['current_state_id'] = 12;

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/pauta/' . $rand . basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['type'] = 'Pauta';

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->Request->getDataSource();
			try{
				if(!$this->Request->save($request))
		        	throw new Exception();

				$requestFile['RequestFile']['request_id'] = $this->Request->id;

				if(!$this->RequestFile->save($requestFile))
		        	throw new Exception();
		        else
		        	move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

				date_default_timezone_set('America/Bogota');
		        require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'PHPExcel_1.8.0_doc' . DS . 'Classes' . DS . 'PHPExcel.php');

		        $fileType = 'Excel2007';
				$fileName = $file_path;

				$objPHPExcelReader = PHPExcel_IOFactory::createReader($fileType);
				$objPHPExcel = $objPHPExcelReader->load($fileName);

				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($user_sign['User']['signature']);
				$objDrawing->setCoordinates('I4');
				$objDrawing->setResizeProportional(false);
				$objDrawing->setHeight(80);
				$objDrawing->setWidth(200);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

				$objSheet = $objPHPExcel->getActiveSheet();
				$objSheet->getStyle('I6')->getFont()->getColor()->setRGB('000080');
				$objSheet->getStyle('I6')->getFont()->setName('Courier')->setBold(true)->setSize(11);
				$objSheet->getCell('I6')->setValue('APROBADO DIRECTOR');

				$objPHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$fileType);

				$objPHPExcelWriter->save('files/pauta/temp.xlsx');
				unlink($fileName);
				rename('files/pauta/temp.xlsx',$fileName);

		        $req['request'] = $this->Request->id;
			    $this->send_email(2,$req,13);

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}

			$actualizacion = 1;
		}

		$this->set(compact('actualizacion'));
	}

	public function modificar_pauta($in){
		$this->loadModel('User');
		$this->loadModel('Request');
		$this->loadModel('RequestFile');
		$actualizacion = 0;

		if($this->request->is('post')){
			set_time_limit(180);
			ini_set('memory_limit', '-1');

			$request['Request']['id'] = $this->request->data['id'];
			$request['Request']['title'] = $this->request->data['nombre'];
			$request['Request']['amount'] = $this->request->data['monto'];
			$request['Request']['current_state_id'] = 12;

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/pauta/' . $rand . basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['type'] = 'Pauta';

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->Request->getDataSource();
			try{
				if(!$this->Request->save($request))
		        	throw new Exception();

				$requestFile['RequestFile']['request_id'] = $this->Request->id;

				if(!$this->RequestFile->save($requestFile))
		        	throw new Exception();
		        else
		        	move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

				date_default_timezone_set('America/Bogota');
		        require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'PHPExcel_1.8.0_doc' . DS . 'Classes' . DS . 'PHPExcel.php');

		        $fileType = 'Excel2007';
				$fileName = $file_path;

				$objPHPExcelReader = PHPExcel_IOFactory::createReader($fileType);
				$objPHPExcel = $objPHPExcelReader->load($fileName);

				$objDrawing = new PHPExcel_Worksheet_Drawing();
				$objDrawing->setPath($user_sign['User']['signature']);
				$objDrawing->setCoordinates('I4');
				$objDrawing->setResizeProportional(false);
				$objDrawing->setHeight(80);
				$objDrawing->setWidth(200);
				$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

				$objSheet = $objPHPExcel->getActiveSheet();
				$objSheet->getStyle('I6')->getFont()->getColor()->setRGB('000080');
				$objSheet->getStyle('I6')->getFont()->setName('Courier')->setBold(true)->setSize(11);
				$objSheet->getCell('I6')->setValue('APROBADO DIRECTOR');

				$objPHPExcelWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,$fileType);

				$objPHPExcelWriter->save('files/pauta/temp.xlsx');
				unlink($fileName);
				rename('files/pauta/temp.xlsx',$fileName);

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}

			$actualizacion = 1;
		}

		$requests = $this->Request->find('first', array(
			'conditions' => array('Request.id' => $in),
			));

		$requests['RequestFile'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $requests['Request']['id'], 'RequestFile.type' => 'Pauta'),
				'order' => array('RequestFile.created' => 'DESC'),
				));

		$this->set(compact('requests','actualizacion'));
	}

	public function listar_pautas($in = null){

		$this->loadModel('Request');
		$this->loadModel('RequestFile');
		$this->loadModel('RequestFileNote');

		$state = 14;
		if(isset($in) && $in != null) $state = 20;

		$requests = $this->Request->find('all', array(
			'conditions' => array('Request.current_state_id' => $state, 'Request.request_type_id' => 3),
			'order' => array('Request.id' => 'DESC'),
			'fields' => array('Request.id', 'Request.request_type_id', 'Request.title', 'Request.number', 'Request.created', 'Request.amount', 'Request.current_state_id', 'User.first_name', 'User.last_name'),
			));

		$this->RequestFile->recursive = -1;

		foreach ($requests as &$r){
			$r['RequestFile'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $r['Request']['id'], 'RequestFile.type'=>'Pauta'),
				'order' => array('RequestFile.created' => 'DESC'),
				));
			$r['RequestFile2'] = $this->RequestFile->find('all', array(
				'conditions' => array('RequestFile.request_id' => $r['Request']['id'], 'RequestFile.type'=>'PDF'),
				'order' => array('RequestFile.created' => 'DESC'),
				));
			foreach ($r['RequestFile2'] as &$rf2) {
				$rf2['RequestFileNote'] = $this->RequestFileNote->find('first', array(
					'conditions' => array('RequestFileNote.request_file_id' => $rf2['RequestFile']['id']),
					));
			}
		}

		$this->set(compact('requests', 'state'));
	}

	public function nuevo_presupuesto($request_id){
		$actualizacion = 0;

		if($this->request->is('post')){
			$this->loadModel('User');
			$this->loadModel('RequestFile');

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/presupuestos/' . $rand . basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['request_id'] = $this->request->data['request'];
			$requestFile['RequestFile']['user_id'] = $this->Session->read('id');
			$requestFile['RequestFile']['number'] = $this->request->data['consecutivo'];
			$requestFile['RequestFile']['title'] = $this->request->data['nombre'];
			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['amount'] = $this->request->data['monto'];
			$requestFile['RequestFile']['type'] = 'PDF';

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->RequestFile->getDataSource();
			try{
				if(!$this->RequestFile->saveAll($requestFile))
		        	throw new Exception();

		        move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

				$pdf =& new FPDI();

				$pdf->SetAutoPageBreak(false);

				$count = $pdf->setSourceFile($file_path);

				for($i = 1; $i <= $count; $i++){
					$tplidx = $pdf->importPage($i);
					$specs = $pdf->getTemplateSize($tplidx);
					$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']+20));
				    $pdf->useTemplate($tplidx, 0, 0, 0, 0, true);
				}

				$pdf->SetFont('Courier','B',7);
				$pdf->SetTextColor(0,0,128);

				/*$pdf->Image($user_sign['User']['signature'],10,270,30,18);
				$pdf->SetXY(10, 290);*/
				$pdf->Image($user_sign['User']['signature'],10,$specs['h']-10,30,18);
               	$pdf->SetXY(10, $specs['h']+10);
				$pdf->Write(0, "ELABORADO EJECUTIVO");

				$pdf->Output($file_path,'F');

		        $req['request'] = $this->request->data['request'];
		        $req['number'] = $requestFile['RequestFile']['number'];

			    $this->send_email(7,$req,19);
			    $this->send_email(9,$req,19);

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}

			$actualizacion = 1;
		}

		$this->set(compact('actualizacion','request_id'));
	}

	public function listar_presupuestos($in){
		$this->loadModel('RequestFile');

		switch ($in) {
			case 1:
				$status = array(1, 4);
				break;
			case 2:
				$status = array(1, 3);
				break;
			case 3:
				$status = array(3, 5);
				break;
			case 4:
				$status = array(4, 5);
				break;
		}

		//$in == 1 ? $status = 4 : $status = 3;

		$this->RequestFile->recursive = -1;

		$requests = $this->RequestFile->find('all', array(
			'conditions' => array('RequestFile.status' => $status, 'RequestFile.type' => 'PDF')
			));

		$this->set(compact('requests','in'));
	}

	public function aprobar_presupuesto($role, $request_id){
		$this->loadModel('User');
		$this->loadModel('RequestFile');

		$this->RequestFile->recursive = -1;
		$request = $this->RequestFile->findByid($request_id);

		switch ($role) {
			case 2:
				switch ($request['RequestFile']['status']) {
					case 1:
						$next_status = 4; break;
					default:
						$next_status = 5; break;
				}
				break;
			default:
				switch ($request['RequestFile']['status']) {
					case 1:
						$next_status = 3; break;
					default:
						$next_status = 5; break;
				}
				break;
		}

		$file_path = $request['RequestFile']['file'];

		$user_sign = $this->User->find('first', array(
			'conditions' => array('User.id' => $this->Session->read('id')),
			'fields' => array('User.signature')
			));

		$datasource = $this->RequestFile->getDataSource();
		try{
			$this->RequestFile->id = $request_id;
			if(!$this->RequestFile->saveField('status',$next_status))
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

			if($role == 1) {
				/*$pdf->Image($user_sign['User']['signature'],50,270,30,18);
				$pdf->SetXY(50, 290);*/
				$pdf->Image($user_sign['User']['signature'],50,$specs['h']-30,30,18);
               	$pdf->SetXY(50, $specs['h']-10);
				$pdf->Write(0, "APROBADO DIRECTOR");
			}
		    else if($role == 2) {
		    	/*$pdf->Image($user_sign['User']['signature'],90,270,30,18);
				$pdf->SetXY(90, 290);*/
				$pdf->Image($user_sign['User']['signature'],90,$specs['h']-30,30,18);
               	$pdf->SetXY(90, $specs['h']-10);
				$pdf->Write(0, "APROBADO GERENTE");
		    }

			$pdf->Output($file_path,'F');

			$req['request'] = $this->RequestFile->id;
			$req['number'] = $request['RequestFile']['number'];

			switch ($next_status) {
				case 3:
				    $this->send_email(-1,$req,20);
					break;
				case 4:
					$this->send_email(-1,$req,21);
					break;
				case 5:
					$this->send_email(-1,$req,22);
					break;
			}
	        

			$datasource->commit();

			$this->Session->setFlash(__('Solicitud enviada con éxito.'));
		} catch(Exception $e) {
			$datasource->rollback();
			$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
		}

		$this->redirect('listar_presupuestos/'.$role);
	}

	public function solicitar_modificacion_presupuesto($in,$obs){
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
		if(isset($obs) && $obs != '') $note['RequestFileNote']['note'] = $obs;
		else $note['RequestFileNote']['note'] = 'ninguna';

		$datasource = $this->RequestFile->getDataSource();
		try{
			if(!$this->RequestFile->saveField('status', 8))
		        throw new Exception();

			if(!$this->RequestFileNote->save($note))
		        throw new Exception();

		    
		    $req['request'] = $in;
		    $req['number'] = $request['RequestFile']['number'];
			$req['title'] = $request['RequestFile']['title'];
			$req['note'] = $note['RequestFileNote']['note'];
		    $this->send_email(-1,$req,23);
			

		    $datasource->commit();
		} catch(Exception $e) {
			$datasource->rollback();
		}

		$this->redirect('listar_presupuestos/1');
	}

	public function modificar_presupuesto($in){
		$this->loadModel('RequestFile');
		$requestFile = $this->RequestFile->findByid($in);

		$actualizacion = 0;


		if($this->request->is('post')){
			$this->loadModel('User');

			$rand = mt_rand(1000000,1000000000);
			$file_path = 'files/presupuestos/' . $rand . basename($_FILES['archivo']['name']);

			$requestFile['RequestFile']['request_id'] = $this->request->data['request'];
			$requestFile['RequestFile']['number'] = $this->request->data['consecutivo'];
			$requestFile['RequestFile']['title'] = $this->request->data['nombre'];
			$requestFile['RequestFile']['file'] = $file_path;
			$requestFile['RequestFile']['description'] = $this->request->data['descripcion'];
			$requestFile['RequestFile']['amount'] = $this->request->data['monto'];
			$requestFile['RequestFile']['type'] = 'PDF';
			$requestFile['RequestFile']['status'] = 1;

			$user_sign = $this->User->find('first', array(
				'conditions' => array('User.id' => $this->Session->read('id')),
				'fields' => array('User.signature')
				));

			$datasource = $this->RequestFile->getDataSource();
			try{
				if(!$this->RequestFile->saveAll($requestFile))
		        	throw new Exception();

		        move_uploaded_file($_FILES['archivo']['tmp_name'], $file_path);

				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
				require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

				$pdf =& new FPDI();

				$pdf->SetAutoPageBreak(false);

				$count = $pdf->setSourceFile($file_path);

				for($i = 1; $i <= $count; $i++){
				    $tplidx = $pdf->importPage($i);
					$specs = $pdf->getTemplateSize($tplidx);
					$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']+20));
				    $pdf->useTemplate($tplidx, 0, 0, 0, 0, true);
				}

				$pdf->SetFont('Courier','B',9);
				$pdf->SetTextColor(0,0,128);

				/*$pdf->Image($user_sign['User']['signature'],15,270,50,18);
				$pdf->SetXY(20, 290);*/
				$pdf->Image($user_sign['User']['signature'],15,$specs['h']-10,30,18);
               	$pdf->SetXY(20, $specs['h']+10);
				$pdf->Write(0, "ELABORADO EJECUTIVO");

				$pdf->Output($file_path,'F');

				$req['request'] = $this->request->data['request'];
		        $req['number'] = $requestFile['RequestFile']['number'];
				$this->send_email(2,$req,24);

				$datasource->commit();

				$this->Session->setFlash(__('Solicitud enviada con éxito.'));
			} catch(Exception $e) {
				$datasource->rollback();
				$this->Session->setFlash(__('Ha ocurrido un error al cargar su solicitud. Por favor intente nuevamente.'));
			}

			$actualizacion = 1;
		}

		$this->set(compact('requestFile','actualizacion'));
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