<?php
class JsonHandler{
    private $RequestEvent;
    private $Request;
    public $Event;    

    public function inicializar($json){
        $json=json_decode($json);
        $this->RequestEvent= $json->RequestEvent;
        $this->Request= $json->Request;
        $this->Event = $json->Event;
    }
    public function getRequestEvent(){
        return $this->RequestEvent;
    }
    public function getRequest(){
        return $this->Request;
    }
    public function getEvents(){
        return $this->Event;
    }
    public function getMonth(){
        return date('n',strtotime($this->Request->created));        
    }
    public function getYear(){
        return date('Y',strtotime($this->Request->created));        
    }    
    
    public function getDayFromDate($date){
        return date('j',strtotime($date));                
    }
}

/*  $json='{"RequestEvent":{"id":"51","request_id":"221","city":"VALLEDUPAR","found":"Individual","objective":"Objetivo del plan"},"Request":{"id":"221","number":null,"request_type_id":"1","user_id":"65","group_id":"16","process_id":"1","title":"PLAYA DE vENTAS","amount":"5500000.00","date":"2015-08-19 21:56:26","current_state_id":"3","created":"2015-08-19 21:56:26","updated":"2015-08-19 22:33:58","approvers":[{"User":{"first_name":"Diana","last_name":"Forero","user_type_id":"3","signature":"\/var\/www\/fordpresupuesto\/app\/webroot\/images\/firmas\/465093603vinilo-decorativo-firma-michael-jackson-1858.png"},"RequestNote":{"note":"Aprobada. Validar actividad 2"}},{"User":{"first_name":"Diana","last_name":"Forero","user_type_id":"4","signature":"\/var\/www\/fordpresupuesto\/app\/webroot\/images\/firmas\/704588941350px-Freddie_Mercury_signature_2.svg.png"},"RequestNote":{"note":"Aprobada. Actividad 2 reivsada"}}],"group_name":"JANNA MOTORS","balance":"6.800.000,00","after":"1.300.000,00"},"Event":[{"id":"76","request_event_id":"51","line":"FUSION","activity":"Eventos","media":"BTL","amount":"5000000.00","description":"Observaciones evento - FUSION","Date":[{"start":"2015-09-09 21:56:26","end":"2015-09-09 21:56:26"},{"start":"2015-08-25 21:56:26","end":"2015-08-30 21:56:26"}]},{"id":"77","request_event_id":"51","line":"FOCUS","activity":"Digital","media":"Digital","amount":"500000.00","description":"DIGITAL","Date":[{"start":"2015-08-28 21:56:26","end":"2015-08-28 21:56:26"}]}]}';

$b = new JsonHandler();
$b->inicializar($json);
echo  $b->getRequest()->title;

foreach ($b->getEvents() as $event)
{
    echo 'evento '.$event->id;
    
}
*/
/*
$b = new JsonHandler();
$b->inicializar($json);
echo $b->getRequestEvent()->id;
*/
/*
$json='{"RequestEvent":{"id":"25","request_id":"25","city":"Caracas","found":"Individual","objective":""},
"Request":{"id":"25","number":null,"request_type_id":"1","user_id":"24","group_id":"10","process_id":"1","title":"Presupuesto V","amount":"100","date":null,"current_state_id":"5","created":"2015-08-06 16:48:05","updated":"2015-08-06 16:48:05"},
"Event":[{"id":"31","request_event_id":"25","line":"Uno","activity":"Radio","media":"Pauta en Medios","amount":"10","description":""},{"id":"32","request_event_id":"25","line":"Dos","activity":"Radio","media":"Publicidad Exterior","amount":"20","description":""},{"id":"33","request_event_id":"25","line":"Dos","activity":"Television","media":"Merchandising","amount":"30","description":""},{"id":"34","request_event_id":"25","line":"Dos","activity":"Merchandising","media":"Publicidad Exterior","amount":"40","description":""}]}';

$b = new JsonHandler();
$b->inicializar($json);
echo $b->getMonthFromDate();


$fecha='2015-08-06 16:48:05';
$fecha = strtotime($fecha);
//echo $fecha;
$fecha =date('j',$fecha);    

echo $fecha;

/*



$manejoJson = new JsonHandler();
$manejoJson->inicializar($json);
foreach ($manejoJson->getEventos() as $evento){
    echo 'id'.$evento->id;
    echo '<br>';
}
//print_r($manejoJson->getEventos());


/*
$json = '{
    "title": "JavaScript: The Definitive Guide",
    "author": "David Flanagan",
    "edition": 6
}';*/
//$json= json_encode($json);
/*
$json=json_decode($json);
$RequestEvent= $json->RequestEvent;
$Request= $json->Request;
$eventos = $json->Event;
*/

?>