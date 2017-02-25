<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.css')?>" />

<div class="content ">
	<div class="row">
		<div class="col-lg-12">
		<div class="hpanel hblue">
		<div class="panel-heading hbuilt">
			<h3>Edición de Perfil</h3>
		</div>
		<div class="panel-body">
		<div class="groups col-sm-7">
			<div class="col-sm-10">
				<form method="post">
					<label>Nombre</label>
					<input type="text" id="nombre" name="first_name" class="form-control" value="<?php echo $user['User']['first_name']?>" required>
					<p id="error4" hidden style="color:red;">Caracteres inválidos. Por favor utilice letras para su nombre empezando con mayúscula.</p>
					<br>
					<label>Apellido</label>
					<input type="text" id="apellido" name="last_name" class="form-control" value="<?php echo $user['User']['last_name']?>" required>
					<p id="error5" hidden style="color:red;">Caracteres inválidos. Por favor utilice letras para su apellido empezando con mayúscula.</p>
					<br>
					<div align="center"><input id="editar" value="Editar" class="btn btn-primary"  type="submit" style="width:100px;"></div>
				</form>
				<form method="post">
					<hr>
					<label>Cambiar Contraseña</label>
					<input type="password" id="password1" name="password" class="form-control" required>
					<p id="error1" hidden style="color:red;">Su contraseña debe contener al menos 8 caracteres, entre ellos una letra mayúscula, una letra minúscula y un número.</p>
					<br>
					<label>Confirme Contraseña</label>
					<input type="password" id="password2" name="password2" class="form-control" required>
					<p id="error2" hidden style="color:red;">Las contraseñas no coinciden.</p>
                    <p id="error3" hidden style="color:red;">Su contraseña debe contener al menos 8 caracteres, entre ellos una letra mayúscula, una letra minúscula y un número.</p>
					<br>
					<div align="center"><input id="enviar" value="Enviar" class="btn btn-primary" type="submit" style="width:100px;"></div>
					<br>
					<br>
				</form>
			</div>
		</div>

		<?php
			if($this->Session->read('type') != 'Concesionario'){
		?>

		<div class="groups col-sm-5">
			<div class="col-sm-12">
				<label>Firma Digital</label>
				<br>
				<br>
				<img src="<?php echo Router::url('/') .$user['User']['signature'];?>" style='width:100%;'>
				<br>
				<br>
				<div align="center"><a class="btn btn-primary" id="modificar" style="width:100px;">Modificar</a></div>
				<div id="nueva_firma" hidden>
					<hr>
	                <div align="center"><img hidden id="blah" src="#" alt="your image" style='width:100%;'/></div>
					<br>
					<form method="post" enctype="multipart/form-data">
		                <input type="file" title="Por favor seleccione un archivo" required="" value="" name="signature" id="signature" class="form-control" accept=".jpg,.jpeg">
						<br>
						<div align="center" id="image_btn" hidden>
							<input value="Modificar" type="submit" class="btn btn-primary" id="subir" style="width:100px;">
							<a class="btn btn-default" id="cancelar" style="width:100px;">Cancelar</a>
						</div>
					</form>
				</div>
			</div>
		</div>

		<?php
			}
		?>

		</div>
		</div>
		</div>
	</div>
</div>

<button class="homerDemo btn" style="display:none;"></button>

<script src="<?php echo $this->Html->url('/cms/vendor/toastr/build/toastr.min.js')?>"></script>

<script>
        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "positionClass": "toast-top-center",
            "closeButton": true,
            "debug": false,
            "toastClass": "animated fadeInDown",
        };

<?php 
    if($actualizacion){
?>
	$('.homerDemo').click(function (){
	    toastr.success('<?php echo $this->Session->flash();?>');
	});

    $('.homerDemo').click();
<?php
    }
?>

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
        $('#image_btn').fadeIn(0);
    }
    else {
        $('#image_btn').fadeOut(0);
    	$('#blah').fadeOut(0);
    }
}

$("#signature").change(function(){
    $('#blah').fadeIn(0);
    readURL(this);
});

$('#modificar').on('click',function(){
	$(this).fadeOut(0);
	$('#nueva_firma').fadeIn(0);
});

$('#cancelar').on('click',function(){
	$('#modificar').fadeIn(0);
	$('#nueva_firma').fadeOut(0);
	readURL('');
});

$('#nombre').on('change',function(){
	if(!/^[ ]+$/.test($(this).val()) && $(this).val()!=''){
		if(!/^[ÁÉÍÓÚÜÑA-Z][-'ÁÉÍÓÚÜÑáéíóúüña-zA-Z ]+$/.test($(this).val())){
	        $('#error4').fadeIn(0);
	        $(this).attr('style','border-color: red !important;');
	    }
	    else{
	        $('#error4').fadeOut(0);
	        $(this).attr('style','border-color: #e4e5e7 !important;');
	    }
	}
	else {
		$(this).val('');
		$('#error4').fadeOut(0);
		$(this).attr('style','border-color: #e4e5e7 !important;');
	}
});

$('#apellido').on('change',function(){
	if(!/^[ ]+$/.test($(this).val()) && $(this).val()!=''){
	    if(!/^[ÁÉÍÓÚÜÑA-Z ][-'ÁÉÍÓÚÜÑáéíóúüña-zA-Z ]+$/.test($(this).val())){
	        $('#error5').fadeIn(0);
	        $(this).attr('style','border-color: red !important;');
	    }
	    else{
	        $('#error5').fadeOut(0);
	        $(this).attr('style','border-color: #e4e5e7 !important;');
	    }
    }
	else {
		$(this).val('');
		$('#error5').fadeOut(0);
		$(this).attr('style','border-color: #e4e5e7 !important;');
	}
});

$('#password1').on('change',function(){
    if(!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test($(this).val())){
        $('#error1').fadeIn(0);
        $(this).attr('style','border-color: red !important;');
    }
    else{
        $('#error1').fadeOut(0);
        $(this).attr('style','border-color: #e4e5e7 !important;');
    }
});

$('#password2').on('change',function(){
    if(!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test($(this).val())){
        $('#error3').fadeIn(0);
        $(this).attr('style','border-color: red !important;');
    }
    else{
        $('#error3').fadeOut(0);
        $(this).attr('style','border-color: #e4e5e7 !important;');
        if($('#password1').val() != $('#password2').val())
            $('#error2').fadeIn(0);
        else
            $('#error2').fadeOut(0);
    }
});

$('#enviar').on('click',function(){
    if($('#password1').val() != '' && $('#password2').val() != ''){
        if($('#password1').val() != $('#password2').val()) {
            return false;
        }
        else if(!/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/.test($('#password1').val()))
            return false;
    }
});

$('#editar').on('click',function(){
	if((!/^[ ]+$/.test($('#nombre').val()) && $('#nombre').val()!='') && (!/^[ ]+$/.test($('#apellido').val()) && $('#apellido').val()!=''))
	if(!/^[ÁÉÍÓÚÜÑA-Z ][-'ÁÉÍÓÚÜÑáéíóúüña-zA-Z ]+$/.test($('#nombre').val()) || !/^[ÁÉÍÓÚÜÑA-Z ][-'ÁÉÍÓÚÜÑáéíóúüña-zA-Z ]+$/.test($('#apellido').val())){
        return false;
    }
});


</script>