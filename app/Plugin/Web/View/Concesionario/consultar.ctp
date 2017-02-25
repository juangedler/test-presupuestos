<div class="row">
    <div class="hpanel hblue">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-info pull-right">
                        $<span class="moneh"><?php echo $requestEvents['Request']['amount'];?></span>
                    </h4>
                    <br>
                    <br>
                    <h4><a href="#"><?php echo $requestEvents['Request']['title'];?></a></h4>

                    <p><u>Creador</u>: <?php echo $requestEvents['User']['User']['first_name'].' '.$requestEvents['User']['User']['last_name'];?></p>
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
    <?php
    $i=1;
    foreach($requestEvents['Event'] as $e){
?>

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
    
    <div class="hpanel hgreen">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <h4 class="text-success pull-right">
                        $<span class="moneh"><?php echo $e['amount'];?></span>
                    </h4>
                    <br>
                    <br>
                    <h4><a href="#"> Actividad <?php echo $i;?>: </a></h4>

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
		<div class="hpanel hblue">
		    <div class="panel-body">
		        <div class="row">
		        	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        		<h4><a href="#">Soportes de actividades</a></h4>
		        	</div>
                    <?php
                   // if($is_supportable):
                    ?>
		        	<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="margin-bottom: 15px;" >
		        		<div id="alertMessage" class="alert alert-dismissable alert-info" style="display: none">
		        			<button type="button" class="close alert-message" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <p id="messageText" class="error-text"></p>
					    </div>
					</div>
		        	<div id="supportsSection" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		        		<?php echo $this->element('list_supports_element', array('supports' => $supports, 'isDealership' => 1, 'is_supportable' => $is_supportable));?>
		    		</div>
                    <?php
                        if($is_supportable):
                    ?>
		    		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		    			<hr>
		    			<div class="row">
		    				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			    				<label>Agregar soportes de actividades:</label>
			    				<form id="formAddSupport" action="/web/concesionario/saveSupport" method="post" enctype="multipart/form-data">
				    				<input type="hidden" name="request-id" value="<?php echo($requestEvents['Request']['id'])?>" readonly="readonly"/>
				    				<input type="file" class="form-control" rows="2" id="support" name="support"></input>
				    				<br>
				    				<button type="submit" id="sendSupport" class="btn btn-success btn-sm pull-right" style="margin-right:5px; width:75px;">Agregar</button>
                                    <p style="font-size:10px;">Podrás subir cualquier tipo de archivo: JPG, PDF, PNG, MP3,MP4,.MOV,.MPG, etc<br>Deberás nombrar los archivos con el Número de la solicitud y la fecha del día que se cargan</p>

				    			</form>

			    			</div>
	    				</div>
		    		</div>	
		    		<div id="miniLoader" class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="display: none;">
		    			<img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">
		    		</div>
                    <?php
                        endif;
                    ?>
                    <?php
                    /*
                    else:
                    ?>
                    <div id="supportsSection" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p>Sección deshabilitada.</p>
                        <p>Contacte con su administrador en caso de que necesite habilitarla nuevamente.</p>
                    </div>
                    <?php
                    endif;
                    */
                    ?>
		    	</div>
			</div>
		</div>
		<?php echo $this->Html->script('Web.concesionarios.js');?>
	<?php }?>
</div>