<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-3.5.2/select2.css');?>" />
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/select2-bootstrap/select2-bootstrap.css');?>" />
<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/bootstrap-datepicker-master/dist/css/bootstrap-datepicker3.min.css');?>" />

<div id ="main" class="content ">

	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Modificación de Solicitud de Presupuesto');?></h3>
		</div>
		<div class="panel-body">
		<div class="groups col-sm-8">
			<div class="col-sm-8">
				<label></label>
				<input id="concesionario" class="requerido form-control" readonly="" value="<?php echo $request['Group']['name'];?>">
			</div>
		</div>

		<div class="groups col-sm-4">
			<div class="col-sm-12">
				<label>Presupuesto Disponible</label>
				<input id="disponible" type="text" class="form-control" readonly="" style="text-align:right;" value="<?php echo number_format((float)($balance['Balance']['balance'] + $request['Request']['amount'] - $balance['Balance']['pending']), 0,',','.');?>">
				<label>Monto Disponible Merchandising</label>
				<input id="merchandising" type="text" class="form-control" readonly="" style="text-align:right;" value="<?php 
					$disp = number_format((float)($balance['Balance']['balance'] + $request['Request']['amount'] - $balance['Balance']['pending']), 0,',','.');
					$mdisp = number_format((float)($balance['Balance']['merchandising'] + $request['Request']['mamount'] - $balance['Balance']['mpending']), 0,',','.');
					
					$dispAux = ((float)($balance['Balance']['balance'] + $request['Request']['amount'] - $balance['Balance']['pending']));
					$mdispAux = ((float)($balance['Balance']['merchandising'] + $request['Request']['mamount'] - $balance['Balance']['mpending']));
					
					if($mdispAux > $dispAux) echo $disp;
					else echo $mdisp;
				?>">
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
						<input id="nombre" type="text" class="requerido form-control" value="<?php echo $requestEvents['Request']['title']?>"></input>
						<br>
	                	<label>Fecha</label>
						<input id="datepicker" type="text" class="requerido form-control" value="<?php 
	                    	setlocale(LC_ALL,"es_ES");
	                    	echo date('d/m/Y');
		                ?>" readonly=""></input>
						<br>
	                	<label>Ciudad</label>
						<input id="ciudad" type="text" class="form-control" readonly="" value="<?php echo $requestEvents['RequestEvent']['city']?>"></input>
						<br>
						<label>Fondo</label>
						<input id="fondo" type="text" class="requerido form-control" readonly="" value="Individual"></input>
					</div>
					<div class="col-sm-6">
	                	<label>Objetivo del Plan</label>
						<textarea id="objetivo" type="text" class="requerido form-control" rows="13"><?php echo $requestEvents['RequestEvent']['objective']?></textarea>
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

						<?php
							$i = 1;
							foreach ($requestEvents['Event'] as $re) {
						?>

		                <div class="bloque_actividad" value="<?php echo $i;?>">
			                <div class="col-sm-6">
								<label>Línea de Vehículo</label>
			                	<select class="js-source-states-2" id="vehiculos_<?php echo $i;?>" multiple="multiple" style="width: 100%">
										<option value="FIESTA" <?php if (strpos($re['line'],'FIESTA') !== false) echo 'selected';?>>FIESTA</option>
										<option value="FOCUS" <?php if (strpos($re['line'],'FOCUS') !== false) echo 'selected';?>>FOCUS</option>
										<option value="FUSION" <?php if (strpos($re['line'],'FUSION') !== false) echo 'selected';?>>FUSION</option>
										<option value="MUSTANG" <?php if (strpos($re['line'],'MUSTANG') !== false) echo 'selected';?>>MUSTANG</option>
										<option value="ECOSPORT" <?php if (strpos($re['line'],'ECOSPORT') !== false) echo 'selected';?>>ECOSPORT</option>
										<option value="ESCAPE" <?php if (strpos($re['line'],'ESCAPE') !== false) echo 'selected';?>>ESCAPE</option>
										<option value="EDGE" <?php if (strpos($re['line'],'EDGE') !== false) echo 'selected';?>>EDGE</option>
										<option value="EXPLORER" <?php if (strpos($re['line'],'EXPLORER') !== false) echo 'selected';?>>EXPLORER</option>
										<option value="RANGER" <?php if (strpos($re['line'],'RANGER') !== false) echo 'selected';?>>RANGER</option>
										<option value="F-150" <?php if (strpos($re['line'],'F-150') !== false) echo 'selected';?>>F-150</option>
			                        </optgroup>
			                    </select>
								<br>
								<br>
			                	<!--<label>Actividad</label>-->
			                	<select class="actividad requerido form-control" style="display:none;">
									<option value="none"></option>
									<option value="Prensa">Prensa</option>
									<option value="Radio">Radio</option>
									<option value="Televisión">Televisión</option>
									<option value="Digital">Digital</option>
									<option value="OMM">OMM</option>
									<option value="Mercado">Mercado directo</option>
									<option value="Ferias">Ferias</option>
									<option value="POP">POP</option>
									<option value="Merchandising">Merchandising</option>
									<option value="Eventos">Eventos</option>
								</select>
								<!--<br>-->
								<label>Medio</label>
								<select class="medio requerido form-control">
									<option value=''></option>
									<?php foreach ($medias as $keyMedia => $media) { ?>
										<option value="<?php echo($media['Media']['id']);?>" <?php if ($media['Media']['id'] == $re['media_id']){echo ('selected');}?>><?php echo($media['Media']['name']);?></option>
									<?php }?>
									<!--<option selected="" style="display:none;" value="<?php echo $re['media']?>"><?php echo $re['media']?></option>
									<optgroup label="ATL">
										<option value="Pauta en Medios">Pauta en Medios</option>
										<option value="POP">POP</option>
										<option value="Publicidad Exterior">Publicidad Exterior</option>
										<option value="Merchandising">Merchandising</option>
									</optgroup>
									<optgroup label="Mercadeo Directo">
										<option value="Físico">Físico</option>
										<option value="Digital">Digital</option>
									</optgroup>
									<option value="BTL">BTL</option>
									<option value="DIGITAL">DIGITAL</option>-->
								</select>

								<div class="row bloque_productos" <?php if($re['media_id'] != Media::MEDIA_MERCHANDISING) echo "hidden";?>>
									<div class="col-sm-12"><hr></div>
									<div>
										<div class="col-sm-12">
											<div class="col-sm-6">
												<label><sup>Nombre</sup></label>
											</div>
											<div class="col-sm-3">
												<label><sup>Precio</sup></label>
											</div>
											<div class="col-sm-3">
												<label><sup>Cantidad</sup></label>
											</div>
										</div>
									</div>
									<div class="productos">
									<?php
		                        		foreach ($eventsMerchandising as $em) {
		                        			if($em['Merchandising']['event_id'] == $re['id']){
		                        				echo '<div class="col-sm-12"><div class="col-sm-6"><input type="text" class="merch_name requerido form-control input-sm" value="'.$em['Merchandising']['name'].'"></input></div><div class="col-sm-3"><input type="text" class="merch_price requerido form-control input-sm" value="'.$em['Merchandising']['price'].'"></input></div><div class="col-sm-3"><input type="text" class="merch_quantity requerido form-control input-sm" value="'.$em['Merchandising']['quantity'].'"></input></div></div>';
		                        			}
		                        		}
		                        	?>

									</div>
									<div class="col-sm-12"><br></div>
									<div class="col-sm-12">
										<div class="col-sm-12">
											<input type="button" class="mas_productos btn btn-sm btn-primary pull-right" value="+" style="margin-left:5px;">
											<input type="button" class="menos_productos btn btn-sm btn-danger pull-right" value="-" style="">
										</div>
									</div>
									<div class="col-sm-12"><hr></div>
								</div>


								<br>
								<label>Monto</label>
								<input type="text" class="monto requerido form-control" value="<?php echo floatval($re['amount']);?>"></input>
								<br>
								<label>Observaciones</label>
								<textarea type="text" class="requerido observaciones form-control" rows="5"><?php echo $re['description']?></textarea>
							</div>
							<div class="col-sm-6">
								<label>Fecha de Ejecución</label>
								<div class="col-sm-12"><br></div>
								<div class="col-sm-12">
				                    <div class="col-sm-6"> <input type="radio" checked="" value="1" name="optionsRadios<?php echo $i;?>"> Individuales </div>
									<div class="col-sm-6"> <input type="radio" value="2" name="optionsRadios<?php echo $i;?>"> Rango </div>
								</div>
								<div class="col-sm-12"><br></div>
		                        <div class="fechas">
	                        	<?php
	                        		foreach ($eventsDates as $ed) {
	                        			if($ed['Date']['event_id'] == $re['id']){
	                        				if($ed['Date']['start']==$ed['Date']['end'])
	                        				echo '<div class="bloque col-sm-12"><input type="text" class="form-control datepick" value="'.date('d/m/Y',strtotime($ed['Date']['start'])).'"><div class="col-sm-12"><br></div></div>';
	                        				else
	                        				echo '<div class="bloque col-sm-12"><div class="input-daterange input-group datepick"><input type="text" class="startDate input-sm form-control" name="start" value="'.date('d/m/Y',strtotime($ed['Date']['start'])).'"/><span class="input-group-addon">a</span><input type="text" class="endDate input-sm form-control" name="end" value="'.date('d/m/Y',strtotime($ed['Date']['end'])).'"/></div><div class="col-sm-12"><br></div></div>';
	                        			}
	                        		}
	                        	?>
		                        </div>
		                        <input type="button" class="mas btn btn-primary pull-right" value="+" style="width: 35px;margin-left:5px;">
		                        <input type="button" class="menos btn btn-danger pull-right" value="-" style="width:35px;">
							</div>
							<div class="col-sm-12"><br><hr></div>
						</div>

						<?php
								$i++;
							}
						?>

	            	</div>

					<div class="col-sm-12">
						<a><i style="font-size: 30px;" id="agregarAct" title="Agregar otra Actividad" class="pe-7s-plus"></i></a> <sup>Agregar otra Actividad &nbsp;&nbsp;&nbsp;</sup>
						<a><i style="font-size: 30px;" id="removerAct" title="Remover última Actividad" class="pe-7s-less"></i></a> <sup>Remover otra Actividad</sup>
                        <input id="enviar" type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" value="Modificar Solicitud">
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
	//$('#datepicker').datepicker({language: 'es'});
	<?php
		$i = 1;
		foreach ($requestEvents['Event'] as $re) {
	?>
		$("#vehiculos_<?php echo $i;?>").select2();
	<?php
		$i++;
		}
	?>

	$(".select2-choices").attr('style','border-color:#e4e5e7 !important;');

	$('.datepick').datepicker({language: 'es', startDate: '0d'});

    $('#agregarAct').on('click',function(){
		id=parseInt($('#contenedor_actividades').children().length)+1;

    	$.get("<?php echo $this->Html->url('actividad');?>/"+id, function(data){
		    $('#contenedor_actividades').append(data);
		});
    });

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
    	if($(this).val() == <?php echo(Media::MEDIA_MERCHANDISING);?>) {
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

    $('#disponible').mask('#.##0', {reverse: true});
	$('#disponible').val('$'+$('#disponible').val());

	$('#merchandising').mask('#.##0', {reverse: true});
	$('#merchandising').val('$'+$('#merchandising').val());

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
	  			request: <?php echo $requestEvents['Request']['id'];?>,
	  			nombre: $('#nombre').val(),
	  			fecha: $('#datepicker').val(),
	  			ciudad: $('#ciudad').val(),
	  			fondo: $('#fondo').val(),
	  			objetivo: $('#objetivo').val(),
	  			actividades: JSON.stringify(arrayActividades),
	  			total: supertotal,
	  			mtotal: merchtotal,
	  		},
	        url: "<?php echo $this->Html->url('actualizar');?>",
	        success: function(data){
		        $('#loading').fadeOut(0);
		        console.log(data);
	        	//$('#mensaje_exito').html('<p>Se ha realizado la solicitud con éxito.</p>');
	        }
	  	});	
    });

	$('#enviar').on('click', function(){
		var valido = true;

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

		$('#concesionario_modal').html($('#concesionario').val());
		$('#total').unmask();
		$('#total').html(total);
		$('#total').mask('#.##0', {reverse: true});

		supertotal = total;
		merchtotal = mtotal;
		

		$('#myModal6').on('hidden.bs.modal', function () {
		location.href = "<?php echo $this->Html->url('ver');?>/1"
	})
	});

	$(window).unload( function () { 
		$.ajax({
			async: false,
		    url: "<?php echo $this->Html->url('unblock_request').'/'.$requestEvents['Request']['id'];?>",
		}); 
	});
</script>