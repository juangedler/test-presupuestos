<div id ="main" class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Nuevo Flujo de Pautas Mindshare');?></h3>
		</div>
		<div class="panel-body">
			<form method="post" enctype="multipart/form-data">
				<div class="col-sm-6">
					<label>Nombre</label>
					<input id="nombre" name="nombre" type="text" class="form-control" required></input>
				</div>
				<div class="col-sm-6">
					<label>Valor total (IVA incluido)</label>
					<input id="monto" name="monto2" type="text" class="form-control" required></input>
					<input id="monto2" style="display:none;" name="monto" type="text" class="form-control"></input>
				</div>
				<div class="col-sm-12">
					<br>
					<label>Descripción</label>
					<textarea id="descripcion" name="descripcion" type="text" class="form-control" required rows="4"></textarea>
					<br>
					<label>Archivo Excel</label>
	                <input type="file" title="Por favor seleccione un archivo" required value="" name="archivo" id="archivo" class="form-control" accept=".xlsx">
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
                <div id="mensaje_exito"></div>
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
	$message = $this->Session->flash();
    if($message){
?>
	$('#mensaje_exito').html('<?=$message?>');
	$('#myModal').modal('show');
	$('#myModal').on('hidden.bs.modal', function () {
		location.href = "<?php echo $this->Html->url('listar');?>/1"
	});
<?php
    }
?>

$('#monto').mask('#.##0', {reverse: true});

$('#monto').on('change',function(){
	$(this).unmask();
	$('#monto2').val($(this).val());
	$(this).mask('#.##0', {reverse: true});
});

</script>