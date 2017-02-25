<div class="bloque_actividad" value="<?php echo $in;?>">
	<div class="col-sm-6">
		<label>Línea de Vehículo</label>
		<select id="vehiculos_<?php echo $in;?>" class="js-source-states-2" multiple="multiple" style="width: 100%">
			<option value="FIESTA">FIESTA</option>
			<option value="FOCUS">FOCUS</option>
			<option value="FUSION">FUSION</option>
			<option value="MUSTANG">MUSTANG</option>
			<option value="ECOSPORT">ECOSPORT</option>
			<option value="ESCAPE">ESCAPE</option>
			<option value="EDGE">EDGE</option>
			<option value="EXPLORER">EXPLORER</option>
			<option value="RANGER">RANGER</option>
			<option value="F-150">F-150</option>
		</select>
		<input type="checkbox" id="checkbox_<?php echo $in;?>" > Seleccionar Todos
		<br>
		<br>
		<!--<label>Actividad</label>-->
		<select class="actividad requerido form-control" style="display: none;">
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
				<option value="<?php echo($media['Media']['id']);?>"><?php echo($media['Media']['name']);?></option>
			<?php }?>
			
			<!--<optgroup label="ATL">
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
		
		<div class="row bloque_productos" hidden>
			<div class="col-sm-12"><hr></div>
			<div>
				<div class="col-sm-12">
					<div class="col-sm-6">
						<label><sup>Producto</sup></label>
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
		<input type="text" class="monto requerido form-control"></input>
		<br>
		<label>Observaciones</label>
		<textarea type="text" class="requerido observaciones form-control" rows="5"></textarea>
	</div>
	<div class="col-sm-6">
		<label>Fecha de Ejecución</label>
		<div class="col-sm-12"><br></div>
		<div class="col-sm-12">
			<div class="col-sm-6">
				<input type="radio" checked="" value="1" name="optionsRadios<?php echo $in;?>"> Individuales 
			</div>
			<div class="col-sm-6">
				<input type="radio" value="2" name="optionsRadios<?php echo $in;?>"> Rango 
			</div>
		</div>
		<div class="col-sm-12"><br></div>
		<div class="fechas"></div>
		<input type="button" class="mas btn btn-primary pull-right" value="+" style="width: 35px;margin-left:5px;">
		<input type="button" class="menos btn btn-danger pull-right" value="-" style="width:35px;">
	</div>
	<div class="col-sm-12"><br><hr></div>
</div>

<script>
	$("#vehiculos_<?php echo $in;?>").select2();
	$("#checkbox_<?php echo $in;?>").click(function(){
	    if($("#checkbox_<?php echo $in;?>").is(':checked') ){
	        $("#vehiculos_<?php echo $in;?> > option").prop("selected","selected");
	        $("#vehiculos_<?php echo $in;?>").trigger("change");
	    }else{
	        $("#vehiculos_<?php echo $in;?> > option").removeAttr("selected");
	         $("#vehiculos_<?php echo $in;?>").trigger("change");
	     }
	});
	$('ul.select2-choices').on('click',function(){
    	$(this).attr('style','border-color:#e4e5e7 !important;');
    });
</script>
