<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Nuevo Usuario'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<?php echo $this->Form->create('User'); ?>
	<?php
		echo '<div class="col-sm-10">'.$this->Form->input('first_name',array('label'=>'Nombre','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('last_name',array('label'=>'Apellido','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('username',array('label'=>'Usuario','class'=>'form-control')).'<span id="err" class="help-block m-b-none" style="color:red;display:none;">Nombre de usuario existente, seleccione otro.</span><br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('email',array('label'=>'Correo','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('user_type_id',array('label'=>'Tipo','class'=>'form-control')).'<br></div>';

		echo '<div id="grupos" class="col-sm-10">'.$this->Form->input('groups',array('label'=>'Grupos','multiple' => 'checkbox','class'=>'form-control roles')).'<br></div>';
	?>
<?php 
	echo '<div class="col-sm-10"><div class="col-sm-6" align="center"><br>'.$this->Html->link('<input class="btn btn-default" value="Cancelar" style="width:100px;"></input>', array('action' => 'index'),array('escape'=>false)).'</div>';
	echo '<div class="col-sm-6" align="center"><br>'.$this->Form->submit('Enviar', array('class'=>'btn btn-primary', 'style'=>'width:100px;', 'id'=>'submit')).'</div></div>';

	echo $this->Form->end();
?>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-menu"></i>', array('action' => 'index'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	$('#UserUserTypeId').prepend('<option selected></option>');
	$('#UserUserTypeId').attr('required','');
	$('#grupos').fadeOut(0);
	tipo_de_rol = 0;
	$('#UserUserTypeId').on('change',function(){
		tipo_de_rol = $(this).val();
		$('.roles input').parent().fadeIn(0);
		$('.roles input').attr('checked', false);
		$('#grupos').fadeOut(0);
		$.ajax({
			url: "<?php echo $this->Html->url('pertenece');?>/"+$(this).val(),
			success: function(data){
				datos = $.parseJSON(data);
				console.log(datos);
				if(data.length > 0) {
					$('.roles input').each(function(){
						if($.inArray($(this).val(), datos.GroupsByType) == -1) $(this).parent().fadeOut(0);
						if(tipo_de_rol == 6)  $(this).attr('type','radio');
						else $(this).attr('type','checkbox');
					});
				}
				$('#grupos').fadeIn(0);
			}
		});
	});

	var user_valid = false;
	$('#UserUsername').on('keyup change',function(){
		$.ajax({
        	url: "<?php echo $this->Html->url('validar/');?>"+$(this).val(),
            success: function(data){
                if(data == 'exist'){
                	$( '#UserUsername' ).each(function () {
					    this.style.setProperty( 'border-color', 'red', 'important' );
					});
					$('#err').fadeIn(0);
					user_valid = false;
				}
                else{
                	$('#UserUsername').each(function () {
					    this.style.setProperty( 'border-color', '#e4e5e7', 'important' );
					});
					$('#err').fadeOut(0);
					user_valid = true;
				}
        	}
      	});
	});

	$('form').on('submit', function(){if(!user_valid)return false;});

	$('#submit').on('click',function(){
		var ninguno = false;
		$('input:checkbox').each(function(){
			ninguno = ninguno || $(this).is(':checked');
		});
		$('input:radio').each(function(){
			ninguno = ninguno || $(this).is(':checked');
		});
		if(!ninguno) alert('Por favor seleccione al menos un grupo.');
		return ninguno;
	});
</script>