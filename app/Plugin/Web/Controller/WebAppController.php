<?php

App::uses('AppController', 'Controller');
App::uses('GroupType', 'Model');
App::uses('UserType', 'Model');

class WebAppController extends AppController {

	public $uses = array("Group", "State", "Request", "GroupMember");
	
	public $components = array(
        'Session',
        'Paginator',
        'Cookie',
    );
	
	public function beforeFilter(){
		parent::beforeFilter();
		//$this->Auth->allow();
		if(!$this->isAuthorized($this->Session->read('id'))) {
			$this->redirect('/web');
		}
		
	    if($this->Session->read('current_user') == NULL) {
	        $this->redirect('/web/login');
		}
		else{
			if($this->Session->read('activated') == 'NO')
				$this->redirect('/web/bienvenida/');
		}

		$this->loadModel('GroupMember');
		$group_types = $this->GroupMember->find('all', array(
			'conditions' => array('GroupMember.user_id' => $this->Session->read('id')),
			'fields' => array('Group.group_type_id'),
			'group' => 'Group.group_type_id'
			));

		$group_types = Set::classicExtract($group_types, '{n}.Group.group_type_id');

		$this->set(compact('group_types'));
	}
	
	public function isAuthorized($user) {
		return true;
	}
	
	//----------------------------------------------------------------------------
	public function printWithFormat($var, $withDie = false) { //Funcion de control para imprimir resultados
		echo "<pre>";
		var_dump($var);
		echo "</pre>";
		
		if($withDie) {
			die();
		}
	}

