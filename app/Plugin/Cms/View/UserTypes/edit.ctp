<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Edit User Type'); ?></h3>
</div>
<div class="panel-body">
<div class="groups form form-horizontal col-sm-9">
<?php echo $this->Form->create('UserType'); ?>
	<?php
		echo $this->Form->input('id');
		echo '<div class="col-sm-10">'.$this->Form->input('name',array('class'=>'form-control')).'<br></div>';
	?>
<?php 
	echo '<div class="col-sm-10"><br>'.$this->Form->submit('Submit', array('class'=>'btn btn-primary')).'</div>'; 
	echo $this->Form->end();
?>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('UserType.id')), array(), __('Are you sure you want to delete %s?', $this->Form->value('UserType.name'))); ?></li>
		<li><?php echo $this->Html->link(__('List User Types'), array('action' => 'index')); ?></li>
	</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>