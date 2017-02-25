<?php
class JsonHandler{
    private $RequestEvent;
    private $Request;
    private $eventos;    

    public function inicializar($json){
        $json=json_decode($json);
        $this->RequestEvent= $json->RequestEvent;
        $this->Request= $json->Request;
        $this->eventos = $json->Event;
    }
    public function getRequestEvent(){
        return $this->RequestEvent;
    }
    public function getRequest(){
        return $this->Request;
    }
    public function getEvents(){
        return $this->eventos;
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
//$b = new JsonHandler();
//$b->inicializar($json);
//echo $b->getDayFromDate('2015-08-16 16:48:05');

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