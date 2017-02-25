<?php
	$text1 = 'Presupuestos Firmados';
	$text2 = 'No tiene documentos firmados aún.';

	if(in_array($in, array(1,2))){
		$text1 = 'Presupuestos por Firmar';
		$text2 = 'No tiene documentos pendientes por firmar.';
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
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />

<div class="content">
	<div id="solicitudes_pendientes" class="row">
	    <div class="col-lg-12">
	        <div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3><?php echo $text1;?></h3>
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
									echo '<td>'.$r['RequestFile']['number'].'</td>';
									echo '<td>'.$r['RequestFile']['title'].'</td>';
									echo '<td>'.date('d/m/Y',strtotime($r['RequestFile']['updated'])).'</td>';
									echo '<td align="right">$<span class="money">'.$r['RequestFile']['amount'].'</span></td>';
									echo '<td align="center">
											<a class="request_id pe-7s-cloud-download" title="Descargar" value="'.$r['RequestFile']['id'].'" style="color:blue;font-size: 20px;" href="'.Router::url('/') .$r['RequestFile']['file'].'" target="_blank"/>';
									if(in_array($in, array(1,2)))
									echo '
											<a id="firmar_si" data-href="'.$this->Html->url('/web/agencia/aprobar_presupuesto/').$in.'/'.$r['RequestFile']['id'].'" class="pe-7s-check" title="Aprobar" value="'.$r['RequestFile']['id'].'" data-toggle="modal" data-target="#myModal1" style="color:green;font-size: 20px;margin-right:10px;margin-left:10px;"/>

											<a id="firmar_no" data-href="'.$this->Html->url('/web/agencia/solicitar_modificacion_presupuesto/').$r['RequestFile']['id'].'" class="request_id pe-7s-pen" title="Solicitar Modificación" value="'.$r['RequestFile']['id'].'" data-toggle="modal" data-target="#myModal2" style="color:#FF7500;font-size: 20px;"/>
										  </td>';
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

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div>
            		¿Está seguro que desea <b>aprobar</b> este presupuesto?
            	</div>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary btn-ok" style="width: 46px;">Si</a>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
            	<div>
            		¿Está seguro que desea solicitar una <b>modificación</b> sobre este presupuesto?
            		<hr>
			        <label>Observaciones:</label>
			        <textarea id="obs" type="text" class="requerido observaciones form-control" rows="2"></textarea>
            	</div>
            </div>
            <div class="modal-footer">
                <a id="mod" type="button" class="btn btn-primary btn-ok" style="width: 46px;">Si</a>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div id="mensaje_exito"><p></p></div>
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

<?php 
	$message = $this->Session->flash();
    if($message){
?>
	$('#mensaje_exito').html('<?=$message?>');
	$('#myModal').modal('show');
<?php
    }
?>

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


$('.money').mask('#.##0', {reverse: true});

$('#myModal1').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#myModal2').on('show.bs.modal', function(e) {
    $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
});

$('#mod').on('click',function(){
	$(this).attr('href', $(this).attr('href')+'/'+$('#obs').val());
});

</script>

<?php
	}
?>