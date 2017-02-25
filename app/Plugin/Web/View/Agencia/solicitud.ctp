<div id ="main" class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Nuevo Presupuesto Publicitario JWT ');?></h3>
		</div>
		<div class="panel-body">
			<form method="post" enctype="multipart/form-data">
				<div class="col-sm-12">
					<label>Producto / Referencia</label>
					<input id="nombre" name="nombre" type="text" class="form-control" required></input>
					<br>
				</div>
				<div class="col-sm-6">
					<label>#Presupuesto Tango</label>
					<input id="consecutivo" name="consecutivo" type="text" class="form-control" required></input>
					<p id="tango-error" style="color:red;display:none;">Este Número de Presupuesto Tango ya está registrado en el sistema. Verifique que su número sea correcto o intente una modificación del presupuesto existente.</p>
				</div>
				<div class="col-sm-6">
					<label>Valor total (sin IVA incluido)</label>
					<input id="monto" name="monto2" type="text" class="form-control" required></input>
					<input id="monto2" style="display:none;" name="monto" type="text" class="form-control"></input>
				</div>
				<div class="col-sm-12">
					<br>
					<label>Descripción</label>
					<textarea id="descripcion" name="descripcion" type="text" class="form-control" required rows="4"></textarea>
					<br>
					<label>Archivo Tango</label>
	                <input type="file" title="Por favor seleccione un archivo" required value="" name="archivo" id="archivo" class="form-control" accept=".pdf,.xml">
					<br>
					<div align="center">
						<input value="Enviar" type="submit" class="btn btn-primary" id="subir" style="width:100px;">
						<a class="btn btn-default" id="cancelar" style="width:100px;">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
		</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div id="mensaje_exito"><p>Solicitud creada con éxito.</p></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->Html->url('/web/js/jquery.mask.min.js');?>"></script>
<script>
<?php 
    if(isset($actualizacion) && $actualizacion){
?>
	$('#myModal').modal('show');
	$('#myModal').on('hidden.bs.modal', function () {
		location.href = "<?php echo $this->Html->url('listar');?>/1"
	});
<?php
    }
?>

<?php 
    if(isset($message) && $message != ''){
?>
	$('#mensaje_exito').html("<?=$message?>");
	$('#myModal').modal('show');	
<?php
    }
?>

$('#monto').mask('#.##0', {reverse: true});

$('#monto').on('change',function(){
	$(this).unmask();
	$('#monto2').val($(this).val());
	$(this).mask('#.##0', {reverse: true});
});

var tango_valid = false;
$('#consecutivo').on('change',function(){
	$.ajax({
    	url: "<?php echo $this->Html->url('validar/');?>"+$(this).val(),
        success: function(data){
            if(data == 'exist'){
            	$( '#consecutivo' ).each(function () {
				    this.style.setProperty( 'border-color', 'red', 'important' );
				    $('#tango-error').fadeIn(0);
				});
				tango_valid = false;
			}
            else{
            	$('#consecutivo').each(function () {
				    this.style.setProperty( 'border-color', '#e4e5e7', 'important' );
				    $('#tango-error').fadeOut(0);
				});
				tango_valid = true;
			}
    	}
  	});
});

$('form').on('submit', function(){if(!tango_valid)return false;});
</script>