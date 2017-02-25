<?php
	if(count($nationalMovement) == 0){
?>
<div class="content ">
	<div id="solicitudes_pendientes" class="row">
		<div class="col-lg-12">
			<div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3>Registros de Monto Nacional Eliminados</h3>
				</div>
				<div class="panel-body">
					No hay registros de montos nacionales eliminados.
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

<div class="content ">
	<div id="solicitudes_pendientes" class="row">
	    <div class="col-lg-12">
	        <div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3>Registros de Monto Nacional Eliminados</h3>
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
								<th>#Registro</th>
								<th>Nombre</th>
								<th>Descripción</th>
								<th style="text-align: right;">Monto</th>
								<th style="text-align: right;">Fecha</th>
							</tr>
						</thead>
						<tbody id="table_body">
							<?php
								foreach($nationalMovement as $nm){
									echo '<tr name="'.$nm['NationalMovement']['id'].'">';
									echo '<td>'.$nm['NationalMovement']['id'].'</td>';
									echo '<td>'.$nm['NationalMovement']['title'].'</td>';
									echo '<td>'.$nm['NationalMovement']['description'].'</td>';
									echo '<td align="right">$<span class="money">'.$nm['NationalMovement']['amount'].'</span></td>';
									echo '<td align="right">'.date('d/m/Y',strtotime($nm['NationalMovement']['created'])).'</td>';
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
            		¿Está seguro que desea <b>eliminar</b> esta solicitud?
            	</div>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary btn-ok" style="width: 46px;">Si</a>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 46px;">No</button>
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
        from = data[4].split("/");
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
</script>

<?php
	}
?>