<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Editar Usuario'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<?php echo $this->Form->create('User'); ?>
	<?php
		echo $this->Form->input('id');
		echo '<div class="col-sm-10">'.$this->Form->input('first_name',array('label'=>'Nombre','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('last_name',array('label'=>'Apellido','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('username',array('label'=>'Usuario','class'=>'form-control')).'<span id="err" class="help-block m-b-none" style="color:red;display:none;">Nombre de usuario existente, seleccione otro.</span><br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('email',array('label'=>'Correo','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('user_type_id',array('label'=>'Tipo','class'=>'form-control')).'<br></div>';
	?>
<?php 
	echo '<div class="col-sm-10"><div class="col-sm-6" align="center"><br>'.$this->Html->link('<input class="btn btn-default" value="Cancelar" style="width:100px;"></input>', array('action' => 'index'),array('escape'=>false)).'</div>';
	echo '<div class="col-sm-6" align="center"><br>'.$this->Form->submit('Enviar', array('class'=>'btn btn-primary', 'style'=>'width:100px;')).'</div></div>';

	echo $this->Form->end();
?>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-key" title="Restablecer Contraseña"></i>', array('action' => 'reset_password', $this->Form->value('User.id')),array('escape'=>false), __('¿Está seguro que desea restablecer la contraseña de %s?', $this->Form->value('User.first_name'))).'&nbsp;&nbsp;&nbsp;'; ?>
	<?php echo $this->Form->postLink('<i style="font-size: 20px;" class="pe-7s-trash" title="Eliminar Usuario"></i>', array('action' => 'delete', $this->Form->value('User.id')),array('escape'=>false), __('¿Está seguro que desea borrar a %s?', $this->Form->value('User.first_name'))).'&nbsp;&nbsp;&nbsp;'; ?>
	<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-menu" title="Listar Usuarios"></i>', array('action' => 'index'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	var user = $('#UserUsername').val();
	var user_valid = true;
	$('#UserUsername').on('keyup change',function(){
		if($(this).val()!= user)
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
                	$( '#UserUsername' ).each(function () {
					    this.style.setProperty( 'border-color', '#e4e5e7', 'important' );
					});
					$('#err').fadeOut(0);
					user_valid = true;
				}
        	}
      	});
	});

	$('form').on('submit', function(){
		if(!user_valid)return false;}
	);
</script>