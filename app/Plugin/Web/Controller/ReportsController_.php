<?php

App::uses('AppController', 'Controller');
App::uses('GroupType', 'Model');
App::uses('State', 'Model');
App::uses('Process', 'Model');
App::uses('UserType', 'Model');
App::uses('NationalSingleMovement', 'Model');
//App::import('Vendor','PHPExcel',array('file' => 'excel/PHPExcel.php')); 
//App::import('Vendor','PHPExcelWriter',array('file' => 'excel/PHPExcel/Writer/Excel5.php')); 

class ReportsController extends WebAppController {
	
	public $uses = array("Group", "Event", "State", "Media", "Request", "RequestEvent", "User","Movement", "HistoricalBalance", "Process", "NationalSingleMovement");
	public $isJWTFPR;  
	//-----------------------------------------------------------------------------------------------------------------
	public function beforeFilter() {
	 	parent::beforeFilter();
    }
	
	//-----------------------------------------------------------------------------------------------------------------
	public function isAuthorized($userId) {
		$success=false;

		if($userId!=NULL){
			$currentUser = $this->User->find('first', array(
				'conditions' => array('User.id' => $userId),
				'fields' => array('User.id', 'User.user_type_id'),
				'recursive' => -1
			));
			
			//$this->printWithFormat($this->Session->read('type'),true);
			
			$groupTypes = $this->GroupMember->find('all', array(
				'conditions' => array('GroupMember.user_id' => $userId),
				'fields' => array('Group.group_type_id'),
				'group' => 'Group.group_type_id'
			));
			
			$groupTypes = Set::classicExtract($groupTypes, '{n}.Group.group_type_id');

			if($currentUser['User']['user_type_id']==UserType::USER_TYPE_FORD || $currentUser['User']['user_type_id']==UserType::USER_TYPE_MASTER || ($currentUser['User']['user_type_id']==UserType::USER_TYPE_JWT && in_array(GroupType::GROUP_TYPE_JWT_FPR, $groupTypes))){
				$success=!$success;
			}
			
			$this->isJWTFPR=($currentUser['User']['user_type_id']==UserType::USER_TYPE_JWT && in_array(GroupType::GROUP_TYPE_JWT_FPR, $groupTypes));		
		}

		return $success;
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	public function index() {
    }

	//------------------------------------------------------------------
	public function requestReport(){
		set_time_limit(0);
		
		$cities=array();
		$carDealerships=array();
		$states=array();
		
		$groups = $this->Group->find('all', array(
			'conditions' => array(
				'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
			),
			'fields' => array('Group.id','Group.city', 'Group.name'),
			'order'=> array('Group.name' => 'ASC'),
			'recursive'=> -1
		));

		foreach ($groups as $keyGroup => $group) {
			if($group['Group']['city']!=NULL && !in_array(trim($group['Group']['city']),$cities)){
				$cities[]=trim($group['Group']['city']);
			}
			
			if(!in_array(trim($group['Group']['name']),$carDealerships)){
				$aux['id']=$group['Group']['id'];
				$aux['name']=trim($group['Group']['name']);
				$carDealerships[]=$aux;
			}
		}
		
		asort($cities);

		$states = $this->State->find('all', array(
			'fields' => array('State.id','State.name'),
			'recursive'=> -1
		));

		$processes = $this->Process->find('all', array(
			'fields' => array('Process.id','Process.name'),
			'recursive'=> -1
		));

		$this->set(compact('cities'));
		$this->set(compact('carDealerships'));
		$this->set(compact('states'));
		$this->set(compact('processes'));
	}
	
	//------------------------------------------------------------------
	public function requestReportFilter(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$state=$this->request->query('state');
		$process=$this->request->query('process');

		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Request.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Request.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Request.current_state_id'=> $state)));
			$arrayCondition[]=$currentCondition;
		}

		if($process!='0'){
			$currentCondition=array('AND' => array(array('Request.process_id'=> $process)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}
		
		//$this->printWithFormat($arrayCondition);
		
		$this->Paginator->settings = array(
            'Request'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Request.group_id'
			            )
			        ),
			        array(
			            'table' => 'state',
			            'alias' => 'State',
			            'type' => 'INNER',
			            'conditions' => array(
			                'State.id = Request.current_state_id'
			            )
			        )
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'Request.id','Request.amount','Request.created', 'Request.title',
			    	'State.id', 'State.name', 'Request.process_id'
				),
        		'recursive'=>-1,
				'limit' => 10, 
             	'order' => array('Request.created' => 'DESC')
				)  
      		);

			$request=$this->Paginator->paginate('Request');

			$this->set(compact('request'));
			$this->set(compact('start'));
			$this->set(compact('end'));
			$this->set(compact('dealership'));
			$this->set(compact('city'));
			$this->set(compact('state'));
			$this->set(compact('process'));
	}
	
	//------------------------------------------------------------------
	public function eventsReport(){
		set_time_limit(0);
		
		$cities=array();
		$carDealerships=array();
		$states=array();
		
		$groups = $this->Group->find('all', array(
			'conditions' => array(
				'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
			),
			'fields' => array('Group.id','Group.city', 'Group.name'),
			'order'=> array('Group.name' => 'ASC'),
			'recursive'=> -1
		));

		foreach ($groups as $keyGroup => $group) {
			if($group['Group']['city']!=NULL && !in_array(trim($group['Group']['city']),$cities)){
				$cities[]=trim($group['Group']['city']);
			}
			
			if(!in_array(trim($group['Group']['name']),$carDealerships)){
				$aux['id']=$group['Group']['id'];
				$aux['name']=trim($group['Group']['name']);
				$carDealerships[]=$aux;
			}
		}
		
		asort($cities);

		$states = $this->State->find('all', array(
			'fields' => array('State.id','State.name'),
			'recursive'=> -1
		));

		$medias = $this->Media->find('all', array(
			'conditions'=> array('Media.show_flag' => 1),
			'fields' => array('Media.id','Media.name'),
			'recursive'=> -1
		));

		$this->set(compact('cities'));
		$this->set(compact('carDealerships'));
		$this->set(compact('states'));
		$this->set(compact('medias'));
	}

	//------------------------------------------------------------------
	public function eventsReportFilter(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$product=$this->request->query('product');
		$media=$this->request->query('media');
		$city=$this->request->query('city');
		$state=$this->request->query('state');

		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Request.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Request.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Request.current_state_id'=> $state)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($media!='0'){
			$currentCondition=array('AND' => array(array('Event.media_id'=> $media)));
			$arrayCondition[]=$currentCondition;
		}

		
		if($product!='0'){
			$currentCondition=array('AND' => array(array('Event.line LIKE'=> "%".$product."%")));
			$arrayCondition[]=$currentCondition;
		}
		
		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}
		
		$this->Event->virtualFields = array( //virtualField para el ordenamiento
		    'request_created' => 'Request.created'
		);
		
		$this->Paginator->settings = array(
            'Event'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
					array(
			            'table' => 'request_event',
			            'alias' => 'RequestEvent',
			            'type' => 'INNER',
			            'conditions' => array(
			                'RequestEvent.id = Event.request_event_id'
			            )
			        ),
					array(
			            'table' => 'request',
			            'alias' => 'Request',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Request.id = RequestEvent.request_id'
			            )
			        ),
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Request.group_id'
			            )
			        ),
			        array(
			            'table' => 'date',
			            'alias' => 'Date',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Date.event_id = Event.id'
			            )
			        ),
			        array(
			            'table' => 'medias',
			            'alias' => 'Media',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Media.id = Event.media_id'
			            )
			        ),
			        array(
			            'table' => 'state',
			            'alias' => 'State',
			            'type' => 'INNER',
			            'conditions' => array(
			                'State.id = Request.current_state_id'
			            )
			        )
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'Request.id','Request.amount','Request.created', 'Request.title',
			    	'Event.id','Event.amount','Event.media_id', 'Event.line', 'Event.description',
			    	'Media.id', 'Media.name',
			    	'State.id', 'State.name',
			    	'Date.id', 'Date.start', 'Date.end'
				),
        		'recursive'=>-1,
				'limit' => 10, 
             	'order' => array('Event.request_created' => 'DESC')
				)  
      		);

			$events=$this->Paginator->paginate('Event');

			$this->set(compact('events'));
			$this->set(compact('start'));
			$this->set(compact('end'));
			$this->set(compact('dealership'));
			$this->set(compact('product'));
			$this->set(compact('media'));
			$this->set(compact('city'));
			$this->set(compact('state'));
	}

	//------------------------------------------------------------------
	public function availableBalancesReport(){
		set_time_limit(0);
		
		$cities=array();
		$carDealerships=array();
		$states=array();
		
		$groups = $this->Group->find('all', array(
			'conditions' => array(
				'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
			),
			'fields' => array('Group.id','Group.city', 'Group.name'),
			'order'=> array('Group.name' => 'ASC'),
			'recursive'=> -1
		));

		foreach ($groups as $keyGroup => $group) {
			if($group['Group']['city']!=NULL && !in_array(trim($group['Group']['city']),$cities)){
				$cities[]=trim($group['Group']['city']);
			}
			
			if(!in_array(trim($group['Group']['name']),$carDealerships)){
				$aux['id']=$group['Group']['id'];
				$aux['name']=trim($group['Group']['name']);
				$carDealerships[]=$aux;
			}
		}
		
		asort($cities);

		$this->set(compact('cities'));
		$this->set(compact('carDealerships'));
	}
	
	//------------------------------------------------------------------
	public function availableBalancesReportFilter(){
		$this->layout=false;
		
		//$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$arrayCondition=array();
		$requestDateCondition = array();
		
		/*
		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('HistoricalBalance.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		*/
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Movement.created <='=> $endDate)));
			$requestDateCondition = array('Request.updated <= ' =>$endDate);
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}

		$currentCondition=array('AND' => array(array('Movement.type'=> "ABONO")));
		$arrayCondition[]=$currentCondition;

		$this->Paginator->settings = array(
            'Movement'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Movement.group_id',
			            )
			        ),
			        /*array(
						'table' => 'request',
						'alias' => 'Request',
						'type' 	=> 'LEFT',
						'conditions' => array(
							'Request.group_id = Movement.group_id',
							'Request.current_state_id in (1,2,3)',
							$requestDateCondition,
						)
					)*/
				),
	         	'fields'=>array(
	         		'sum(Movement.amount) as sumAmount',
	         		'sum(Movement.nacional) as sumNational',
	         		//'sum(Request.amount) as sumPending',
			    	'Group.id', 'Group.name', 'Group.city',
				),
				'group' => array('Movement.group_id'),
        		'recursive'=>-1,
				'limit' => 10, 
             	'order' => array('Movement.created' => 'DESC')
				)
      		);

			$pending = $this->Request->find('all', array(
				'conditions' => array(
							'current_state_id in (1,2,3)',
							$requestDateCondition,
						),
				'fields' => array(
							'Request.group_id',
							'sum(Request.amount) as sumPending'
						),
				'group' => 'Request.group_id',
				'recursive' => -1,
				));

			$temp;

			foreach ($pending as $key => $value) {
				$temp[$value['Request']['group_id']] = $value[0]['sumPending'];
			}

			$pending = $temp;

			$balances=$this->Paginator->paginate('Movement');

			foreach ($balances as &$balance) {
				if(!empty($pending[$balance['Group']['id']])) 
					$balance[0]['sumPending'] = $pending[$balance['Group']['id']];
				else $balance[0]['sumPending'] = '0';
			}

			$this->set(compact('pending'));
			$this->set(compact('balances'));
			$this->set(compact('dealership'));
			$this->set(compact('city'));
			$this->set(compact('end'));
			//$this->set(compact('start'));
	}

	//------------------------------------------------------------------
	public function movementsReport(){
		set_time_limit(0);
		
		$cities=array();
		$carDealerships=array();
		$states=array();
		
		$groups = $this->Group->find('all', array(
			'conditions' => array(
				'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
			),
			'fields' => array('Group.id','Group.city', 'Group.name'),
			'order'=> array('Group.name' => 'ASC'),
			'recursive'=> -1
		));

		foreach ($groups as $keyGroup => $group) {
			if($group['Group']['city']!=NULL && !in_array(trim($group['Group']['city']),$cities)){
				$cities[]=trim($group['Group']['city']);
			}
			
			if(!in_array(trim($group['Group']['name']),$carDealerships)){
				$aux['id']=$group['Group']['id'];
				$aux['name']=trim($group['Group']['name']);
				$carDealerships[]=$aux;
			}
		}
		
		asort($cities);

		$this->set(compact('cities'));
		$this->set(compact('carDealerships'));
	}
	
	//------------------------------------------------------------------
	public function movementsReportFilter(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$state=$this->request->query('state');

		$arrayCondition=array();
		
		$currentCondition=array("OR"=>array(
			array('Movement.type LIKE' => "%APROBADA%"),
			array('Movement.type LIKE' => "%RECHAZADA%"),
			array('Movement.type LIKE' => "%ABONO%"),
			)
		);
		
		$arrayCondition[]=$currentCondition;

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Movement.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Movement.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Movement.type'=> $state)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}

		$this->Paginator->settings = array(
            'Movement'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Movement.group_id'
			            )
			        ),
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'Movement.id', 'Movement.type', 'Movement.amount', 'Movement.created', 'Movement.request_id', 'Movement.balance_before','Movement.percentage'
				),
        		'recursive'=>-1,
				'limit' => 10, 
             	'order' => array('Movement.created' => 'DESC')
				)  
      		);

			$movements=$this->Paginator->paginate('Movement');
			$this->set(compact('movements'));
			$this->set(compact('start'));
			$this->set(compact('end'));
			$this->set(compact('dealership'));
			$this->set(compact('city'));
			$this->set(compact('state'));
	}

	//------------------------------------------------------------------
	public function nationalReport(){
		set_time_limit(0);
		
		$cities=array();
		$carDealerships=array();
		$states=array();
		
		$groups = $this->Group->find('all', array(
			'conditions' => array(
				'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
			),
			'fields' => array('Group.id','Group.city', 'Group.name'),
			'order'=> array('Group.name' => 'ASC'),
			'recursive'=> -1
		));

		foreach ($groups as $keyGroup => $group) {
			if($group['Group']['city']!=NULL && !in_array(trim($group['Group']['city']),$cities)){
				$cities[]=trim($group['Group']['city']);
			}
			
			if(!in_array(trim($group['Group']['name']),$carDealerships)){
				$aux['id']=$group['Group']['id'];
				$aux['name']=trim($group['Group']['name']);
				$carDealerships[]=$aux;
			}
		}
		
		asort($cities);

		$this->set(compact('cities'));
		$this->set(compact('carDealerships'));

	}
	
	//------------------------------------------------------------------
	public function nationalReportFilter(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$state=$this->request->query('state');

		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('NationalSingleMovement.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('NationalSingleMovement.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('NationalSingleMovement.type'=> $state)));
			$arrayCondition[]=$currentCondition;
		}

		$this->Paginator->settings = array(
            'NationalSingleMovement'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = NationalSingleMovement.group_id'
			            )
			        ),
			        array(
			            'table' => 'national_movement',
			            'alias' => 'NationalMovement',
			            'type' => 'INNER',
			            'conditions' => array(
			                'NationalMovement.id = NationalSingleMovement.national_movement_id'
			            )
			        ),
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'NationalSingleMovement.id', 'NationalSingleMovement.national_movement_id', 'NationalSingleMovement.group_id', 'NationalSingleMovement.national_before', 'NationalSingleMovement.national', 'NationalSingleMovement.type','NationalSingleMovement.created', 'NationalMovement.title'
				),
        		'recursive'=>-1,
				'limit' => 10, 
             	'order' => array('NationalSingleMovement.created' => 'DESC')
				)  
      		);

			$movements=$this->Paginator->paginate('NationalSingleMovement');
			$this->set(compact('movements'));
			$this->set(compact('start'));
			$this->set(compact('end'));
			$this->set(compact('dealership'));
			$this->set(compact('city'));
			$this->set(compact('state'));
	}

	//------------------------------------------------------------------
	public function onChangeCity(){
		$this->autoRender=false;
		$city=$this->request->data('city');
		
		$dealshipers=array();
		
		if($city!=NULL && $city!="0"){
			$dealshipers = $this->Group->find('all', array(
				'conditions' => array(
					'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO,
					'Group.city'=> $city
				),
				'fields' => array('Group.id','Group.name'),
				'order'=> array('Group.city' => 'ASC'),
				'recursive'=> -1
			));
		}else{
			$dealshipers = $this->Group->find('all', array(
				'conditions' => array(
					'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
				),
				'fields' => array('Group.id','Group.name'),
				'order'=> array('Group.city' => 'ASC'),
				'recursive'=> -1
			));
		}
		//$this->printWithFormat($dealshipers,true);
		return json_encode($dealshipers);
		//$this->set(compact('dealshipers'));
	}
	
	//------------------------------------------------------------------
	public function onChangeDealshiper(){
		$this->autoRender=false;
		$dealshiper=$this->request->data('dealshiper');
		$cities=array();
		
		if($dealshiper!=NULL && $dealshiper!="0"){
			$cities = $this->Group->find('all', array(
				'conditions' => array(
					'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO,
					'Group.id'=> $dealshiper
				),
				'fields' => array('Group.id','Group.city'),
				'order'=> array('Group.name' => 'ASC'),
				'recursive'=> -1
			));
		}else{
			$cities = $this->Group->find('all', array(
				'conditions' => array(
					'Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO
				),
				'fields' => array('Group.id','Group.city'),
				'order'=> array('Group.name' => 'ASC'),
				'recursive'=> -1
			));
		}
		return json_encode($cities);
		//$this->set(compact('cities'));
	}
	
	//-----------------------------------------------------------------------------------------------------------------
	public function requestReportExcel() {
		$this->layout=false;
		
		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$state=$this->request->query('state');
		
		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Request.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Request.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Request.current_state_id'=> $state)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}
		
		$requests = $this->Request->find("all", array(
			'conditions' => $arrayCondition,
         	'joins' => array(
		        array(
		            'table' => 'group',
		            'alias' => 'Group',
		            'type' => 'INNER',
		            'conditions' => array(
		                'Group.id = Request.group_id'
		            )
		        ),
		        array(
		            'table' => 'state',
		            'alias' => 'State',
		            'type' => 'INNER',
		            'conditions' => array(
		                'State.id = Request.current_state_id'
		            )
		        )
			),
			'fields'=>array(
		    	'Group.id', 'Group.name', 'Group.city',
		    	'Request.id','Request.amount','Request.created', 'Request.title',
		    	'State.id', 'State.name'
			),
    		'recursive'=>-1,
         	'order' => array('Request.created' => 'DESC')) 
		);

		$this->set(compact('requests'));
	}

	//------------------------------------------------------------------
	public function eventsReportExcel(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$product=$this->request->query('product');
		$media=$this->request->query('media');
		$city=$this->request->query('city');
		$state=$this->request->query('state');

		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Request.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Request.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Request.current_state_id'=> $state)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($media!='0'){
			$currentCondition=array('AND' => array(array('Event.media_id'=> $media)));
			$arrayCondition[]=$currentCondition;
		}

		
		if($product!='0'){
			$currentCondition=array('AND' => array(array('Event.line LIKE'=> "%".$product."%")));
			$arrayCondition[]=$currentCondition;
		}
		
		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}
		
		$this->Event->virtualFields = array( //virtualField para el ordenamiento
		    'request_created' => 'Request.created'
		);

		$events = $this->Event->find("all", array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
					array(
			            'table' => 'request_event',
			            'alias' => 'RequestEvent',
			            'type' => 'INNER',
			            'conditions' => array(
			                'RequestEvent.id = Event.request_event_id'
			            )
			        ),
					array(
			            'table' => 'request',
			            'alias' => 'Request',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Request.id = RequestEvent.request_id'
			            )
			        ),
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Request.group_id'
			            )
			        ),
			        array(
			            'table' => 'date',
			            'alias' => 'Date',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Date.event_id = Event.id'
			            )
			        ),
			        array(
			            'table' => 'medias',
			            'alias' => 'Media',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Media.id = Event.media_id'
			            )
			        ),
			        array(
			            'table' => 'state',
			            'alias' => 'State',
			            'type' => 'INNER',
			            'conditions' => array(
			                'State.id = Request.current_state_id'
			            )
			        )
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'Request.id','Request.amount','Request.created', 'Request.title',
			    	'Event.id','Event.amount','Event.media_id', 'Event.line', 'Event.description',
			    	'Media.id', 'Media.name',
			    	'State.id', 'State.name',
			    	'Date.id', 'Date.start', 'Date.end'
				),
        		'recursive'=>-1,
             	'order' => array('Event.request_created' => 'DESC')
      		));
			//$this->printWithFormat($events,true);
			$this->set(compact('events'));
	}

	//------------------------------------------------------------------
	public function availableBalancesReportExcel(){
		$this->layout=false;
		
		//$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$arrayCondition=array();
		$requestDateCondition = array();
		
		/*
		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('HistoricalBalance.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		*/
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Movement.created <='=> $endDate)));
			$requestDateCondition = array('Request.updated <= ' =>$endDate);
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}

		$currentCondition=array('AND' => array(array('Movement.type'=> "ABONO")));
		$arrayCondition[]=$currentCondition;

		$this->Paginator->settings = array(
            'Movement'=>array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Movement.group_id',
			            )
			        ),
			        /*array(
						'table' => 'request',
						'alias' => 'Request',
						'type' 	=> 'LEFT',
						'conditions' => array(
							'Request.group_id = Movement.group_id',
							'Request.current_state_id in (1,2,3)',
							$requestDateCondition,
						)
					)*/
				),
	         	'fields'=>array(
	         		'sum(Movement.amount) as sumAmount',
	         		'sum(Movement.nacional) as sumNational',
	         		//'sum(Request.amount) as sumPending',
			    	'Group.id', 'Group.name', 'Group.city',
				),
				'group' => array('Movement.group_id'),
        		'recursive'=>-1,
				'limit' => 1000000, 
             	'order' => array('Movement.created' => 'DESC')
				)
      		);

			$pending = $this->Request->find('all', array(
				'conditions' => array(
							'current_state_id in (1,2,3)',
							$requestDateCondition,
						),
				'fields' => array(
							'Request.group_id',
							'sum(Request.amount) as sumPending'
						),
				'group' => 'Request.group_id',
				'recursive' => -1,
				));

			$temp;

			foreach ($pending as $key => $value) {
				$temp[$value['Request']['group_id']] = $value[0]['sumPending'];
			}

			$pending = $temp;

			$balances=$this->Paginator->paginate('Movement');

			foreach ($balances as &$balance) {
				if(!empty($pending[$balance['Group']['id']])) 
					$balance[0]['sumPending'] = $pending[$balance['Group']['id']];
				else $balance[0]['sumPending'] = '0';
			}

			$this->set(compact('balances'));
	}

	//------------------------------------------------------------------
	public function movementsReportExcel(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$city=$this->request->query('city');
		$state=$this->request->query('state');

		$arrayCondition=array();
		
		$currentCondition=array("OR"=>array(
			array('Movement.type LIKE' => "%APROBADA%"),
			array('Movement.type LIKE' => "%RECHAZADA%"),
			array('Movement.type LIKE' => "%ABONO%"),
			)
		);

		/*$currentCondition=array(
			'Movement.type LIKE' => '%APROBADA%'
			//'Movement.type' => ""
			/*'NOT'=> array('Movement.type LIKE' => '%CERO%', 'Movement.type' => "")
		);*/
		
		$arrayCondition[]=$currentCondition;

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('Movement.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('Movement.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($city!='0'){
			$currentCondition=array('AND' => array(array('Group.city'=> $city)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('Movement.type'=> $state)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($this->isJWTFPR){
			$currentCondition=array('AND' => array(array('Group.group_type_id'=> GroupType::GROUP_TYPE_CONCESIONARIO)));
			$arrayCondition[]=$currentCondition;
		}

		$movements = $this->Movement->find("all", array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = Movement.group_id'
			            )
			        ),
				),
	         	'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'Movement.id', 'Movement.type', 'Movement.amount', 'Movement.created', 'Movement.request_id', 'Movement.balance_before',
				),
        		'recursive'=>-1,
             	'order' => array('Movement.created' => 'DESC')
				)  
      		);
			
			//$this->printWithFormat($movements,true);

			$this->set(compact('movements'));
	}

	//------------------------------------------------------------------
	public function nationalReportExcel(){

		$this->layout=false;

		$start=$this->request->query('start');
		$end=$this->request->query('end');
		$dealership=$this->request->query('dealership');
		$state=$this->request->query('state');

		$arrayCondition=array();

		if($start!=NULL && $start!=""){
			$startDate=date('Y-m-d H:i:s', strtotime($start."00:00:00"));
			$currentCondition=array('AND' => array(array('NationalSingleMovement.created >='=> $startDate)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($end!=NULL && $end!=""){
			$endDate=date('Y-m-d H:i:s', strtotime($end."23:59:59"));
			$currentCondition=array('AND' => array(array('NationalSingleMovement.created <='=> $endDate)));
			$arrayCondition[]=$currentCondition;
		}

		if($dealership!='0'){
			$currentCondition=array('AND' => array(array('Group.id'=> $dealership)));
			$arrayCondition[]=$currentCondition;
		}
		
		if($state!='0'){
			$currentCondition=array('AND' => array(array('NationalSingleMovement.type'=> $state)));
			$arrayCondition[]=$currentCondition;
		}

		$movements = $this->NationalSingleMovement->find("all", array(
            	'conditions' => $arrayCondition,
             	'joins' => array(
			        array(
			            'table' => 'group',
			            'alias' => 'Group',
			            'type' => 'INNER',
			            'conditions' => array(
			                'Group.id = NationalSingleMovement.group_id'
			            )
			        ),
			        array(
			            'table' => 'national_movement',
			            'alias' => 'NationalMovement',
			            'type' => 'INNER',
			            'conditions' => array(
			                'NationalMovement.id = NationalSingleMovement.national_movement_id'
			            )
			        ),
				),
				'fields'=>array(
			    	'Group.id', 'Group.name', 'Group.city',
			    	'NationalSingleMovement.id', 'NationalSingleMovement.national_movement_id', 'NationalSingleMovement.group_id', 'NationalSingleMovement.national_before', 'NationalSingleMovement.national', 'NationalSingleMovement.type','NationalSingleMovement.created', 'NationalMovement.title'
				),
        		'recursive'=>-1,
             	'order' => array('NationalSingleMovement.created' => 'DESC')
				)  
      		);
			
			//$this->printWithFormat($movements,true);

			$this->set(compact('movements'));
	}
}