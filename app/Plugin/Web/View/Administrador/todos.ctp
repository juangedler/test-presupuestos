<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content">

	<div id="solicitudes_pendientes" class="row">
	    <div class="col-lg-12">
	        <div class="hpanel hblue">
				<div class="panel-heading hbuilt">
					<h3>Presupuestos asignados a Concesionarios</h3>
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
								<th>Concesionario</th>
								<th style="text-align: right;">Monto Privado</th>
								<th style="text-align: right;">Monto Nacional</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody id="table_body">
							<?php
								foreach($groups as $g){
									foreach($g['Movements'] as $m){
										echo '<tr name="'.$g['Group']['id'].'">';
										echo '<td>'.$g['Group']['name'].'</td>';
										echo '<td align="right">$<span class="money">'.$m['Movements']['amount'].'</span></td>';
										echo '<td align="right">$<span class="money">'.$m['Movements']['nacional'].'</span></td>';
										echo '<td>'.date('d/m/Y',strtotime($m['Movements']['created'])).'</td>';
										echo '</tr>';
									}
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
        from = data[3].split("/");
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
	
$(document).ready(function() {
    var table = $('#table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Spanish.json"
        },
        "order": [[ 3, "desc" ]]
    });

    $('#desde, #hasta').on('change', function() {
        table.draw();
    } );
});

$('.money').mask('#.##0', {reverse: true});
</script>