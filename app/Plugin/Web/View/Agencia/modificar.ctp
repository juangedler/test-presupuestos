<div id ="main" class="content">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Solicitud - Aprobación de Presupuesto');?></h3>
		</div>
		<div class="panel-body">
			<form method="post" enctype="multipart/form-data">
				<input id="id" name="id" type="text" class="form-control" style="display:none;" value="<?php echo $requests['Request']['id'];?>"></input>

				<div class="col-sm-12">
					<label>Producto / Referencia</label>
					<input id="nombre" name="nombre" type="text" class="form-control" required value="<?php echo $requests['Request']['title'];?>"></input>
					<br>
				</div>
				<div class="col-sm-6">
					<label>#Presupuesto Tango</label>
					<input id="consecutivo" name="consecutivo" type="text" class="form-control" value="<?php echo $requests['Request']['number'];?>" readonly=""></input>
				</div>
				<div class="col-sm-6">
					<label>Valor total (IVA incluido)</label>
					<input id="monto" name="monto2" type="text" class="form-control" required value="<?php echo $requests['Request']['amount'];?>"></input>
					<input id="monto2" style="display:none;" name="monto" type="text" class="form-control" value="<?php echo $requests['Request']['amount'];?>"></input>
				</div>
				<div class="col-sm-12">
					<br>
					<label>Descripción</label>
					<textarea id="descripcion" name="descripcion" type="text" class="form-control" required rows="4"><?php echo $requests['RequestFile'][0]['RequestFile']['description'];?></textarea>
					<br>
					<label>Archivo Tango</label>
					<p>Actual: 
					<a  href="<?php echo Router::url('/') . $requests['RequestFile'][0]['RequestFile']['file'];?>" target="_blank">
					<?php
						$file_name = explode('/',$requests['RequestFile'][0]['RequestFile']['file']);
						echo $file_name[count($file_name)-1];
					?>
					</a>
					</p>
	                <input type="file" title="Por favor seleccione un archivo" required value="hola" name="archivo" id="archivo" class="form-control" accept=".pdf,.xml">
					<br>
					<div align="center">
						<input value="Modificar" type="submit" class="btn btn-primary" id="subir" style="width:100px;">
						<a class="btn btn-default" id="cancelar" style="width:100px;" href="<?php echo $this->Html->url('listar').'/1';?>">Cancelar</a>
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
                <div id="mensaje_exito"><p>Solicitud modificada con éxito.</p></div>
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
    if($actualizacion){
?>
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