<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />

<div class="content">

	<?php
	if(!$cero && date('d') >= 15){
	?>
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Cierre de Año');?></h3>
		</div>
		<div class="panel-body">
		<p>Puesta en cero de saldos de todos los concesionarios.</p>
		<p>Para vaciar las cuentas de los concesionarios presione el botón.</p>
		<br>
		<div class="groups index col-sm-12" align="center">
			<a class="btn btn-primary" data-toggle="modal" data-target="#myModal5">Puesta en Cero</a>
		</div>
		</div>
		</div>
		</div>
	</div>
	<?php
	}
	?>

	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Asignación de Presupuesto');?></h3>
		</div>
		<div class="panel-body">
		<div class="groups index col-sm-12">
			<div class="col-sm-8">
				<select id="concesionario" class="form-control">
				<option>Seleccione un concesionario</option>
				<?php foreach ($groups as $group): ?>
				<option value="<?php echo $group['Group']['id'];?>"><?php echo $group['Group']['name'];?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<div class="col-sm-4">
				<img id="loading" hidden class ="pull-right" src="<?php echo $this->Html->url('/cms/images/loader.gif');?>" width="30" height="30">
			</div>
		</div>
		</div>
		</div>
		</div>
	</div>

	<div id="balance_datos" hidden class="row">
	    <div class="col-lg-4">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Presupuesto Actual 
	            </div>
	            <div class="panel-body">
	                <div class="col-sm-12">
	                	<label>Monto Disponible</label>
	                	<sub id="fecha" class="pull-right" hidden>
	                	<?php 
	                    	setlocale(LC_ALL,"es_ES");
	                    	echo date('d/m/Y');
		                ?>
	                	</sub>
						<input id="disponible" type="text" class="form-control" readonly=""></input>
						<br>
						<label>Monto Merchandising Disponible</label>
						<input id="merchandising" type="text" class="form-control" readonly=""></input>
						<br>
					</div>
	            </div>
	        </div>
	    </div>
	    <div id="datos_sueldo" class="col-lg-8">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Próximo Presupuesto
	            </div>
	            <div class="panel-body">
	                <div class="col-sm-6">
	                	<label>Monto Individual</label>
						<input id="privado" type="text" class="requerido form-control" ></input>
						<br>
	                	<label>Mes</label>
						<select id="months" class="requerido form-control">
							<option></option>
						</select>
						<br>
						<label>Final Disponible</label>
						<input id="final" type="text" class="form-control" readonly=""></input>
					</div>
					<div class="col-sm-6">
	                	<label>Monto Nacional</label>
						<input id="nacional" type="text" class="requerido form-control"></input>
						<br>
						<label>Porcentaje para Merchandising</label>
						<input id="percent" type="text" class="requerido form-control" placeholder="0" maxlength="3" value="10"></input>
						<br>
						<label>Final Merchandising Disponible</label>
						<input id="mfinal" type="text" class="form-control" readonly=""></input>
						<br>
						<br>
						<img id="loading2" hidden class ="col-sm-offset-4" src="<?php echo $this->Html->url('/cms/images/loader.gif');?>" width="100" height="100">

					</div>
					<div class="col-sm-12">
						<br>
						<br>
						<div class="col-sm-6" align="right">
							<input id="asignar" type="button" class="btn btn-primary" value="Asignar" style="width:75px;" data-toggle="modal" data-target="#myModal"></input>
						</div>
						<div class="col-sm-6" align="left">
							<input id="borrar" type="button" class="btn btn-primary" value="Borrar" style="width:75px;"></input>
						</div>
	            	</div>
	            </div>
	        </div>
	    </div>
	    <div hidden id="info_sueldo" class="col-lg-8">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Próximo Presupuesto
	            </div>
	            <div class="panel-body">
	                <p>El presupuesto ya ha sido asignado. </p>
	                <p>Para una nueva asignación de presupuesto regrese el próximo mes.</p>
	            </div>
	        </div>
	    </div>
	</div>

	<div id="movimientos_datos" hidden class="row">
	    <div class="col-lg-12">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Histórico de Presupuesto Asignado
	            </div>
	            <div class="panel-body">
	                <div class="groups index col-sm-12">
						<table id ="table" class="table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>Mes<?php //echo $this->Paginator->sort('first_name'); ?></th>
								<th>Año<?php //echo $this->Paginator->sort('last_name'); ?></th>
								<th style="text-align: right;">Monto Asignado<?php //echo $this->Paginator->sort('username'); ?></th>
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 id="concesionario_modal" class="modal-title">Concesionario Tal</h4>
                <small class="font-bold">Asignación de Presupuesto</small>
            </div>
            <div class="modal-body">
                <p>Está por asignar los siguientes montos:</p>
                <p>Monto Individual: <strong id="monto_privado"></strong></p>
                <p>Monto Nacional: <strong id="monto_nacional"></strong></p>
                <p>Para el mes de <strong id="monto_mes"></strong></p>
                <p>¿Está seguro que desea continuar con esta operación?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button id="save" type="button" class="btn btn-primary" data-dismiss="modal">Si</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <p>Caracteres inválidos en campo de monto. Por favor utilice caracteres numéricos.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <p>El presupuesto ha sido asignado satisfactoriamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <p>Existen datos vacios en el formulario</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal5" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body" align="">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
		      	<p>Está por vaciar las cuentas de todos los concesionarios.<br>
		      	Esta acción colocará el balance de cada concesionario en el sistema en cero ($0.00) y se asignará al monto nacional para uso de Ford.<br>
		      	Adicionalmente, todas las solicitudes de concesionarios que estén abiertas se cerrarán automáticamente.</p>
                <p>¿Está seguro que desea continuar?</p>
				<p>Tenga en cuenta que esta acción es irreversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <a id="cero" type="button" class="btn btn-primary" href="<?php echo $this->Html->url('/web/administrador/ceroSaldos');?>">Si</a>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');?>"></script>
