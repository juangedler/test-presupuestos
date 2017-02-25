<div class="row">
    <div class="hpanel hblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-info pull-right">
                        &nbsp;$<span class="moneh"><?php echo $requestEvents['Request']['amount'];?></span>
                    </h4>
                    <br>
                    <br>
                    <h4><?php echo $requestEvents['Request']['title'];?></h4>

                    <p><u>Descripción</u>: <?php echo $requestEvents['RequestEvent']['objective'];?></p>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="project-label">CIUDAD</div>
                            <small><?php echo $requestEvents['RequestEvent']['city'];?></small>
                        </div>
                        <div class="col-sm-4">
                            <div class="project-label">FONDO</div>
                            <small><?php echo $requestEvents['RequestEvent']['found'];?></small>
                        </div>
                        <div class="col-sm-5">
                            <div class="project-label">FECHA DE CREACIÓN</div>
                                <small><?php echo date('d/m/Y',strtotime($requestEvents['Request']['date']));?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hpanel hyellow">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Observaciones</h4>
                    <?php
                        if(isset($requestEvents['Note'][0]) && count($requestEvents['Note'][0]) != 0)
                            echo '<p><u>JWT</u>: '.
                                $requestEvents['Note'][0]['RequestNote']['note'].'<br><sup>('.
                                $requestEvents['Note'][0]['User']['first_name'].' '.
                                $requestEvents['Note'][0]['User']['last_name'].' - '.
                                date('d/m/Y',strtotime($requestEvents['Note'][0]['RequestNote']['created'])).')</sup></p>';
                        if(isset($requestEvents['Note'][1]) && count($requestEvents['Note'][1]) != 0)
                            echo '<p><u>Ford</u>: '.
                                $requestEvents['Note'][1]['RequestNote']['note'].'<br><sup>('.
                                $requestEvents['Note'][1]['User']['first_name'].' '.
                                $requestEvents['Note'][1]['User']['last_name'].' - '.
                                date('d/m/Y',strtotime($requestEvents['Note'][1]['RequestNote']['created'])).')</sup></p>';
                        if(isset($requestEvents['MotivoAnulacion'][0]) && count($requestEvents['MotivoAnulacion'][0]) != 0)
                            echo '<p><u>Ford (anulación)</u>: '.
                                $requestEvents['MotivoAnulacion'][0]['RequestNote']['note'].'<br><sup>('.
                                $requestEvents['MotivoAnulacion'][0]['User']['first_name'].' '.
                                $requestEvents['MotivoAnulacion'][0]['User']['last_name'].' - '.
                                date('d/m/Y',strtotime($requestEvents['MotivoAnulacion'][0]['RequestNote']['created'])).')</sup></p>';
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php
        $i=1;
        foreach($requestEvents['Event'] as $e){
    ?>
    <div class="hpanel hgreen">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success pull-right">
                        $<span class="moneh"><?php echo $e['amount'];?></span>
                    </h4>
                    <br>
                    <br>
                    <h4>Actividad <?php echo $i;?></h4>

                    <p><?php echo $e['line']?></p>
                    <p><u>Descripción</u>: <?php echo $e['description']?></p>

                    <div class="row">
                        <!--<div class="col-sm-3">
                            <div class="project-label">ACTIVIDAD</div>
                            <small><?php echo $e['activity'];?></small>
                        </div>-->
                        <div class="col-sm-6">
                            <div class="project-label">MEDIO</div>
                            <small><?php echo ($mediasArray[$e['media_id']]);?></small>
                        </div>
                        <div class="col-sm-6">
                            <div class="project-label">FECHAS DE EJECUCIÓN</div>
                            <?php
                                if(isset($e['Date']))
                                foreach ($e['Date'] as $d) {
                            ?>
                                <small>
                                <?php 
                                    echo date('d/m/Y',strtotime($d['start']));
                                    if($d['start'] != $d['end']) echo ' - '.date('d/m/Y',strtotime($d['end']));
                                    echo '<br>';
                                ?>
                                </small>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <?php
                        if(isset($e['Merchandising']) && count($e['Merchandising']) > 0){
                    ?>
                    <div class="row">
                    <div class="col-sm-12">
                    <br>PRODUCTOS <small>(Nombre - Precio Unitario - Cantidad)</small><br>
                    <small>
                    <?php
                        foreach ($e['Merchandising'] as $m) {  
                            echo $m['name'] .' - $'.$m['price'].' - '.$m['quantity'].'<br>';
                        }
                    ?> 
                    </small>
                    </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php
$i++;
}
?>
	<?php if($requestEvents['Request']['current_state_id']==State::STATE_APROBADO_FORD){ ?>
		<?php if(count($requestEvents['Request']['supports'])>0){?>
			<div class="hpanel hblue">
			    <div class="panel-body">
			        <div class="row">
			        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h4><a href="#">Soportes de actividades</a></h4>
			        	</div>
			        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			        		<?php echo $this->element('list_supports_element', array('supports' => $requestEvents['Request']['supports'],'isDealership' => 1, 'is_supportable' => 1));?>
			        		<!--<?php foreach ($requestEvents['Request']['supports'] as $keySupport => $support) {?>
			        			<div id="support-<?php echo($support['RequestSupport']['id']);?>" class="row" style="margin-bottom: 2px;">
			    					<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
			    						<span><?php echo($support['RequestSupport']['file'])?></span>
			    					</div>
			    					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
			    						<a class="viewSupport special-a" href="/files/soportes/<?php echo($support['RequestSupport']['file']);?>" target="blank">Ver</a>
			    					</div>
			        			</div>
							<?php } ?>-->
			    		</div>
			    	</div>
				</div>
			</div>
		<?php } ?>
	<?php }?>
</div>
<?php echo $this->Html->script('Web.concesionarios.js');?>