	public function sign_pdf($file_path, $signature, $position){
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$ftype = finfo_file($finfo,$signature);
		if($ftype != "image/jpeg"){
        	throw new \Exception();
		}

		require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdf.php');
		require_once(ROOT . DS . 'app' . DS . 'Plugin' . DS . 'Cms' . DS . 'webroot' . DS .  'vendor' . DS . 'fpdf' . DS . 'fpdi.php');

		$bottom = 0;

		switch ($position) {
			case 1:
				$bottom = 20;
				$margin = 15;
				$signer = "ELABORADO EJECUTIVO";
				break;
			case 2:
				$margin = 78;
				$signer = "APROBADO CLIENTE";
				break;
			case 3:
				$margin = 140;
				$signer = "APROBADO CLIENTE";
				break;
			case 4:
				$bottom = 20;
				$margin = 10;
				$signer = "ELABORADO EJECUTIVO";
				break;
			case 5:
				$margin = 50;
				$signer = "APROBADO DIRECTOR";
				break;
			case 6:
				$margin = 90;
				$signer = "APROBADO GERENTE";
				break;
			case 7:
				$margin = 130;
				$signer = "APROBADO CLIENTE";
				break;
			case 8:
				$margin = 170;
				$signer = "APROBADO CLIENTE";
				break;
		}

		$pdf =& new FPDI();
		$pdf->SetAutoPageBreak(false);
		$count = $pdf->setSourceFile($file_path);

		for($i = 1; $i <= $count; $i++){
		    $tplidx = $pdf->importPage($i);
			$specs = $pdf->getTemplateSize($tplidx);
			$pdf->addPage($specs['h'] > $specs['w'] ? 'P' : 'L', array($specs['w'], $specs['h']+$bottom));
		    $pdf->useTemplate($tplidx, 0, 0, 0, 0, true);
		}

		$pdf->SetFont('Courier','B',9);
		$pdf->SetTextColor(0,0,128);
		$pdf->Image($signature,$margin,$specs['h']-30+$bottom,30,18);
	   	$pdf->SetXY($margin,$specs['h']-10+$bottom);
		$pdf->Write(0,$signer);

		$pdf->Output($file_path,'F');
	}

	
	//------------------------------------------------------------------
	public function send_email($grupo,$request,$tipo){
		switch ($grupo) {
			case 0:
				$this->loadModel('Request');
				$this->Request->recursive = 1;
				$usersMail = $this->Request->find('all', array(
					'conditions' => array('Request.id' => $request['request']),
					'fields' => array('User.email', 'User.first_name', 'User.last_name'),
					));
				break;
			case -1:
				$this->loadModel('RequestFile');
				$this->RequestFile->recursive = 1;
				$usersMail = $this->RequestFile->find('all', array(
					'conditions' => array('RequestFile.id' => $request['request']),
					'fields' => array('User.email', 'User.first_name', 'User.last_name'),
					));
				break;
			default:
				$this->loadModel('GroupMember');
				$usersMail = $this->GroupMember->find('all', array(
					'conditions' => array('GroupMember.group_id' => $grupo),
					'fields' => array('User.email', 'User.first_name', 'User.last_name', 'Group.name')
					));
				break;
		}

		App::uses('CakeEmail', 'Network/Email');

		$subject = '';
		$content = '';

		switch ($tipo) {
			case 1:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes una nueva solicitud';
				$content = '<br><br>El concesionario <b>'.$request['grupoName'].' </b> ha realizado una nueva solicitud de presupuesto, la cual requiere de tu aprobación.<br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 2:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes una nueva solicitud aprobada por JWT';
				$content = '<br><br>El concesionario <b>'.$request['grupoName'].' </b> ha realizado una nueva solicitud de presupuesto, la cual fue aprobada por JWT.<br><br>La solicitud <b>'.$request['title'].' </b> requiere de su aprobación.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 3:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tu solicitud fue rechazada';
				$content = '<br><br>La solicitud <b>'.$request['title'].' </b> realizada por el concesionario <b>'.$request['grupoName'].' </b> no fue aprobada por JWT.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 4:
				$subject = '[ Asignación de Saldo : '.$usersMail[0]['Group']['name'].' ]';
				$content = '<br><br>Ford ha asignado al concesionario <b>'.$usersMail[0]['Group']['name'].' </b> un saldo de: <br><br>$'.number_format((float)($this->request->data['privado']), 0,',','.').' para el mes de '.$this->request->data['mes_nombre'].'.<br><br>Para hacer uso de su saldo por medio de solicitudes de presupuesto haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 5:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Solicitud aprobada';
				$content = '<br><br>La solicitud <b>'.$request['title'].' </b> realizada por el concesionario <b>'.$request['grupoName'].'</b> fue aprobada por Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 6:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Solicitud rechazada';
				$content = '<br><br>La solicitud <b>'.$request['title'].' </b> realizada por el concesionario <b>'.$request['grupoName'].'</b> no fue aprobada por Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 7:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo presupuesto';
				$content = '<br><br><b>JWT</b> ha realizado un nuevo presupuesto publicitario, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 8:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo presupuesto aprobado por la Gerencia de Comunicaciones Ford';
				$content = '<br><br><b>JWT</b> ha realizado un nuevo presupuesto publicitario, el cual fue aprobado por la Gerencia de Comunicaciones de Ford.<br><br>El presupuesto <b>'.$request['title'].' </b> requiere de su aprobación.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 9:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Presupuesto aprobado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>JWT</b> fue aprobado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 10:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tu presupuesto fue rechazado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>JWT</b> no fue aprobado por la Gerencia de Comunicaciones de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 11:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Presupuesto rechazado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>JWT</b> no fue aprobado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 12:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Presupuesto necesita modificación';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>JWT</b> necesita ser modificado por solicitud de la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 13:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo flujo de pauta';
				$content = '<br><br><b>Mindshare</b> ha realizado un nuevo flujo de pauta, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 14:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo flujo de pauta aprobado por la Gerencia de Comunicaciones Ford';
				$content = '<br><br><b>Mindshare</b> ha realizado un nuevo flujo de pauta, el cual fue aprobado por la Gerencia de Comunicaciones de Ford.<br><br>El flujo de pauta <b>'.$request['title'].' </b> requiere de su aprobación.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 15:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Flujo de pauta aprobado';
				$content = '<br><br>El flujo de pauta <b>'.$request['title'].' </b> realizado por <b>Mindshare</b> fue aprobado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 16:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tu flujo de pauta fue rechazado';
				$content = '<br><br>El flujo de pauta <b>'.$request['title'].' </b> realizado por <b>Mindshare</b> no fue aprobado por la Gerencia de Comunicaciones de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 17:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Flujo de pauta rechazado';
				$content = '<br><br>El flujo de pauta <b>'.$request['title'].' </b> realizado por <b>Mindshare</b> no fue aprobado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 18:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Flujo de pauta necesita modificación';
				$content = '<br><br>El flujo de pauta <b>'.$request['title'].' </b> realizado por <b>Mindshare</b> necesita ser modificado por solicitud de la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 32:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Flujo de pauta necesita modificación';
				$content = '<br><br>El flujo de pauta <b>'.$request['title'].' </b> realizado por <b>Mindshare</b> necesita ser modificado por solicitud de la Gerencia de Comunicaciones de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 19:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo presupuesto';
				$content = '<br><br><b>Ejecutivo de Compras Mindshare</b> ha realizado un nuevo presupuesto publicitario, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 20:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Firmado Dirección de Cuentas';
				$content = '<br><br><b>Dirección de Cuentas Mindshare</b> ha firmado su nuevo presupuesto publicitario. Aun requiere la firma del Gerente de Negociación para su pase a evaluación por parte de Ford.<br><br>Le notificaremos vía correo electrónico cuando la Gerencia de Negociación revise su presupuesto.<br><br>Si necesitas ayuda, en la siguiente URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>http://fprford.com<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 21:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Firmado Gerencia de Negociación';
				$content = '<br><br><b>Gerencia de Negociación Mindshare</b> ha firmado su nuevo presupuesto publicitario. Aun requiere la firma del Director de Cuentas para su pase a evaluación por parte de Ford.<br><br>Le notificaremos vía correo electrónico cuando la Dirección de Cuentas revise su presupuesto.<br><br>Si necesitas ayuda, en la siguiente URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>http://fprford.com<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 22:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Firmado y enviado a Ford';
				$content = '<br><br><b>Director de Cuentas y Gerente de Negociación Mindshare</b> han firmado su nuevo presupuesto publicitario. Hemos enviado su presupuesto a la gerencia de Ford para su evaluación.<br><br>Le notificaremos vía correo electrónico cuando la gerencia de Ford revise su presupuesto.<br><br>Si necesitas ayuda, en la siguiente URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>http://fprford.com<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 23:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Presupuesto necesita modificación';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> necesita ser modificado.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 24:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Presupuesto modificado';
				$content = '<br><br><b>Ejecutivo de Compras Mindshare</b> ha realizado una modificación al presupuesto publicitario, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 25:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo presupuesto';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>Ejecutivo de Compras Mindshare</b> fue aprobado por la Gerencia de Comunicaciones de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 26:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Presupuesto aprobado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>Ejecutivo de Compras Mindshare</b> fue aprobado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 27:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Tu presupuesto fue rechazado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>Ejecutivo de Compras Mindshare</b> fue rechazado por la Gerencia de Comunicaciones de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 28:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Presupuesto rechazado';
				$content = '<br><br>El presupuesto <b>'.$request['title'].' </b> realizado por <b>Ejecutivo de Compras Mindshare</b> fue rechazado por la Gerencia de Mercadeo de Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 29:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Presupuesto modificado';
				$content = '<br><br><b>JWT</b> ha realizado ajustes en el presupuesto publicitario antes de su aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 30:
				$subject = '[ Presupuesto Nro. '.str_pad($request['number'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo presupuesto';
				$content = '<br><br><b>Ejecutivo de Compras Mindshare</b> ha realizado un nuevo presupuesto publicitario que ha sido aprobado por la Dirección de Compras y la Gerencia de Negociación, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 31:
				$subject = '[ Flujo de Pauta Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Tienes un nuevo flujo de pauta modificado';
				$content = '<br><br><b>Mindshare</b> ha realizado una modificación al flujo de pauta, el cual requiere de tu aprobación.<br><br>Para verlo haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 998:
				$subject = '[ Presupuesto Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Presupuesto anulado';
				$content = '<br><br>El Presupuesto <b>'.$request['title'].' </b> realizado por JWT fue anulado por Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br> Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
				break;
			case 999:
				$subject = '[ Solicitud Nro. '.str_pad($request['request'], 8, "0", STR_PAD_LEFT).' ] Solicitud anulada';
				$content = '<br><br>La solicitud <b>'.$request['title'].' </b> realizada por el concesionario <b>'.$request['grupoName'].'</b> fue anulada por Ford.<br><br><u>Observaciones</u>: <i>'.$request['note'].'</i><br><br> Es posible que los recursos asignados a esta solicitud no se hayan empleado. Hemos reembolsado el dinero destinado a esta solicitud. Puede realizar nuevamente esta solicitud o crear una nueva.<br><br>Para verla haz click aqui:<br><br>http://fprford.com<br><br>Si necesitas ayuda, en esta URL podrás encontrar información relacionada al funcionamiento de este sistema.<br><br>Si tienes problemas para acceder al sistema, no tardes en escribir a soporte@ford.com.<br><br>Saludos.';
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