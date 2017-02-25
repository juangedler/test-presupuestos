<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Nuevo Miembro de Grupo'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<?php echo $this->Form->create('GroupMember'); ?>
	<?php
		echo '<div class="col-sm-10">'.$this->Form->input('user_id',array('label'=>'Usuario','class'=>'form-control')).'<br></div>';
		echo '<div id="grupos" class="col-sm-10">'.$this->Form->input('group_id',array('label'=>'Grupo','class'=>'form-control')).'<br></div>';
	?>
	<div class="col-sm-12"><p id="error" hidden> No hay grupos al que pueda agregarse.</p></div>
<?php 
	echo '<div class="col-sm-10"><br>'.$this->Form->submit('Enviar', array('class'=>'btn btn-primary')).'</div>'; 
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
	$('#GroupMemberUserId').prepend('<option selected></option>');
	$('#GroupMemberGroupId').prepend('<option selected></option>');
	$('#GroupMemberUserId').attr('required','');
	$('#GroupMemberGroupId').attr('required','');
	
	$('#GroupMemberGroupId').fadeOut(0);
	$('#GroupMemberUserId').on('change',function(){
		$('option').fadeIn(0);
		$('#error').fadeOut(0)
		$('#GroupMemberGroupId').fadeOut(0);
		$.ajax({
			url: "<?php echo $this->Html->url('pertenece');?>/"+$(this).val(),
			success: function(data){
				datos = $.parseJSON(data);
				console.log(datos);
				if(data.length > 0) {
					$('#GroupMemberGroupId option').each(function(){
						if($.inArray($(this).val(), datos.GroupsByType) != -1){
							if($.inArray($(this).val(), datos.GroupsIsIn) != -1) $(this).fadeOut(0);
						}
						else $(this).fadeOut(0);
					});
				}
				$('#GroupMemberGroupId').fadeIn(0);
			}
		});
	});
</script>