<script>

var disponible_original = 0;
var merchandising_original = 0;

var percentages= {
<?php
	foreach ($groups as $group) {
		echo $group['Group']['id'] . ':' . $group['Group']['last_percent'].',';
	}
?>
};

var months = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

$('#privado').mask('#.##0', {reverse: true});
$('#nacional').mask('#.##0', {reverse: true});
$('#disponible').mask('#.##0', {reverse: true});
$('#final').mask('#.##0', {reverse: true});

$('#privado').on('change',function(){
	$(this).unmask();
	$('#final').unmask();
	$('#mfinal').unmask();

	var uno = 0;
	var percent = 0;
	if($(this).val() != '') uno = parseFloat($(this).val());
	if($('#percent').val() != '') percent = parseInt($('#percent').val());
	var dos = parseFloat(disponible_original);
	var mdos = parseFloat(merchandising_original);
	var fin = uno + dos;
	var mfin = uno * percent / 100 + mdos;
	$('#final').val(fin);
	$('#mfinal').val(mfin);

	$(this).mask('#.##0', {reverse: true});
	$('#final').mask('#.##0', {reverse: true});
	$('#mfinal').mask('#.##0', {reverse: true});
});

$('#percent').on('change',function(){
	$('#privado').unmask();
	$('#mfinal').unmask();

	var uno = 0;
	var percent = 0;
	if($('#privado').val() != '') uno = parseFloat($('#privado').val());
	if($('#percent').val() != '') percent = parseInt($('#percent').val());
	var mdos = parseFloat(merchandising_original);
	var mfin = uno * percent / 100 + mdos;
	$('#mfinal').val(mfin);

	$('#privado').mask('#.##0', {reverse: true});
	$('#mfinal').mask('#.##0', {reverse: true});
});


$('#borrar').on('click', function(){
	$('#privado').val('');
    $('#nacional').val('');
	$('#final').val($('#disponible').val());
	$("#months").val($("#months option:first").val());
});

var actualizado = 0;


