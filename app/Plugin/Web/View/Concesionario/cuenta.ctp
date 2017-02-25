<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Estado de Cuenta');?></h3>
		</div>
		<div class="panel-body">
		<div class="groups index col-sm-8">
			<div class="col-sm-8">
				<select id="concesionario" class="form-control">
				<option>Seleccione un concesionario</option>
				<?php foreach ($groups as $group): ?>
				<option value="<?php echo $group['Group']['id'];?>"><?php echo $group['Group']['name'];?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<div class="col-sm-4" align="center">
				<img id="loading" hidden src="<?php echo $this->Html->url('/cms/images/loader.gif');?>" width="30" height="30">
			</div>
		</div>
		<div class="groups col-sm-4">
			<div class="col-sm-12">
				<label>Presupuesto Disponible</label>
				<input id="balance" type="text" class="form-control" readonly="" style="text-align:right;">
				<label>Presupuesto Pendiente</label>
				<input id="pending" type="text" class="form-control" readonly="" style="text-align:right;">
			</div>
		</div>
		</div>
		</div>
		</div>
	</div>

	<div id="movimientos_datos" hidden class="row">
	    <div class="col-lg-12">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Histórico de Movimientos
	            </div>
	            <div class="panel-body">
	                <div class="groups index col-sm-12">
						<table id ="table" class="table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Fecha<?php //echo $this->Paginator->sort('first_name'); ?></th>
								<th>Mes<?php //echo $this->Paginator->sort('first_name'); ?></th>
								<th>Año<?php //echo $this->Paginator->sort('last_name'); ?></th>
								<th>Tipo<?php //echo $this->Paginator->sort('last_name'); ?></th>
								<th style="text-align: right;">Monto Asignado</th>
								<th style="text-align: right;">Disponible<?php //echo $this->Paginator->sort('email'); ?></th>
								<th style="text-align: right;">Monto Total<?php //echo $this->Paginator->sort('email'); ?></th>
							</tr>
						</thead>
						<tbody id="table_body">
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
<script>

var arrayBalance = [];
var arrayPending = [];

<?php
	foreach($groups as $g) {
		echo 'arrayBalance['.$g['Group']['id'].']="'.$g['Balance'].'";';
		echo 'arrayPending['.$g['Group']['id'].']="'.$g['Pending'].'";';
	}
?>

var months = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

var actualizado = 0;

$('#concesionario').on('change', function(){
	$('#balance').val(arrayBalance[$(this).val()]);
	$('#pending').val(arrayPending[$(this).val()]);
	$('#balance').mask('#.##0', {reverse: true});
	$('#pending').mask('#.##0', {reverse: true});
	$('#balance').val('$'+$('#balance').val());
	$('#pending').val('$'+$('#pending').val());

	$('#loading').fadeIn(0);
  	$.ajax({
        url: "<?php echo $this->Html->url('loadGroup').'/';?>"+$('#concesionario').val(),
        data: {actualizado: actualizado},
        success: function(data){
        	datos = $.parseJSON(data);
			$('#movimientos_datos').fadeIn(0);
			$('#loading').fadeOut(0);
			var aux = '';

			$.each(datos.Movements, function(i,o){
				if(o.Movements.type == 'ABONO')
				total_amount = parseFloat(o.Movements.balance_before) + parseFloat(o.Movements.amount);
				else if(o.Movements.type == 'RECHAZADA')
				total_amount = parseFloat(o.Movements.balance_before);
				else 
				total_amount = parseFloat(o.Movements.balance_before) - parseFloat(o.Movements.amount);

				aux= aux+'<tr>'+
				'<td>'+o.Movements.created+'</td>'+
				'<td>'+months[o.Movements.abono_mes]+'</td>'+
				'<td>'+o[0].year+'</td>'+
				'<td>'+o.Movements.type+'</td>'+
				'<td align="right">$<span class="money">'+parseFloat(o.Movements.amount)+'</span></td>'+
				'<td align="right">$<span class="money">'+parseFloat(o.Movements.balance_before)+'</span></td>'+
				'<td align="right">$<span class="money">'+total_amount+'</span></td></tr>';
			});

			$('#table_body').html(aux);
			$('.money').mask('#.##0', {reverse: true});
        }
  	});
});
</script>