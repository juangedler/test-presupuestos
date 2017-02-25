<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Nuevo Grupo'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<?php echo $this->Form->create('Group'); ?>
	<?php
		echo '<div class="col-sm-10">'.$this->Form->input('name',array('label'=>'Nombre','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('group_type_id',array('label'=>'Tipo','class'=>'form-control')).'<br></div>';
		echo '<div class="col-sm-10">'.$this->Form->input('city',array('label'=>'Ciudad','class'=>'form-control')).'<br></div>';
		//echo '<div class="col-sm-10">'.$this->Form->input('Process',array('label'=>'Proceso','multiple' => 'checkbox','class'=>'form-control')).'<br></div>';
	?>
<?php 
	echo '<div class="col-sm-10"><div class="col-sm-6" align="center"><br>'.$this->Html->link('<input class="btn btn-default" value="Cancelar" style="width:100px;"></input>', array('action' => 'index'),array('escape'=>false)).'</div>';
	echo '<div class="col-sm-6" align="center"><br>'.$this->Form->submit('Enviar', array('class'=>'btn btn-primary', 'style'=>'width:100px;')).'</div></div>';

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
