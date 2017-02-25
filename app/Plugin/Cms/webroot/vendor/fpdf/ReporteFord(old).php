<?php
require ('fpdf.php');
require ('JsonHandler.php');

class Reporte extends FPDF{
    public $font='Arial';
    
function Header(){
}
    
function Footer(){
}
    
public function draw($json){
    $jsonHandler = new JsonHandler();
    $jsonHandler->inicializar($json);
    
    $RequestEvent=$jsonHandler->getRequestEvent();
    $Request=$jsonHandler->getRequest();
    $Event=$jsonHandler->getEvents();
    
    $this->AddPage('L','A3');
    $font='Arial';
    $this->SetFont($this->font,'',11);
    
    $this->Cell(14);
    $this->Cell(0,10,'Solicitud  de Aprobacion ',0,0,'C');    
    
    $this->getBasicInfo($RequestEvent,$Request);
    $this->Ln(10);
    
    $month=$jsonHandler->getMonth();
    $year= $jsonHandler->getYear();
    
    $this->getActivities($month,$year,$Event);
    $this->Ln(10);
    $this->getDownSide($Request);
    $this->Ln(20);
    $this->getSignature($Request);
}
    
public function getSignature($Request){
    if(count($Request->approvers) == 1){
        $w=50;
        $h=25;
        $val = ($this->w*(1/1.8))-$w;
        
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Image($Request->approvers[0]->User->signature,null,null,$w,$h);
        $this->Cell($w,7,'','B',0,'B',false,null);
        $this->Ln();
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Persona autoriza',0,0,'B',false,null);
        $this->Ln();
        
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Nombre: '.utf8_decode($Request->approvers[0]->User->first_name.' '.$Request->approvers[0]->User->last_name),0,0,'B',false,null);
        $this->Ln();
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'JWT',0,0,'B',false,null);
        
        $this->Ln();
        
        $this->Cell($val,7,'',0,0,'R',false,null);
    }
    else if(count($Request->approvers) == 2){
        $w=50;
        $h=25;
        $val = ($this->w*(1/3))-$w;
        $val2 = ($this->w*(1/2.5))-$w;

        $y = $this->GetY();
        
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Image($Request->approvers[0]->User->signature,null,$y,$w,$h);
        $this->Cell($w,25,'','B',0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Image($Request->approvers[1]->User->signature,null,$y,$w,$h);
        $this->Cell($w,25,'','B',0,'B',false,null);


        $this->Ln();

        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Persona autoriza',0,0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Persona autoriza',0,0,'B',false,null);

        $this->Ln();
        
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Nombre: '.utf8_decode($Request->approvers[0]->User->first_name.' '.$Request->approvers[0]->User->last_name),0,0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Nombre: '.utf8_decode($Request->approvers[1]->User->first_name.' '.$Request->approvers[1]->User->last_name),0,0,'B',false,null);

        $this->Ln();

        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'JWT',0,0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Ford',0,0,'B',false,null);
        
        $this->Ln();
        
        $this->Cell($val,7,'',0,0,'R',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
    }

}
    
public function getDownSide($Request){
    $this->setColorCell(124,140,176);
    $this->Cell(135,7,"Nro. de Solicitud: ".str_pad($Request->id, 8, "0", STR_PAD_LEFT),1,0,'L',true,null);
    $this->unSetColorCell();    
    $this->Ln();
    //$this->Cell(135,7,"SALDO REGIONAL ACTUAL: ",1,0,'L',false,null);
  //  $this->Ln();
    $this->Cell(135,7,"SALDO INDIVIDUAL ACTUAL: ".$Request->balance,1,0,'L',false,null);    
    $this->Ln();
//    $this->Cell(135,7,"APROBADO FORD: ",1,0,'L',false,null);    
   // $this->Ln();
  //  $this->Ln();
    
    $this->setColorCell(124,140,176);
  // $this->Cell(135,7,"SALDO REGIONAL FINAL: ",1,0,'L',true,null);    
   // $this->Ln();
    $this->Cell(135,7,"SALDO INDIVIDUAL FINAL: ".$Request->after,1,0,'L',true,null);    
   // $this->Ln();
    $this->unSetColorCell();    
}
    
