<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Usuario'); ?></h3>
</div>
<div class="panel-body">
<div class="groups view col-sm-9">
	<dl>
		<dt><?php echo __('Nombre'); ?></dt>
		<dd>
			<?php echo h($user['User']['first_name']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Apellido'); ?></dt>
		<dd>
			<?php echo h($user['User']['last_name']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Usuario'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Correo'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Tipo'); ?></dt>
		<dd>
			<?php echo h($user['UserType']['name']).'<br>';//echo $this->Html->link($user['UserType']['name'], array('controller' => 'user_types', 'action' => 'view', $user['UserType']['id'])).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Grupos'); ?></dt>
		<dd>
			<?php 
				if(count($group) > 0)
				foreach($group as &$g) echo h($g['name']).'<br>'; 
				else echo 'Sin grupo asignado<br>';
			?>
			&nbsp;
		</dd>
		<dt><?php echo __('Creado'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Actualizado'); ?></dt>
		<dd>
			<?php echo h($user['User']['updated']).'<br>'; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Acciones'); ?></h3>
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-pen"></i>', array('action' => 'edit', $user['User']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?> 
		<?php echo $this->Form->postLink('<i style="font-size: 20px;" class="pe-7s-trash"></i>', array('action' => 'delete', $user['User']['id']),array('escape'=>false), __('¿Está seguro que desea borrar a %s?', $user['User']['first_name'])).'&nbsp;&nbsp;&nbsp;'; ?>
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-menu"></i>', array('action' => 'index'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?> 
		<?php echo $this->Html->link('<i style="font-size: 20px;" class="pe-7s-plus"></i>', array('action' => 'add'),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?> 
</div>
</div>
</div>
</div>
</div>
</div>
</div>