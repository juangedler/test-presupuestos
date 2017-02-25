<?php
	$text1 = "Pautas Aprobadas";
	$text2 = "No posee pautas aprobadas";
	if($state == 20){
		$text1 = "Pautas Finalizadas";
		$text2 = "No posee pautas finalizadas";
	}

	if(count($requests) == 0){
?>
<div class="content">
	<div id="solicitudes_pendientes" class="row">
		<div class="col-lg-12">
			<div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3><?php echo $text1;?></h3>
				</div>
				<div class="panel-body">
					<?php echo $text2;?>
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
	<h3><?php echo $text1;?></h3>
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
	                    			echo 'Pauta: '.str_pad($r['Request']['id'], 8, "0", STR_PAD_LEFT).' - '.$r['Request']['title'].' - '.date('d/m/Y',strtotime($r['Request']['created'])).' - $'.number_format($r['Request']['amount'], 0,',','.');
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
							?>
							<br><br>
                		</div>
                		<div align="center">
            			<?php
            				if(isset($r['RequestFile']) && $r['RequestFile'] != NULL){
            			?>
                			<a class="btn btn-info" href="<?php echo Router::url('/') .$r['RequestFile'][0]['RequestFile']['file'];?>" target="_blank" style="width: 100px; height:34px; padding-top:0px;">Descargar<br><sup>(Excel)</sup></a>
                			<?php
                				if($state == 14){
                			?>
                			<a class="btn btn-success" href="<?php echo $this->Html->url('/web/agencia/nuevo_presupuesto/').$r['Request']['id']?>" style="width: 100px; height:34px; padding-top:0px;">Nuevo<br><sup>(Presupuesto)</sup></a>
                			<?php
                				}
                			?>
            			<?php 
            				}
            				if(count($r['RequestFile2']) > 0){
            			?>
                			<a class="btn btn-default presupuestos" href="#" value="<?php echo $r['Request']['id'];?>" style="width: 100px; height:34px; padding-top:0px;padding-left:5px;">Presupuestos<br><sup>&nbsp;&nbsp;(PDF)</sup></a>

                				<div class="versiones" id="presupuestos<?php echo $r['Request']['id'];?>" hidden>
	                				<hr>
	                				<?php
	                					for($i = 0; $i < count($r['RequestFile2']); $i++){
	                				?>
	                					<div class="col-sm-5" align="left"><?php echo $r['RequestFile2'][$i]['RequestFile']['title'].' - $'.$r['RequestFile2'][$i]['RequestFile']['amount'].' - '.date('d/m/Y',strtotime($r['RequestFile2'][$i]['RequestFile']['created']));?></div>
	                					<div class="col-sm-4">
	                						<?php 
	                							switch ($r['RequestFile2'][$i]['RequestFile']['status']) {
	                							 	case 1:
	                							 		echo 'Sin Revisión'; break;
	                							 	case 3:
	                							 		echo 'Firmado Director'; break;
	                							 	case 4:
	                							 		echo 'Firmado Gerente'; break;
	                							 	case 5:
	                							 		echo 'Firmado Director y Gerente'; break;
                							 		case 6:
	                							 		echo 'Aprobado Gerente de Comunicaciones'; break;
	                							 	case 7:
	                							 		echo 'Rechazado Gerente de Comunicaciones'; break;
	                							 	case 8:
	                							 		echo 'Requiere Modificación <a class="vercom pe-7s-search" style="color:blue;" data-toggle="modal" data-target="#myModal1" data-href="'.$r['RequestFile2'][$i]['RequestFileNote']['RequestFileNote']['note'].'"></a>'; break;
	                							 	case 9:
	                							 		echo 'Aprobado Gerente de Mercadeo'; break;
	                							 	case 10:
	                							 		echo 'Rechazado Gerente de Mercadeo'; break;
	                							 }
	                						?>
	                					</div>
	                					<div class="col-sm-3" align="right">
	                						<a class="" href="<?php echo Router::url('/') .$r['RequestFile2'][$i]['RequestFile']['file'];?>" target="_blank" style="width: 100px;">Descargar</a>
	                						<a class="" href="<?php echo $this->Html->url('/web/agencia/modificar_presupuesto/').$r['RequestFile2'][$i]['RequestFile']['id'];?>" style="width: 100px;">Modificar</a>
	                					</div>
	                					<div class="col-sm-12"></div>
	                				<?php
	                					}
	                				?>
									<div class="col-sm-12"><hr></div>
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

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div class="obs">
            	</div>
            </div>
        </div>
    </div>
</div>

<script>
	$('.presupuestos').on('click',function(){
		$('#presupuestos'+$(this).attr('value')).fadeIn(0);
	});

	$('.collapsed').on('click',function(){
		$('.versiones').fadeOut(0);
	});

	$('#myModal1').on('show.bs.modal', function(e) {
	    $(this).find('.obs').html($(e.relatedTarget).data('href'));
	});
</script>

<?php
	}
?>