<?php
require ('fpdf.php');
require ('JsonHandler.php');

class Reporte extends FPDF{
    public $font='Arial';
    
function Header(){
}

function draw($json){
    $obj = new JsonHandler();
    $obj->inicializar($json);
    
    $requestEvent=$obj->getRequestEvent();
    $request=$obj->getRequest();
    $event=$obj->getEvents();
    
    $count=0;
    $widht_separator=45;
    $this->AddPage('P','A4');
    $this->SetFont('Arial','',8);
    
    $this->Cell(0,10,'FORMATO DE SOLICITUD DE PRESUPUESTO ',0,0,'C');
    $this->Ln();
    $this->Cell(0,10,'FONDO DE PUBLICIDAD REGIONAL ',0,0,'C');
    
    
    $r=66;
    $g=80;
    $b=208;

    $this->Ln();
    $this->getLine($r,$g,$b);
    $this->Ln();

    
    
    $concY = $this->GetY();
    $this->getCampoSubtitulo("Estado de la solicitud",$r,$g,$b,'R',false);
    
    $this->Ln(3);

    $state = '';

    switch ($request->current_state_id) {
        case 1:
            $state = 'Creada';
            break;
        case 2:
            $state = 'Aprobada JWT';
            break;
        case 3:
            $state = 'Aprobada Ford';
            break;
        case 4:
            $state = 'Rechazada JWT';
            break;
        case 5:
            $state = 'Rechazada Ford';
            break;
        case 999:
            $state = 'Anulada Ford';
            break;
    }
    
    $this->getCampo($state,'R',false);

    $this->Ln(4);
    $this->SetY($concY);
    $this->getCampoSubtitulo(utf8_decode($request->group_name),$r,$g,$b,'L',false);
    $this->Ln(12);
    $this->getCampoNegrita('Ciudad:');
    $this->SetX(22);
    $this->getCampo(utf8_decode($requestEvent->city),'R',true);

    $this->SetX(72);
    $this->getCampoNegrita('Nombre de la Solicitud:');
    $this->SetX(106);
    $this->getCampo(utf8_decode($request->title),'L',true);
    
    $this->Ln(4);
    $this->getCampoNegrita(utf8_decode('Número de la Solicitud:'));
    $this->SetX(43);
    $this->getCampo(str_pad($request->id, 8, "0", STR_PAD_LEFT),'L',true);
    
    $this->Ln(4);
    $this->getCampoNegrita('Fecha de Solicitud:');
    $this->SetX(37);
    $this->getCampo(date('d/m/Y',strtotime($request->date)),'L',true);
    $this->getSpace(16);
    $varX=$this->GetY();
    $varY=$this->GetX();
    
    $this->SetX(72);
    $this->getCampoNegrita('Objetivo de la Solicitud:');
    $x1=$this->getX();
    $y1=$this->getY();
    
    $this->SetXY(106,$this->GetY()+3);
    
    $varX1=$this->GetY();
    $varY1=$this->GetX();

    $this->MultiCell(80,4,utf8_decode($requestEvent->objective),0);

    $cuadroY = $this->GetY();

    $this->SetY($varX+10);    
    $this->getCampoNegrita('Monto Total:');
    $this->SetX(28);
    $this->getCampo('$'.number_format((float)($request->amount), 0,',','.'),'L',true);

    if($this->GetY() < $cuadroY) $this->SetY($cuadroY);

    $this->Ln(3);    


    $r=68;
    $g=255;
    $b=0;
    
    $count=0;
    $act = 1;
    foreach ($event as $event){
        if ($count==2){
            $this->AddPage('P','A4');
            $this->SetFont('Arial','',8);
            $count=0;
        }
        $this->getActivity($event, $act);
    }

    $this->AddPage('P','A4');

    $this->getObservation($obj->getRequest());
    $this->Ln(15);    
    $this->getSignature($obj->getRequest());
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

        $this->Ln(4);
        
        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Nombre: '.utf8_decode($Request->approvers[0]->User->first_name.' '.$Request->approvers[0]->User->last_name),0,0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Nombre: '.utf8_decode($Request->approvers[1]->User->first_name.' '.$Request->approvers[1]->User->last_name),0,0,'B',false,null);

        $this->Ln(4);

        $this->Cell($val,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'JWT',0,0,'B',false,null);

        $this->Cell($val2,7,'',0,0,'R',false,null);
        $this->Cell($w,7,'Ford',0,0,'B',false,null);
    }

}    
function getObservation($Request){
$r=255;
$g=205;
$b=0;

$this->getLine($r,$g,$b);
$this->Ln();
    
$this->getCampoSubtitulo("OBSERVACIONES",$r,$g,$b,'l',false);
$this->Ln(8);

if(isset($Request->approvers[0]->RequestNote->note)) {
    $this->getCampoNegrita('Usuario JWT:');
    $this->Ln(8);
    $this->MultiCell(0,4,utf8_decode($Request->approvers[0]->RequestNote->note),0);
}
if(isset($Request->approvers[1]->RequestNote->note)) {
    $this->getCampoNegrita('Usuario Ford:');
    $this->Ln(8);
    $this->MultiCell(0,4,utf8_decode($Request->approvers[1]->RequestNote->note),0);
}
if(isset($Request->nullers[0]->RequestNote->note)) {
    $this->getCampoNegrita(utf8_decode('Usuario Ford (anulación):'));
    $this->Ln(8);
    $this->MultiCell(0,4,utf8_decode($Request->nullers[0]->RequestNote->note),0);
}

$this->Ln(5);
    
$this->getCampoNegrita('Saldo individual Actual: $'. $Request->balance);
$this->getSpace(67);
$this->getCampoNegrita('Saldo individual Final: $'. $Request->after);
$this->Ln(2);
$this->getLine($r,$g,$b);
    
}
    
