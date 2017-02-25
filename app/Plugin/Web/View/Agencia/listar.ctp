<?php
	if(count($requests) == 0){
?>
<div class="content">
	<div id="solicitudes_pendientes" class="row">
		<div class="col-lg-12">
			<div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3>Solicitudes <?php echo $title;?></h3>
				</div>
				<div class="panel-body">
					No posee Solicitudes <?php echo $title;?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
	}
	else {
?>

<div class="content">
<div id="solicitudes_pendientes" class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3>Solicitudes <?php echo $title;?></h3>
</div>
<div class="panel-body">
	<br>
    <div class="groups index col-sm-12">
    	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    	<?php
    		foreach ($requests as $r) {
    	?>
    		<div class="panel panel-default">
	            <div class="panel-heading" role="tab" id="headingThree">
	                <h4 class="panel-title">
	                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $r['Request']['id'];?>" aria-expanded="false" aria-controls="collapseFive">
		                    <?php
		                    	if($r['Request']['request_type_id'] == 2)
		                    		echo 'Solicitud: '.str_pad($r['Request']['id'], 8, "0", STR_PAD_LEFT).' - Tango ID: '.$r['Request']['number'].' - '.date('d/m/Y',strtotime($r['Request']['created'])).' - $'.number_format($r['Request']['amount'], 0,',','.');
		                    	else if($r['Request']['request_type_id'] == 3)
	                    			echo 'Pauta: '.str_pad($r['Request']['id'], 8, "0", STR_PAD_LEFT).' - '.date('d/m/Y',strtotime($r['Request']['created'])).' - $'.number_format($r['Request']['amount'], 0,',','.');
		                    	if(in_array($r['Request']['current_state_id'],array(7,13))) {
		                    ?>
		                    	<i class="pe-7s-timer pull-right" style="color:blue;font-size: 20px;margin-top: -4px;" title="Esperando Aprobación de la Gerencia de Mercadeo Ford"></i>
		                    <?php
		                    	}
		                    	else if(in_array($r['Request']['current_state_id'], array(11,17))) {
		                    ?>
		                    	<i class="pe-7s-pen pull-right" style="color:#FF7500;font-size: 20px;margin-top: -4px;" title="Requiere Modificación por parte del Creador"></i>
		                    <?php
		                    	}
		                    ?>
	                    </a>
	                </h4>
	            </div>
	            <div id="collapse<?php echo $r['Request']['id'];?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
	                <div class="panel-body">
	                	<div class="col-sm-12">
	                		<?php 
								echo 	'Creador: '.
										$r['User']['first_name'].' '.$r['User']['last_name'].'<br>
									  	Nombre: '.$r['Request']['title'].'<br>';
                				if(isset($r['RequestFile']) && $r['RequestFile'] != NULL)
									echo 'Descripción: '.
										$r['RequestFile'][0]['RequestFile']['description'].'<br>
										<sup>'.date('d/m/Y',strtotime($r['RequestFile'][0]['RequestFile']['created'])).'</sup>';
								else if(isset($r['RequestFile2']) && $r['RequestFile2'] != NULL)
									echo  'Descripción: '.$r['RequestFile2'][0]['RequestFile']['description'].'<br><sup>'.date('d/m/Y',strtotime($r['RequestFile2'][0]['RequestFile']['created'])).'</sup>';
								if(in_array($r['Request']['current_state_id'], array(11,17)))
									echo '<br><u>Observaciones Gerencia de Comunicaciones de Ford</u>: '.
										  $r['RequestNote'][0]['RequestNote']['note'];
								else if(in_array($r['Request']['current_state_id'],array(7,9,13,15)))
									echo '<br><u>Observaciones Gerencia de Comunicaciones de Ford</u>: '.
										  $r['RequestNote'][0]['RequestNote']['note'];
								else if(in_array($r['Request']['current_state_id'], array(8,10,14,16)))
									echo '<br><u>Observaciones Gerencia de Comunicaciones de Ford</u>: '.
										  $r['RequestNote'][0]['RequestNote']['note'].'
										  <br><u>Observaciones Gerencia de Mercadeo de Ford</u>: '.
										  $r['RequestNote'][1]['RequestNote']['note'];
							?>
							<br><br>
                		</div>
                		<div align="center">
            			<?php
            				if(in_array($r['Request']['current_state_id'], array(6,11))){
            			?>
                			<a class="btn btn-primary" href="<?php echo $this->Html->url('modificar').'/'.$r['Request']['id'];?>" style="width: 100px;">Modificar</a>
            			<?php
            				}
            				if(in_array($r['Request']['current_state_id'], array(12,17))){
            			?>
                			<a class="btn btn-primary" href="<?php echo $this->Html->url('modificar_pauta').'/'.$r['Request']['id'];?>" style="width: 100px;">Modificar</a>
            			<?php
            				}
            				if(isset($r['RequestFile']) && $r['RequestFile'] != NULL){
            			?>
                			<a class="btn btn-info" href="<?php echo Router::url('/') .$r['RequestFile'][0]['RequestFile']['file'];?>" target="_blank" style="width: 100px; height:34px; padding-top:0px;">Descargar<br><sup>(Tango)</sup></a>
            			<?php 
            				}
            				if(isset($r['RequestFile2']) && $r['RequestFile2'] != NULL){
            			?>
                			<a class="btn btn-info" href="<?php echo Router::url('/') .$r['RequestFile2'][0]['RequestFile']['file'];?>" target="_blank" style="width: 100px; height:34px; padding-top:0px;">Descargar<br><sup>(Pauta Medios)</sup></a>
            			<?php 
            				}
            				if(count($r['RequestFile']) > 1){
            			?>
                			<a class="btn btn-default anteriores" href="#" value="<?php echo $r['Request']['id'];?>" style="width: 100px; height:34px; padding-top:0px;">Anteriores<br><sup>(Tango)</sup></a>
                				<div class="versiones" id="anteriores<?php echo $r['Request']['id'];?>" hidden>
	                				<hr>
	                				<?php
	                					for($i = 1; $i < count($r['RequestFile']); $i++){
	                				?>
	                					<div class="col-sm-6" align="left">Tango ID: <?php echo $r['RequestFile'][$i]['RequestFile']['number'].' - '.date('d/m/Y',strtotime($r['RequestFile'][$i]['RequestFile']['created']));?></div>
	                					<div class="col-sm-6" align="right"><a class="" href="<?php echo Router::url('/') .$r['RequestFile'][$i]['RequestFile']['file'];?>" target="_blank" style="width: 100px;">Descargar</a></div>
	                				<?php
	                					}
	                				?>
	                			</div>
                			<?php
                				}
                				if(count($r['RequestFile2']) > 1){
                			?>
                			<a class="btn btn-default anteriores" href="#" value="<?php echo $r['Request']['id'];?>" style="width: 100px; height:34px; padding-top:0px;">Anteriores<br><sup>(Pauta)</sup></a>
                				<div class="versiones" id="anteriores<?php echo $r['Request']['id'];?>" hidden>
	                				<hr>
	                				<?php
	                					for($i = 1; $i < count($r['RequestFile2']); $i++){
	                				?>
	                					<div class="col-sm-6" align="left">Pauta ID: <?php echo $r['RequestFile2'][$i]['RequestFile']['number'].' - '.date('d/m/Y',strtotime($r['RequestFile2'][$i]['RequestFile']['created']));?></div>
	                					<div class="col-sm-6" align="right"><a class="" href="<?php echo Router::url('/') .$r['RequestFile2'][$i]['RequestFile']['file'];?>" target="_blank" style="width: 100px;">Descargar</a></div>
	                				<?php
	                					}
	                				?>
	                			</div>
                			<?php
                				}
                			?>
                		</div>
	                </div>
	            </div>
	        </div>
    	<?php
    		}
    	?>
    	</div>
	</div>
</div>
</div>
</div>
</div>
</div>

<script>
	$('.anteriores').on('click',function(){
		$('#anteriores'+$(this).attr('value')).fadeIn(0);
	});

	$('.collapsed').on('click',function(){
		$('.versiones').fadeOut(0);
	});
</script>

<?php
	}
?>