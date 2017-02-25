<div id ="main" class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3><?php echo __('Nuevo Registro de Monto Nacional');?></h3>
		</div>
		<div class="panel-body">
			
			<form id="form" method="post" enctype="multipart/form-data">
				<div class="col-sm-3 pull-right">
					<label>Disponible ($)</label>
					<input id="disponible" type="text" class="form-control" readonly="" value="<?php echo $disponible;?>"></input>
					<br>
				</div>
				<div class="col-sm-6 pull-left">
					<label>Nombre</label>
					<input id="nombre" name="nombre" type="text" class="form-control" required></input>
					<br>
					<label>Valor total (IVA incluido)</label>
					<input id="monto" name="monto2" type="text" class="form-control" required></input>
					<input id="monto2" style="display:none;" name="monto" type="text" class="form-control"></input>
				</div>
				<div class="col-sm-12 pull-left">
					<br>
					<label>Descripción</label>
					<textarea id="descripcion" name="descripcion" type="text" class="form-control" required rows="4"></textarea>
					<br>
					<div align="center">
						<input value="Enviar" type="submit" class="btn btn-primary" name="submit_me" style="width:100px;">
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

<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
		      	<button type="button" class="close" data-dismiss="modal" style="margin-right: -20px;margin-top:-15px;">&times;</button>
                <div><p>El monto solicitado excede el monto disponible, por favor ingrese un monto válido.</p></div>
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
		location.href = "<?php echo $this->Html->url('/web/administrador/nacional/listar');?>"
	});
<?php
    }
?>

$('#monto').mask('#.##0', {reverse: true});
$('#disponible').mask('#.##0', {reverse: true});

$('#monto').on('change',function(){
	$(this).unmask();
	$('#monto2').val($(this).val());
	$(this).mask('#.##0', {reverse: true});
});

$("#form").submit(function(e) {
     e.preventDefault();
     //alert($('#monto2').val()+"    "+<?php echo $disponible;?>+"   "+(<?php echo $disponible;?>-$('#monto2').val()));
     //self.submit();
     var disponible = parseFloat(<?php echo $disponible;?>);
     var monto = parseFloat($('#monto2').val());
     
     if(monto < disponible) this.submit();
     else $('#myModal2').modal('show');
     return false;
});
</script>