function getActivity($event, &$in){
    
    $r=68;
    $g=255;
    $b=0;
    $this->getLine($r,$g,$b);
    $this->Ln();
    
    $this->getCampoSubtitulo("ACTIVIDAD ".$in,$r,$g,$b,'l',false);
    $this->Ln();
    
    $this->getCampoNegrita(utf8_decode('Línea de Vehículo: '));
    
    $X1=$this->getY();
    $Y1=$this->getX();
    
    $this->Ln(4);  

    if(substr($event->line,0,1)==' ') $event->line = substr($event->line,1);

    $descY = $this->GetY();

    $this->SetXY(36,$this->GetY()-1);
    $this->MultiCell(20,4,utf8_decode($event->line),0,'L');
    
    $this->setXY($this->getX(),$this->getY()-1);
    
    /*$this->getCampoNegrita('Tipo de Actividad:');
    $this->SetX(36);
    $this->getCampo(utf8_decode($event->activity),'L',true);
    $this->Ln(4);*/

    $this->getCampoNegrita('Medio:');
    $this->SetX(21);
    $this->getCampo(utf8_decode($event->media),'L',true);
    $this->Ln(4);

    $this->getCampoNegrita('Monto:');
    $this->SetX(21);
    $this->getCampo('$'.number_format((float)($event->amount), 0,',','.'),'L',true);
    $this->Ln(4);

    if(isset($event->Merchandising)){
        $this->getCampoNegrita('Productos:');
        $this->Ln(4);
        $this->getCampo('(Nombre - Precio Unitario - Cantidad)','L',true);
        $this->Ln(4);
        foreach ($event->Merchandising as $m){
            $this->setY($this->getY());
            $this->getCampo($m->name.' - $'.$m->price.' - '.$m->quantity.' unidades','L',true);                   
            $this->Ln(4);
        }
    }

    $fechasY = $this->GetY();

    $this->setXY(72,$descY-4);
    $this->getCampoNegrita(utf8_decode('Descripción:'));
    $this->SetXY(91,$this->GetY()+3);
    $this->MultiCell(90,4,utf8_decode($event->description),0);

    $altura = $this->GetY();

    $this->setY($fechasY+8);
    $this->getCampoNegrita(utf8_decode('Fechas de Ejecución:'));
    $this->Ln(4);
    
    if(isset($event->Date))
    foreach ($event->Date as $d){
        if($d->start == $d->end){
            $this->setY($this->getY());
            $this->getCampo(date('d/m/Y',strtotime($d->start)) ,'L',true);                   
            $this->Ln(4);
        }
        else{
            $this->setY($this->getY());
            $this->getCampo(date('d/m/Y',strtotime($d->start)).' - '.date('d/m/Y',strtotime($d->end)) ,'L',true);  
            $this->Ln(4);
        }                
    }

    if($altura < $this->GetY())  
    $altura = $this->GetY();

    $this->SetY($altura);

    $this->Ln(1);
    $r=68;
    $g=255;
    $b=0;
    
    $this->getLine($r,$g,$b);

    $in++;
}
    
function getMultiline($text){
    $this->MultiCell(70,4,$text,0);
}

function getLine($r,$g,$b){
    $this->SetDrawColor($r,$g,$b);
    $this->Cell(0,7,"",'B',0,'C',false,null);
}

function getSpace($space){
    $this->Cell($space,10,'',0,0,'L');     
}

function getCampo($descripcion,$orientation,$calWidth){
    if ($calWidth)
        $this->Cell(strlen($descripcion)+8,10,$descripcion,0,0,$orientation);
    else
        $this->Cell(0,10,$descripcion,0,0,$orientation);
}
   
    
function getCampoNegrita($descripcion){
    $this->SetFont('Arial','B','');    
    $this->Cell(strlen($descripcion),10,$descripcion,0,0,'L');
    $this->SetFont('');    
}
    
function getCampoSubtitulo($descripcion,$r,$g,$b,$orientation,$calWidth){
    $this->SetTextColor($r,$g,$b); 
    if ($calWidth)
        $this->Cell(strlen($descripcion)+8,10,$descripcion,0,0,$orientation);
    else
        $this->Cell(0,10,$descripcion,0,0,$orientation);
    
    $this->SetTextColor(12,12,12); 
}
   
function Footer(){
}
    
}
?>