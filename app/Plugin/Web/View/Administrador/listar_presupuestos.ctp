<?php
	switch ($option) {
		case 5:
			$title = __('Presupuestos Pendientes');
			break;
		case 6:
			$title = __('Presupuestos Aprobados');
			break;
		case 7:
			$title = __('Presupuestos Rechazados');
			break;
		case 9:
			$title = __('Presupuestos Aprobados Finales');
			break;
		case 10:
			$title = __('Presupuestos Rechazados Finales');
			break;
	}
?>

<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />

<div class="content">

	<div id="solicitudes_pendientes" class="row">
	    <div class="col-lg-12">
	        <div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3><?php echo $title;?></h3>
	            </div>
	            <div class="panel-body">
	            	<div class="col-sm-3">
	                	<div class="input-group date">
	                		<input class="form-control" placeholder="Fecha Desde" type="text" id="desde" name="desde">
	                		<span class="input-group-addon"><i class="pe-7s-date"></i></span>
	                	</div>
	                </div>
	                <div class="col-sm-3">
	                	<div class="input-group date">
		                	<input class="form-control" placeholder="Fecha Hasta" type="text" id="hasta" name="hasta">
		                	<span class="input-group-addon"><i class="pe-7s-date"></i></span>
	                	</div>
	                </div>

	                <div class="col-sm-12"><br></div>
						<table id ="table" class="table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th>#Documento</th>
								<th>Nombre</th>
								<th>Fecha</th>
								<th style="text-align: right;">Monto</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="table_body">
							<?php
								foreach($requests as $r){
									echo '<tr name="'.$r['RequestFile']['id'].'">';
									echo '<td>'.str_pad($r['RequestFile']['id'], 8, "0", STR_PAD_LEFT).'</td>';
									echo '<td>'.str_pad($r['RequestFile']['number'], 8, "0", STR_PAD_LEFT).'</td>';
									echo '<td id="group_name">'.$r['RequestFile']['title'].'</td>';
									echo '<td>'.date('d/m/Y',strtotime($r['RequestFile']['created'])).'</td>';
									echo '<td align="right">$<span class="money">'.$r['RequestFile']['amount'].'</span></td>';
									echo '<td align="center"><a id="consultar" class="request_id pe-7s-search" title="Ver detalle" value="'.$r['RequestFile']['id'].'" data-toggle="modal" data-target="#myModal"/></td>';
									echo '</tr>';
								}
							?>
						</tbody>
						</table>
					</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div id="modal-content" class="modal-content">
	      	<button type="button" class="close" data-dismiss="modal" style="margin-top:10px;margin-right:10px;">&times;</button>
            <div id="modal-header-1" class="modal-header text-center">
                <h4 id="concesionario_modal" class="modal-title">Mindshare</h4>
                <small class="font-bold">Presupuesto Publicitario</small>
            </div>
            <div class="modal-body">
            	<div id="modal-body-1"></div>
            	<div id="modal-body-2" class="row">
            	</div>
            </div>
            <div id="modal-footer-1" class="modal-footer">
    			<a id="descargar" type="button" class="btn btn-primary" target="blank" style="width: 93px;">Descargar</a>
                <button id="cancelar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 93px;">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div id="modal2-body-1">
            		¿Está seguro que desea <b>rechazar</b> este presupuesto?
            	</div>
            </div>
            <div id="modal-footer2-1" class="modal-footer">
                <button id="rechazar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 46px;">Si</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div id="modal3-body-1">
            		¿Está seguro que desea <b>aprobar</b> este presupuesto?
            	</div>
            </div>
            <div id="modal-footer3-1" class="modal-footer">
                <button id="aprobar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 46px;">Si</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div id="modal3-body-1">
            		Confirme la solicitud de <b>modificación</b> para este presupuesto.
            	</div>
            </div>
            <div id="modal-footer3-1" class="modal-footer">
                <button id="modificar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 100px;">Enviar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 100px;">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div id="mensaje_exito"><p>Procesando presupuesto, por favor espere...</p></div>
                <img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js');?>"></script>

<script>

$('#desde, #hasta').datepicker({language: 'es'});

$.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
    	from = $("#desde").val().split("/");
		f = new Date(from[2], from[1] - 1, from[0]);
        var min = f;
        from = $("#hasta").val().split("/");
		f = new Date(from[2], from[1] - 1, from[0]);
        var max = f;
        from = data[2].split("/");
		f = new Date(from[2], from[1] - 1, from[0]);
        var age = f;

        if ( ( $('#desde').val() =='' && $('#hasta').val() =='' ) 	||
             ( $('#desde').val() =='' && age <= max ) 	||
             ( min <= age   && $('#hasta').val() =='' ) 	||
             ( min <= age   && age <= max ) )
        {
            return true;
        }
        return false;
    }
);

var request = 0;
	
$(document).ready(function() {
    var table = $('#table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Spanish.json"
        },
        "order": [[ 0, "desc" ]]
    });

    $('#desde, #hasta').on('change', function() {
        table.draw();
    } );
});

$('#table_body').on('click', '#consultar', function(){
	id= $(this).attr('value');
	request = id;
	$('#modal-body-1').html('<img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">');
	$('#modal-body-2').html('');
	$.ajax({
		method:'POST',
        url: "<?php echo $this->Html->url('mostrar_presupuesto_ms');?>/"+$(this).attr('value'),
        success: function(data){
        	$('#modal-content').html(data);
        }
	});
});

$('#aprobar').on('click',function(){
	$('[name = '+request+']').fadeOut(0);
	$.ajax({
		method:'POST',
		data:{note: $('.observaciones').val()},
		url: "<?php echo $this->Html->url('aprobar_presupuesto_ms').'/'.$in;?>/"+request,
		success: function(data){
			$('#loading').fadeOut(0);
        	$('#mensaje_exito').html('<p>Se ha aprobado el presupuesto con éxito.</p>');
		}
	});
	$('#myModal').modal('hide');
	$('#loading').fadeIn(0);
	$('#myModal4').modal('show');
});

$('#rechazar').on('click',function(){
	$('[name = '+request+']').fadeOut(0);
	$.ajax({
		method:'POST',
		data:{note: $('.observaciones').val()},
		url: "<?php echo $this->Html->url('rechazar_presupuesto_ms').'/'.$in;?>/"+request,
		success: function(data){
			$('#loading').fadeOut(0);
        	$('#mensaje_exito').html('<p>Se ha rechazado el presupuesto con éxito.</p>');
		}
	});
	$('#myModal').modal('hide');
	$('#loading').fadeIn(0);
	$('#myModal4').modal('show');
});

$('#modificar').on('click',function(){
	$('[name = '+request+']').fadeOut(0);
	$.ajax({
		method:'POST',
		data:{note: $('.observaciones').val()},
		url: "<?php echo $this->Html->url('modificar_presupuesto_ms').'/'.$in;?>/"+request,
		success: function(data){
			$('#loading').fadeOut(0);
        	$('#mensaje_exito').html('<p>Se ha enviado la solicitud de modificación con éxito.</p>');
		}
	});
	$('#myModal').modal('hide');
	$('#loading').fadeIn(0);
	$('#myModal4').modal('show');
});

$('.money').mask('#.##0', {reverse: true});
</script>