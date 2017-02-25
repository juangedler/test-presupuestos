<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Migración de elementos del Usuario'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<div class="col-sm-10">
<p><?php echo __('Seleccione un usuario de cada grupo para asignarle todos los elementos (solicitudes y comentarios realizados) del usuario a eliminar.'); ?></p>
<p><?php echo __('Si en uno o mas grupos no existe ningún usuario, cree un nuevo usuario y asígnelo al grupo antes de proceder.'); ?></p>
<br>
</div>
	<form action="<?php echo $this->Html->url('delete').'/'.$id;?>" method="post">
	<?php
		echo '<div class="col-sm-10">';
		foreach ($groups as $g) {
			echo '<label>'.$g['Group']['name'].'</label>';
			echo '<select name="'.$g['Group']['id'].'" class="form-control" required>';
			echo '<option selected=""></option>';
			foreach ($g['Users'] as $u) {
				if($u['User']['id'] != $id)
				echo '<option value="'.$u['User']['id'].'">'.$u['User']['first_name'].' '.$u['User']['last_name'].'</option>';
			}
			echo '</select><br>';
		}
		echo '</div>';

		echo '<div class="col-sm-10"><div class="col-sm-6" align="center"><br>'.$this->Html->link('<input class="btn btn-default" value="Cancelar" style="width:100px;"></input>', array('action' => 'index'),array('escape'=>false)).'</div>';
		echo '<div class="col-sm-6" align="center"><br>'.$this->Form->submit('Enviar', array('class'=>'btn btn-primary', 'style'=>'width:100px;')).'</div></div>';
	?>
	</form>

</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-menu" title="Listar Usuarios"></i>', array('action' => 'index'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
	$('form').on('submit', function(){
		$('select').each(function(){
			if($(this).val()==''){
				$(this).attr('style','border-color: red !important;');
			} 
		});
	});
</script>