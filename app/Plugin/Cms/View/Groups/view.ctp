<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Grupo'); ?></h3>
</div>
<div class="panel-body">
<div class="groups view col-sm-9">
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($group['Group']['name']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($group['GroupType']['name']).'<br>';//echo $this->Html->link($group['GroupType']['name'], array('controller' => 'group_types', 'action' => 'view', $group['GroupType']['id'])).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Ciudad'); ?></dt>
		<dd>
			<?php echo h($group['Group']['city']).'<br>'; ?>
			&nbsp;
		</dd>
		<?php
		/*
		<dt><?php echo __('Proceso'); ?></dt>
		<dd>
			<?php 
				if(count($group['Process']) > 0)
				foreach($group['Process'] as &$p) echo h($p['name']).'<br>'; 
				else echo 'Ningún proceso asignado<br>';
			?>
			&nbsp;
		</dd>
		*/
		?>

		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($group['Group']['created']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actualizado'); ?></dt>
		<dd>
			<?php echo h($group['Group']['updated']).'<br>'; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Acciones'); ?></h3>
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-pen"></i>', array('action' => 'edit', $group['Group']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
		<?php echo $this->Form->postLink('<i style="font-size: 20px;" class="pe-7s-trash"></i>', array('action' => 'delete', $group['Group']['id']),array('escape'=>false), __('¿Está seguro que desea borrar a %s?', $group['Group']['name'])).'&nbsp;&nbsp;&nbsp;'; ?>
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-menu"></i>', array('action' => 'index'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?> 
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-plus"></i>', array('action' => 'add'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?> 
</div>

</div>
</div>
</div>
</div>
</div>
</div>
