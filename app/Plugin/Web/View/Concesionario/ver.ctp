<?php echo $this->Html->css('Web.custom'); ?>
<?php echo $this->Html->css('Web.bootstrap.js'); ?>
<?php echo $this->Html->script('Web.jquery.form.js'); ?>

<?php
	switch ($option) {
		case 1:
			$title = __('Solicitudes Pendientes');
			break;
		case 3:
			$title = __('Solicitudes Aprobadas');
			break;
		case 4:
			$title = __('Solicitudes Rechazadas');
			break;
		case 999:
			$title = __('Solicitudes Anuladas');
			break;
	}
?>

<div class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo $title;?></h3>
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
				<input id="balance" type="text" class="form-control" readonly="" style="text-align:right;">
				<label>Presupuesto Pendiente</label>
				<input id="pending" type="text" class="form-control" readonly="" style="text-align:right;">
			</div>
		</div>
		</div>
		</div>
		</div>
	</div>

	<div id="solicitudes_pendientes" class="row">
	    <div class="col-lg-12">
	        <div class="hpanel">
	            <div class="panel-body">
            		<br>
	                <div class="groups index col-sm-12">
	                	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	                	</div>
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
            <div id="modal-body-1" class="modal-body">
            </div>
            <div id="modal-footer-1" class="modal-footer">
                <button id="okey" type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script>
	var arrayBalance = [];
	var arrayPending = [];

	<?php
		foreach($groups as &$g) {
			echo 'arrayBalance['.$g['Group']['id'].']="'.$g['Balance'].'";';
			echo 'arrayPending['.$g['Group']['id'].']="'.$g['Pending'].'";';
		}
	?>

	$('#concesionario').on('change',function(){
        $('#accordion').html('<img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">');

		$('#balance').val(arrayBalance[$(this).val()]);
		$('#pending').val(arrayPending[$(this).val()]);
		$('#balance').mask('#.##0', {reverse: true});
		$('#pending').mask('#.##0', {reverse: true});
		$('#balance').val('$'+$('#balance').val());
		$('#pending').val('$'+$('#pending').val());
		$('#concesionario_modal').html($('#concesionario :selected').html());

		$.ajax({
	  		method: "POST",
	  		data: { 
	  			grupo: $(this).val(),
	  		},
	        url: "<?php echo $this->Html->url('solicitudes').'/'.$option;?>",
	        success: function(data){
		        $('#accordion').html(data);
				$('.money').mask('#.##0', {reverse: true});
	        }
	  	});
	});

	$('#accordion').on('click', '#consultar', function(){
    	$('#modal-body-1').html('<img id="loading" style="display: block; margin-left: auto; margin-right: auto;" src="<?php echo $this->Html->url("/cms/images/loader.gif");?>" width="30" height="30">');
		$.ajax({
			method:'POST',
	        url: "<?php echo $this->Html->url('consultar');?>/"+$(this).attr('value'),
	        success: function(data){
	        	$('#modal-body-1').html(data);
				$('.moneh').mask('#.##0', {reverse: true});
				$('.modal').data('bs.modal').handleUpdate();
	        }
		});
	});
</script>