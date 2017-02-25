<?php
	if(count($groups) == 0){
?>

<div id ="sub" class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Solicitud de Presupuesto');?></h3>
		</div>
		<div class="panel-body">
			<p>Disculpe, usted no pertenece a ningún concesiorario. </p>
			<p>No puede realizar solicitudes de presupuesto.</p>
		</div>
		</div>
		</div>
	</div>
</div>

<?php
	}
	else if(date('m') == 12){
?>
	<div id ="sub" class="content ">
		<div class="row">
			<div class="col-lg-12">
			<div class="hpanel hblue">
			<div class="panel-heading hbuilt">
				<h3><?php echo __('Solicitud de Presupuesto');?></h3>
			</div>
			<div class="panel-body">
				<p>Estamos en mes de Diciembre, las solicitudes han sido cerradas. </p>
				<p>No puede realizar solicitudes de presupuesto.</p>
			</div>
			</div>
			</div>
		</div>
	</div>
<?php		
	}
	else{
?>

<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-3.5.2/select2.css');?>" />
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-bootstrap/select2-bootstrap.css');?>" />
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>"/>

<div id ="main" class="content ">

	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Solicitud de Presupuesto');?></h3>
		</div>
		<div class="panel-body">
		<div class="groups col-sm-8">
			<div class="col-sm-8">
				<label></label>
				<select id="concesionario" class="requerido form-control">
					<option value="">Seleccione un concesionario</option>
					<?php
						foreach($groups as &$g) echo '<option value="'.$g['Group']['id'].'">'.$g['Group']['name'].'</option>';
					?>
				</select>
			</div>
		</div>

		<div class="groups col-sm-4">
			<div class="col-sm-12">
				<label>Presupuesto Disponible</label>
				<input id="disponible" type="text" class="form-control" readonly="" style="text-align:right;">
				<br>
				<label>Monto Disponible Merchandising</label>
				<input id="merchandising" type="text" class="form-control" readonly="" style="text-align:right;">
			</div>
		</div>
		</div>
		</div>
		</div>
	</div>

	<div class="row">
	    <div id="datos_sueldo" class="col-lg-12">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Datos de Solicitud
	            </div>
	            <div class="panel-body">
	                <div class="col-sm-6">
	                	<label>Nombre</label>
						<input id="nombre" type="text" class="requerido form-control"></input>
						<br>
	                	<label>Fecha</label>
						<input id="datepicker" type="text" class="requerido form-control" value="<?php 
	                    	setlocale(LC_ALL,"es_ES");
	                    	echo date('d/m/Y');
		                ?>" readonly=""></input>
						<br>
	                	<label>Ciudad</label>
						<input id="ciudad" type="text" class="form-control" readonly=""></input>
						<br>
						<label>Fondo</label>
						<input id="fondo" type="text" class="requerido form-control" readonly="" value="Individual"></input>
					</div>
					<div class="col-sm-6">
	                	<label>Objetivo del Plan</label>
						<textarea id="objetivo" type="text" class="requerido form-control" rows="13"></textarea>
					</div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="row">
	    <div id="datos_sueldo" class="col-lg-12">
	        <div class="hpanel">
	            <div class="panel-heading">
	                Actividades
	            </div>
	            <div class="panel-body">
	            	<div id="contenedor_actividades">
		                
	            	</div>

					<div class="col-sm-12">
						<a><i style="font-size: 30px;" id="agregarAct" title="Agregar otra Actividad" class="pe-7s-plus"></i></a> <sup>Agregar otra Actividad &nbsp;&nbsp;&nbsp;</sup>
						<a><i style="font-size: 30px;" id="removerAct" title="Remover última Actividad" class="pe-7s-less"></i></a> <sup>Remover otra Actividad</sup>
                        <input id="enviar" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" value="Enviar Solicitud" >
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
            <div class="modal-header text-center">
                <h4 id="concesionario_modal" class="modal-title"></h4>
                <small class="font-bold">Solicitud de Presupuesto</small>
            </div>
            <div class="modal-body">
                <p>Está por solicitar un presupuesto valorado en <strong>$<span id="total"></span></strong></p>
                <p>¿Está seguro que desea completar esta operación?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" style="width:46px;">No</button>
                <button id="save" type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#myModal6" style="width:46px;">Si</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <p>Seleccione un concesionario</p>
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
                <p>Existen datos vacios en el formulario. Recuerde asignar fechas válidas a cada actividad.</p>
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
                <p>El monto total del presupuesto para su solicitud excede el monto que tiene disponible en este momento. Por favor corregir.</p>
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

<div class="modal fade" id="myModal6" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div id="mensaje_exito"><p>Enviando solicitud, por favor espere...</p></div>
                <img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal7" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <p>El monto total del presupuesto de actividades de Merchandising para su solicitud excede el monto que tiene disponible en este momento. Por favor corregir.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->Html->url('/cms/vendor/select2-3.5.2/select2.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.es.min.js');?>"></script>

<script>
    $("#vehiculos_1").select2();

	$(".select2-choices").attr('style','border-color:#e4e5e7 !important;');

	//$('#datepicker').datepicker({language: 'es'});

    $('#agregarAct').on('click',function(){
		id=parseInt($('#contenedor_actividades').children().length)+1;

    	$.get("<?php echo $this->Html->url('actividad');?>/"+id, function(data){
		    $('#contenedor_actividades').append(data);
		});
    });

    $('#agregarAct').click();

    $('#removerAct').on('click',function(){
    	$('#contenedor_actividades').children('.bloque_actividad:last').remove();
    });

    $('.panel-body').on('click', '.mas',function(){
		id=parseInt($(this).closest('.bloque_actividad.fechas').length+1);

		idr = parseInt($(this).closest('.bloque_actividad').attr('value'));

    	if($('[name= "optionsRadios'+idr+'"]:checked').val() == 1){
	    	$(this).siblings('.fechas').append('<div class="bloque col-sm-12"><input type="text" class="form-control datepick requerido"><div class="col-sm-12"><br></div></div>');
    	}
    	if($('[name= "optionsRadios'+idr+'"]:checked').val() == 2){
    		$(this).siblings('.fechas').append('<div class="bloque col-sm-12"><div class="input-daterange input-group datepick"><input type="text" class="startDate input-sm form-control requerido" name="start" /><span class="input-group-addon">a</span><input type="text" class="endDate input-sm form-control requerido" name="end" /></div><div class="col-sm-12"><br></div></div>');
    	}
   		$(this).siblings('.fechas').children('.bloque').children('.datepick').datepicker({language: 'es', startDate: '0d'});
    });

    $('.panel-body').on('click', '.mas_productos',function(){
		$(this).parent().parent().siblings('.productos').append('<div class="col-sm-12"><div class="col-sm-6"><input type="text" class="merch_name requerido form-control input-sm"></input></div><div class="col-sm-3"><input type="text" class="merch_price requerido form-control input-sm"></input></div><div class="col-sm-3"><input type="text" class="merch_quantity requerido form-control input-sm"></input></div></div>');
    });

    $('.panel-body').on('click', '.menos_productos',function(){
    	$(this).parent().parent().siblings('.productos').children(':last').remove();
    });

    $('.panel-body').on('change', '.medio',function(){
    	if($(this).val() == <?php echo(Media::MEDIA_MERCHANDISING);?>) { //OJO
    		$(this).siblings('.bloque_productos').fadeIn(0);
    		$(this).closest('div').find('.mas_productos').click();
    	}
    	else {
    		$(this).siblings('.bloque_productos').fadeOut(0);
    		$(this).siblings('.bloque_productos').children('.productos').html('');
    	}
    });

    $('.panel-body').on('click', '.menos',function(){
    	$(this).siblings('.fechas').children('.bloque:last').remove();
    });

    $('.panel-body').on('change','.requerido', function(){
    	$(this).attr('style','border-color:#e4e5e7 !important;');
    });

    $('ul.select2-choices').on('click',function(){
    	$(this).attr('style','border-color:#e4e5e7 !important;');
    });

	var supertotal = 0;
	var merchtotal = 0;

	$('#save').on('click',function(){

		var arrayActividades = [];
		$('.bloque_actividad').each(function(){
		    var actividad = {};
    		var fechasI = [];
			var fechasR = [];

			actividad['vehiculo'] = '';

			$(this).children('div').children('.select2-container').each(function(){
				var data = $(this).select2("data");

				$.each( data, function( index, value ){
					actividad['vehiculo'] = actividad['vehiculo']+' '+value.text;
				});
			});

			actividad['actividad'] = ($(this).children('div').children('.actividad').val());
			actividad['medio'] = ($(this).children('div').children('.medio').val());
			actividad['monto'] = ($(this).children('div').children('.monto').val());
			actividad['observaciones'] = ($(this).children('div').children('.observaciones').val());
			actividad['fechasI'] = [];
			actividad['fechasR'] = [];
			actividad['merchandising'] = [];
			$(this).children('div').children('.fechas').children('.bloque').children('.datepick').each(function(){
				if((typeof $(this).val()!= 'undefined') && $(this).val() !== '')actividad['fechasI'].push($(this).val());
			});
			$(this).children('div').children('.fechas').children('.bloque').children('div').each(function(){
				var start;
				var end;
				if((typeof $(this).children('.startDate').val() != 'undefined') && $(this).children('.startDate').val() != '') start = ($(this).children('.startDate').val());
				if((typeof $(this).children('.endDate').val() != 'undefined') && $(this).children('.endDate').val() != '') end = ($(this).children('.endDate').val());
				if(typeof start != 'undefined' && typeof end != 'undefined') actividad['fechasR'].push([start,end]);
			});
			$(this).children('div').children('div.bloque_productos').children('.productos').children().each(function(){
				actividad['merchandising'].push([$(this).find('.merch_name').val(),$(this).find('.merch_price').val(),$(this).find('.merch_quantity').val()]);
			});
			arrayActividades.push(actividad);
		});
		$.ajax({
	  		method: "POST",
	  		data: { 
	  			grupo: $('#concesionario').val(),
	  			grupoName: $('#concesionario :selected').html(),
	  			nombre: $('#nombre').val(),
	  			fecha: $('#datepicker').val(),
	  			ciudad: $('#ciudad').val(),
	  			fondo: $('#fondo').val(),
	  			objetivo: $('#objetivo').val(),
	  			actividades: JSON.stringify(arrayActividades),
	  			total: supertotal,
	  			mtotal: merchtotal,
	  			disponible: $('#disponible').val(),
	  		},
	        url: "<?php echo $this->Html->url('crear');?>",
	        success: function(data){
	        	$('#loading').fadeOut(0);
	        	$('#mensaje_exito').html('<p>Se ha realizado la solicitud con éxito.</p>');
	        }
	  	});	
    });


	var arrayBalance = [];
	var arrayMerchandising = [];
	var arrayCity = [];

	<?php
		foreach($groups as &$g) {
			echo 'arrayBalance['.$g['Group']['id'].']="'.$g['Balance'].'";';
			echo 'arrayMerchandising['.$g['Group']['id'].']="'.$g['Merchandising'].'";';
			echo 'arrayCity['.$g['Group']['id'].']="'.$g['Group']['city'].'";';
		}
	?>

	$('#concesionario').on('change',function(){
		$('#disponible').val(arrayBalance[$(this).val()]);
		$('#merchandising').val(arrayMerchandising[$(this).val()]);
		$('#ciudad').val(arrayCity[$(this).val()]);

		$('#disponible').mask('#.##0', {reverse: true});
		$('#disponible').val('$'+$('#disponible').val());

		$('#merchandising').mask('#.##0', {reverse: true});
		$('#merchandising').val('$'+$('#merchandising').val());
	});

	$('#enviar').on('click', function(){
		var valido = true;

		if($('#concesionario').val() == '') {
			$('#myModal2').modal('show');
			return false;
		}
		else{
			$('.requerido').each(function(){
				if($(this).val()==''){
					$(this).attr('style','border-color: red !important;');
					valido = false;
				} 
			});
			$(".js-source-states-2").each(function(){
				if($(this).select2("data").length == 0){
					$(this).children(".select2-choices").attr('style','border-color: red !important;');
					valido = false;
				}
			});
		}

		$('.bloque_actividad').each(function(){
			if($(this).children('div').children('.fechas').children('.bloque').children('.datepick').length == 0 &&
			$(this).children('div').children('.fechas').children('.bloque').children('div').length == 0)
			valido = false;
		});

		if(!valido){
			$('#myModal3').modal('show');
			return false;
		} 

		var montos_validos = true;
		var total = 0;
		var mtotal = 0;
		$('#disponible').unmask();
		var disponible = parseFloat($('#disponible').val());
		$('#disponible').mask('#.##0', {reverse: true});
		$('#disponible').val('$'+$('#disponible').val());
		$('#merchandising').unmask();
		var merchandising = parseFloat($('#merchandising').val());
		$('#merchandising').mask('#.##0', {reverse: true});
		$('#merchandising').val('$'+$('#merchandising').val());

		$('.monto').each(function(){
			if(!/^[0-9]+$/.test($(this).val())){
				$(this).val('');
				$(this).attr('style','border-color: red !important;');
				montos_validos = false;
			}
			total = total + parseFloat($(this).val());
		});

		$('.bloque_actividad').each(function(){
		   if($(this).children('div').find('.medio').val() == <?php echo(Media::MEDIA_MERCHANDISING);?>)
		   		mtotal += parseFloat($(this).children('div').find('.monto').val());
		});

		if(!montos_validos){
			$('#myModal5').modal('show');
			return false;
		}
		if(total > disponible){
			$('#myModal4').modal('show');
			return false;
		}
		if(mtotal > merchandising){
			$('#myModal7').modal('show');
			return false;
		}

		$('#concesionario_modal').html($('#concesionario :selected').html());
		$('#total').unmask();
		$('#total').html(total);
		$('#total').mask('#.##0', {reverse: true});

		supertotal = total;
		merchtotal = mtotal;
	});

	$('#myModal6').on('hidden.bs.modal', function () {
		location.href = "<?php echo $this->Html->url('ver');?>/1"
	})

</script>

<?php
	}
?>