$('#concesionario').on('change', function(){
	$('#movimientos_datos').fadeOut(0);
	$('#balance_datos').fadeOut(0);
	$('#fecha').fadeOut(0);
	$('#percent').val(percentages[$('#concesionario').val()]);
	if(actualizado == 0) $('#loading').fadeIn(0);
  	$.ajax({
        url: "<?php echo $this->Html->url('loadGroup').'/';?>"+$('#concesionario').val(),
        data: {actualizado: actualizado},
        success: function(data){
        	datos = $.parseJSON(data);
			$('#balance_datos').fadeIn(0);
			$('#movimientos_datos').fadeIn(0);
			$('#loading').fadeOut(0);
            disponible_original = parseFloat(datos.Balance);
            merchandising_original = parseFloat(datos.Merchandising);

            $('#privado').unmask();
			$('#nacional').unmask();
			$('#disponible').unmask();
			$('#merchandising').unmask();
			$('#final').unmask();
			$('#mfinal').unmask();

            $('#disponible').val(disponible_original);
            $('#merchandising').val(merchandising_original);
            $('#final').val(disponible_original);
            $('#mfinal').val(merchandising_original);
            $('#privado').val('');
            $('#nacional').val('');

			$('#privado').mask('#.##0', {reverse: true});
			$('#nacional').mask('#.##0', {reverse: true});
			$('#disponible').mask('#.##0', {reverse: true});
			$('#merchandising').mask('#.##0', {reverse: true});
			$('#final').mask('#.##0', {reverse: true});
			$('#mfinal').mask('#.##0', {reverse: true});
			if($('#disponible').val() != '') $('#fecha').fadeIn(0);
			var aux = '';

			//$('#table').dataTable().fnClearTable();

			$.each(datos.Movements, function(i,o){
				total_amount = parseFloat(o.Movements.amount) + parseFloat(o.Movements.balance_before);
				total_amount = total_amount;
				aux= aux+'<tr>'+
				'<td>'+months[o.Movements.abono_mes]+'</td>'+
				'<td>'+o[0].year+'</td>'+
				'<td align="right">$<span class="money">'+o.Movements.amount+'</span></td>'+
				'<td align="right">$<span class="money">'+o.Movements.balance_before+'</span></td>'+
				'<td align="right">$<span class="money">'+total_amount+'</span></td></tr>';
				//$('#table').dataTable().fnAddData( [months[o.Movements.abono_mes],o[0].year,o.Movements.amount,o.Movements.balance_before] );
			});

			$('#table_body').html(aux);
			$('.money').mask('#.##0', {reverse: true});

			if(datos.Movements.length) {
				if(datos.Movements[0][0].year == <?php echo date('Y');?>){
					mes = parseInt(datos.Movements[0].Movements.abono_mes);
					if(mes < <?php echo date('m')-1;?>) mes = <?php echo date('m')-1;?>
				}
				else mes = <?php echo date('m')-1;?>
			}
			else mes = <?php echo date('m')-1;?>;
			
			mes++;

			if(mes > <?php echo date('m')+1;?> || mes >= 12) {
				console.log(<?php echo date('d');?>);
				if(<?php echo date('d');?> <= 15){
					$('#datos_sueldo').fadeIn(0);
					$('#info_sueldo').fadeOut(0);
				}
				else{
					$('#datos_sueldo').fadeOut(0);
					$('#info_sueldo').fadeIn(0);
				}
			}
			else {
				$('#datos_sueldo').fadeIn(0);
				$('#info_sueldo').fadeOut(0);
			}

			$('#months').html('<option></option>');
			for(i = mes; i <= <?php echo date('m')+1;?>; i++){
				$('#months').append('<option value="'+i+'">'+months[i]+'</option>');
				break;
			}

			if(actualizado == 1) $('#myModal3').modal('show');
			actualizado = 0;
			$('#loading2').fadeOut(0);
			$('#balance_datos').fadeIn(0);
        }
  	});
});

$('.requerido').on('change',function(){
	$(this).attr('style','border-color:#e4e5e7 !important;');
});

$('#asignar').on('click',function(){
	if($('#privado').val()=='' || $('#nacional').val()=='' || $('#months').val()=='') {
		$('#myModal4').modal('show');
		$('.requerido').each(function(){
			if($(this).val()==''){
				$(this).attr('style','border-color: red !important;');
				valido = false;
			} 
		});
		return false;
	}
	$('#concesionario_modal').html($('#concesionario :selected').html());
	$('#monto_privado').html('$'+$('#privado').val());
	$('#monto_nacional').html('$'+$('#nacional').val());
	$('#monto_mes').html(months[$('#months').val()]);
});

$('#save').on('click',function(){
	$('#loading2').fadeIn(0);

	$('#privado').unmask();
	$('#nacional').unmask();
	$('#disponible').unmask();

	var priv = (parseFloat($('#privado').val()));
	var naci = (parseFloat($('#nacional').val()));
	var disp = (parseFloat($('#disponible').val()));

	$('#privado').mask('#.##0', {reverse: true});
	$('#nacional').mask('#.##0', {reverse: true});
	$('#disponible').mask('#.##0', {reverse: true});

	percentages[$('#concesionario').val()] = parseInt($('#percent').val());
  	$.ajax({
  		method: "POST",
  		data: { privado: 	priv, 
  				nacional: 	naci,  
  				disponible: disp, 
  				percentage:	parseInt($('#percent').val()), 
  				mes: 		$('#months').val(), 
  				group: 		$('#concesionario').val(), 
  				mes_nombre: months[$('#months').val()]},
        url: "<?php echo $this->Html->url('asignarSaldo');?>",
        success: function(data){
        	console.log(data);
        	actualizado = 1;
			$('#concesionario').trigger('change');
			return true;
        }
  	});	
});

$('#percent').bind('keydown', function (event) {
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if(event.which != 8 && event.which != 9){
	    if (!regex.test(key)) {
	       event.preventDefault();
	       return false;
	    }
	    else{
	    	if(parseInt($('#percent').val()+String.fromCharCode(event.which)) > 100)
	    		$('#percent').val('100');
	    }
    }
});

</script>