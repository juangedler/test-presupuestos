<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Usuarios'); echo $this->Html->link('<i style="font-size: 30px;" title="Nuevo Usuario" class="pe-7s-plus pull-right"></i>', array('action' => 'add'),array('escape'=>false)); ?></h3>
</div>
<div class="panel-body">
<div class="groups index col-sm-12">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th>Nombre<?php //echo $this->Paginator->sort('first_name'); ?></th>
			<th>Apellido<?php //echo $this->Paginator->sort('last_name'); ?></th>
			<th>Usuario<?php //echo $this->Paginator->sort('username'); ?></th>
			<th>Correo<?php //echo $this->Paginator->sort('email'); ?></th>
			<th>Tipo<?php //echo $this->Paginator->sort('user_type_id'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['first_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['last_name']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['username']); ?>&nbsp;</td>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo h($user['UserType']['name']); ?>&nbsp;</td>

		<td class="actions" align="center">
			<?php echo $this->Html->link('<i class="pe-7s-search" title="Ver"></i>', array('action' => 'view', $user['User']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
			<?php echo $this->Html->link('<i class="pe-7s-pen" title="Editar"></i>', array('action' => 'edit', $user['User']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
			<?php echo $this->Form->postLink('<i class="pe-7s-trash" title="Borrar"></i>', array('action' => 'delete', $user['User']['id']),array('escape'=>false), __('¿Está seguro que desea borrar a %s?', $user['User']['first_name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>
<div class="actions col-sm-12">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 30px;" title="Nuevo Usuario" class="pe-7s-plus"></i>', array('action' => 'add'),array('escape'=>false)); ?>
</div>
</div>
</div>
</div>
</div>
</div>

<script src="<?php echo $this->Html->url('/cms/vendor/datatables/media/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.min.js');?>"></script>
<script>

$(function () {
    $('#table').dataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.7/i18n/Spanish.json"
            }
        });
});

</script>