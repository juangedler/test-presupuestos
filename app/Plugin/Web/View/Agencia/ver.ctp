<?php echo $this->Html->css('Web.custom'); ?>
<?php echo $this->Html->script('Web.jquery.form.js'); ?>
<?php
	switch ($option) {
		case 1:
			$title = __('Solicitudes Pendientes');
			break;
		case 2:
			$title = __('Solicitudes Aprobadas');
			break;
		case 3:
			$title = __('Solicitudes Aprobadas por Ford');
			break;
		case 4:
			$title = __('Solicitudes Rechazadas');
			break;
		case 5:
			$title = __('Solicitudes Rechazadas por Ford');
			break;
		case 999:
			$title = __('Solicitudes Anuladas por Ford');
			break;
	}
?>

<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content ">

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

	                <div class="groups index col-sm-12">
						<table id ="table" class="table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>#Solicitud</th>
								<th>Concesionario</th>
								<th>Fecha</th>
								<th style="text-align: right;">Monto</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="table_body">
							<?php
								foreach($requests as $r){
									echo '<tr name="'.$r['Request']['id'].'">';
									echo '<td>'.str_pad($r['Request']['id'], 8, "0", STR_PAD_LEFT).'</td>';
									echo '<td id="group_name">'.$r['Request']['group_name'].'</td>';
									echo '<td>'.date('d/m/Y',strtotime($r['Request']['created'])).'</td>';
									echo '<td align="right">$<span class="money">'.$r['Request']['amount'].'</span></td>';
									echo '<td align="center"><a id="consultar" class="request_id pe-7s-search" title="Ver detalle" value="'.$r['Request']['id'].'" data-toggle="modal" data-target="#myModal"/></td>';
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
        <div class="modal-content">
	      	<button type="button" class="close" data-dismiss="modal" style="margin-top:10px;margin-right:10px;">&times;</button>
            <div id="modal-header-1" class="modal-header text-center">
                <h4 id="concesionario_modal" class="modal-title"></h4>
                <small class="font-bold">Solicitud de Presupuesto</small>
            </div>
            <div class="modal-body">
            	<div id="modal-body-1"></div>
            	<div id="modal-body-2" class="row">
            	</div>
            </div>
            <div id="modal-footer-1" class="modal-footer">
            	<div id="optional_section"></div>
    			<a id="descargar" href="../ver_pdf" type="button" class="btn btn-primary" target="blank" style="width: 93px;">Descargar</a>
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
            		¿Está seguro que desea <b>rechazar</b> esta solicitud?
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
            		¿Está seguro que desea <b>aprobar</b> esta solicitud?
            	</div>
            </div>
            <div id="modal-footer3-1" class="modal-footer">
                <button id="aprobar" type="button" class="btn btn-primary" data-dismiss="modal" style="width: 46px;">Si</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div id="mensaje_exito"><p>Procesando solicitud, por favor espere...</p></div>
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
var visualizada = false;
	
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
	$('#descargar').fadeOut(0);
	id= $(this).attr('value');
	request = id;
	$('#concesionario_modal').html($(this).parent().parent().children('#group_name').html());
	$('#modal-body-1').html('<img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">');
	$('#modal-body-2').html('');
	$.ajax({
		method:'POST',
        url: "<?php echo $this->Html->url('consultar');?>/"+$(this).attr('value'),
        success: function(data){
        	$('#modal-body-1').html(data);
        	console.log(data.length);
        	if(data.length > 100){
        		visualizada = true;
        		if(<?php echo $option;?> == 1)
	        	$('#modal-body-2').html('<hr><label>Observaciones:</label><textarea type="text" class="requerido observaciones form-control" rows="2"></textarea><br><button type="button" class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#myModal2" style="margin-right:5px; width:75px;" value="'+id+'">Rechazar</button><button type="button" class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#myModal3" style="margin-right:5px; width:75px;" value="'+id+'">Aprobar</button>');
	        	$('#descargar').fadeIn(0);
	        	$('#descargar').attr('href','../ver_pdf/'+id);
				$('.moneh').mask('#.##0', {reverse: true});
        	}
        	else {
        		$('#descargar').fadeOut(0);
        		visualizada = false;
        	}
			$('.modal').data('bs.modal').handleUpdate();
        }
	});
	if(<?=$option?> == <?=State::STATE_APROBADO_FORD?>)
	$.ajax({
		method:'POST',
        url: "<?php echo $this->Html->url('is_supportable');?>/"+id,
        success: function(data){
        	if(data == 1){
	    		button_disable(id);
	    	}
        	else{
	    		button_enable(id);
        	}
        }
	});
});

function button_disable(id){
	$('#optional_section').html('<button type="button" class="deshabilitar btn btn-danger pull-left" style="margin-right:5px; width:200px;" value="'+id+'">Inhabilitar Carga Soportes</button>');
	$('.deshabilitar').on('click',function(){
		$.ajax({
			method:'POST',
			url: "<?php echo $this->Html->url('deshabilitar_soportes');?>/"+id,
			success: function(data){
				button_enable(id);
			}
		});
	});
}

function button_enable(id){
	$('#optional_section').html('<button type="button" class="habilitar btn btn-success pull-left" style="margin-right:5px; width:200px;" value="'+id+'">Habilitar Carga Soportes</button>');
	$('.habilitar').on('click',function(){
		$.ajax({
			method:'POST',
			url: "<?php echo $this->Html->url('habilitar_soportes');?>/"+id,
			success: function(data){
				button_disable(id);
			}
		});
	});
}

$('#aprobar').on('click',function(){
	$('[name = '+request+']').fadeOut(0);
	$.ajax({
		method:'POST',
		data:{note: $('.observaciones').val(),grupoName:$('#concesionario_modal').html()},
		url: "<?php echo $this->Html->url('aprobar');?>/"+request,
		success: function(data){
			$('#loading').fadeOut(0);
        	$('#mensaje_exito').html('<p>Se ha aprobado la solicitud con éxito.</p>');
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
		data:{note: $('.observaciones').val(),grupoName:$('#concesionario_modal').html()},
		url: "<?php echo $this->Html->url('rechazar');?>/"+request,
		success: function(data){
			$('#loading').fadeOut(0);
        	$('#mensaje_exito').html('<p>Se ha rechazado la solicitud con éxito.</p>');
		}
	});
	$('#myModal').modal('hide');
	$('#loading').fadeIn(0);
	$('#myModal4').modal('show');
});

$('.money').mask('#.##0', {reverse: true});

$('#myModal').on('hidden.bs.modal', function () {
	if(visualizada)
	$.ajax({
		async: false,
	    url: "<?php echo $this->Html->url('unblock_request');?>/"+request,
	}); 
})

$(window).unload( function () { 
	if(visualizada)
	$.ajax({
		async: false,
	    url: "<?php echo $this->Html->url('unblock_request');?>/"+request,
	}); 
});
</script>