public function getBasicInfo($RequestEvent,$Request){
    $fondo=false;
    $width1=200;
    $width2=60;
    $widthDayBox=10;
    $visible=1;
    
    $this->Ln();
    $this->Cell($width2,7,"Fecha de Solicitud: ",0,0,'L',$fondo,null);
    $this->Cell($width1,7,date('d/m/Y',strtotime($Request->date)),$visible,0,'L',$fondo,null);
    
    $this->Ln();
    $this->Cell($width2,7,"Concesionario: ",0,0,'L',$fondo,null);
    $this->Cell($width1,7,utf8_decode($Request->group_name),$visible,0,'L',$fondo,null);
    
    $this->Ln();
    $this->Cell($width2,7,"Ciudad: ",0,0,'L',$fondo,null);
    $this->Cell($width1,7,utf8_decode($RequestEvent->city),$visible,0,'L',$fondo,null);
    
    $this->Ln();
    $this->Cell($width2,7,"Objetivo del Plan: ",0,0,'L',$fondo,null);
    $this->Cell($width1,7,utf8_decode($RequestEvent->objective),$visible,0,'L',$fondo,null);
    

    $this->Ln();
    $this->Cell($width2,7,"FONDO: ",0,0,'L',$fondo,null);
    $this->Cell($width1,7,$RequestEvent->found,$visible,0,'L',$fondo,null);
    
    //$this->Ln();
   // $this->Cell($width2,7,"DETALLE ACTIVIDAD: ",0,0,'L',$fondo,null);
//    $this->Cell($width1*3,7*4,$Request->detalle,$visible,0,'L',$fondo,null);
   // $this->MultiCell(0,6,$RequestEvent->objective,1);
}
    
public function getActivities($month,$year,$event){
    $fondo=false;
    $visible=0;
    $width=6;
    $height=4;
    
    $daysCount=date('t', strtotime($year.'-'.$month.'-01'));
    $mes = $this->getMonthName($month);
    
    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    
    $visible=1;
    
    
    $this->Cell($daysCount*($width),7,ucfirst($mes).' '.$year,$visible,0,'C',$fondo,null);
    $this->Ln();

    $visible=0;

    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    $this->Cell(45,7,"",$visible,0,'C',$fondo,null);
    $visible=1;
    
    for($i=1;$i<=$daysCount;$i++){
        $day=$this->getDayWeek($i,$month,$year);
        $day=substr($day,0,1);
        /*lunes/martes/miercoles...*/
        $this->Cell($width,$height,$day,$visible,0,'C',$fondo,null);
    }
        $this->Ln();
    
    
    $titulo1="LINEA DE VEHICULO";
    $this->Cell(45,7,$titulo1,$visible,0,'C',$fondo,null);
    
    $titulo2="ACTIVIDAD";
    $this->Cell(45,7,$titulo2,$visible,0,'C',$fondo,null);

    $titulo3="MEDIO";
    $this->Cell(45,7,$titulo3,$visible,0,'C',$fondo,null);

    $visible=1;
    for($i=1;$i<=$daysCount;$i++){
        /*12345678910*/
      //$this->Cell($width,7,$i,$visible,0,'L',$fondo,null);
        //$this->Cell($width,7,$i,$visible,0,'L',$fondo,null);
        $this->Cell($width,7,$i,$visible,0,'L',$fondo,null);
    }
    
        $widthColMonto=30;
        $widthObservaciones=50;
    
        $this->Cell($widthColMonto,7,"Monto (COP$)",$visible,0,'C',$fondo,null);
        $this->Cell($widthObservaciones,7,"Observaciones",$visible,0,'C',$fondo,null);
        $this->Ln();

    
    //for($j=1;$j<$row;$j++)
    
        $amount=0;
        $y = 76;
        foreach ($event as $event)
        {

            $this->Cell(45,7,utf8_decode($event->line),$visible,0,'L',$fondo,null);
            $this->Cell(45,7,utf8_decode($event->activity),$visible,0,'L',$fondo,null);            
            $this->Cell(45,7,utf8_decode($event->media),$visible,0,'L',$fondo,null);            
            $this->setColorCell(41,88,199);
            
            $amount+=$event->amount;
            /*se llenan las celdas de acuerdo a la fecha de su evento.*/

            if(isset($event->Date))
            foreach($event->Date as $d){
                $dayStart=$this->getDayFromDate($d->start);
                $dayEnd=$this->getDayFromDate($d->end);
                $x = 145;
                for($i=1;$i<=$daysCount;$i++){
                    if ($i==$dayStart || $i>=$dayStart && $i<=$dayEnd){
                            $this->SetXY($x,$y);
                            $this->Cell($width,7,'',$visible,0,'C',true,null);  
                    }
                    else{
                            $this->SetXY($x,$y);
                            $this->Cell($width,7,'',$visible,0,'C',false,null);  
                    }
                    $x+=6;
                }
            }
            else
            for($i=1;$i<=$daysCount;$i++){
                $this->Cell($width,7,'',$visible,0,'C',false,null);  
            }

            $this->unSetColorCell();
            $this->Cell($widthColMonto,7,number_format((float)($event->amount), 2,',','.'),$visible,0,'R',false,null);
            $this->MultiCell($widthObservaciones,7,utf8_decode($event->description),1);
            //$this->Cell($widthObservaciones,7,$event->description,$visible,0,'L',false,null);        
            $this->Ln(0);
            $y+=7;
        }
            $amount = number_format((float)($amount), 2,',','.');
            $width=45*3+$width*26;
            
            $this->Cell($width,7,'',0,0,'L',false,null);
        
            $width=6*($daysCount-26);
            $this->Cell($width,7,'Total: ',$visible,0,'L',false,null);
    
            
            $this->Cell($widthColMonto,7,$amount,$visible,0,'R',false,null);
    
    
    
}
    
public function setColorCell($r,$g,$b){
          $this->SetFont('Arial','',11);
          $this->SetFillColor($r,$g,$b);
}
public function unSetColorCell(){
          $this->SetFont('Arial','',11);
          $this->SetFillColor(255,255,255);
}
    
public function getDaysTable($month,$year,$row){
    $visible=1;
    $daysCount=date('t', strtotime($year.'-'.$month.'-01'));
    $mes = $this->getMonthName($month);
    
    $this->Cell($daysCount*(6),7,ucfirst($mes),$visible,0,'C',$fondo,null);
    $this->Ln();
    
    for($i=1;$i<=$daysCount;$i++){
        $day=$this->getDayWeek($i,$month,$year);
        $day=substr($day,0,1);
        $this->Cell(6,7,$day,$visible,0,'C',$fondo,null);
    }
    $this->Cell(30,7,"",$visible,0,'C',$fondo,null);
    $this->Cell(30,7,"",$visible,0,'C',$fondo,null);
    $this->Ln();
    for($j=1;$j<=$daysCount;$j++){
        $this->Cell(6,7,$j,$visible,0,'C',$fondo,null);
    }
        $this->Cell(30,7,"Monto estimado",$visible,0,'C',$fondo,null);
        $this->Cell(30,7,"Observaciones",$visible,0,'C',$fondo,null);
    
    for($i=1;$i<=$row;$i++){
        $this->Ln();
        for($j=1;$j<=$daysCount;$j++){
            $this->Cell(6,7,"",$visible,0,'C',$fondo,null);
        }
            $this->Cell(30,7,"",$visible,0,'C',$fondo,null);
            $this->Cell(30,7,"",$visible,0,'C',$fondo,null);
    }
            $this->Ln();
            $visible=0;
    
            $this->Cell($daysCount*6,7,"",$visible,0,'C',$fondo,null);
            $visible=1;
            $this->Cell(30,7,"Total:",$visible,0,'C',$fondo,null);
            $this->Cell(30,7,"$",$visible,0,'R',$fondo,null);
}
    
public function getMonthName($month){
    setlocale(LC_ALL,"es_ES");
    $string = "01/$month/0000";
    $date = DateTime::createFromFormat("d/m/Y", $string);
    $mes= strftime("%B",$date->getTimestamp());
    return $mes;
}
    
public function getDayWeek($dia,$mes,$ano){
    setlocale(LC_ALL,"es_ES"); 
    $timestamp = strtotime($dia.'-'.$mes.'-'.$ano);
    $dw = date("l", $timestamp);
    return ucfirst((strftime("%A",$timestamp )));
}    

public function getDayFromDate($fecha){
    //$d='2015-08-07 16:48:13';
    $timestamp = strtotime($fecha);
    $d=date('j',$timestamp);
    return $d;
}
    
}
/*$json='
{
"RequestEvent":{"id":"25","request_id":"25","city":"Caracas","found":"Individual","objective":"objectivo"},
"Request":{"id":"25","number":null,"request_type_id":"1","user_id":"24","group_id":"10","process_id":"1","title":"Presupuesto V","amount":"100","date":"2015-08-06","current_state_id":"5","created":"2015-08-06 16:48:05","updated":"2015-08-06 16:48:05",
"detalle":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."
},
"Event":[
{"id":"31","request_event_id":"25","line":"Fiat","activity":"Radio","media":"Pauta en Medios","amount":"10","description":"Lorem ipsum dolor sit    .",
"start":"2015-08-12 16:48:12","end":"2015-08-14 16:48:12"
},
{"id":"32","request_event_id":"25","line":"BWM","activity":"Radio","media":"Publicidad Exterior","amount":"20","description":"Todo o nada",
"start":"2015-08-12 16:48:12","end":"2015-08-14 16:48:12"
},
{"id":"33","request_event_id":"25","line":"Mercedez","activity":"Television","media":"Merchandising","amount":"30","description":"Match point"
,"start":"2015-08-13 16:48:13","end":"2015-08-13 16:48:13"
},
{"id":"34","request_event_id":"25","line":"c","activity":"Merchandising","media":"Publicidad Exterior","amount":"40","description":""
,"start":"2015-08-07 16:48:13","end":"2015-08-13 16:48:13"
}
]}';
    


$pdf = new Reporte();
$pdf->draw($json);
$pdf->Output();*/
?>
