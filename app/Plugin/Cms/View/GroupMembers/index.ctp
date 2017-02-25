<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Miembros de Grupo'); echo $this->Html->link('<i style="font-size: 30px;" title="Nuevo Miembro" class="pe-7s-plus pull-right"></i>', array('action' => 'add'),array('escape'=>false));?></h3>
</div>
<div class="panel-body">
<div class="groups index col-sm-12">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th>Usuario<?php //echo $this->Paginator->sort('user_id'); ?></th>
			<th>Grupo<?php //echo $this->Paginator->sort('group_id'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groupMembers as $groupMember): ?>
	<tr>
		<td>
			<?php echo $this->Html->link($groupMember['User']['username'], array('controller' => 'users', 'action' => 'view', $groupMember['User']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($groupMember['Group']['name'], array('controller' => 'groups', 'action' => 'view', $groupMember['Group']['id'])); ?>
		</td>
		<td class="actions" align="center">
			<?php echo $this->Form->postLink('<i class="pe-7s-trash" title="Borrar"></i>', array('action' => 'delete', $groupMember['GroupMember']['id']),array('escape'=>false), __('¿Está seguro que desea eliminar esta relación?', $groupMember['GroupMember']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>
<div class="actions col-sm-12">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 30px;" class="pe-7s-plus" title="Nuevo Miembro"></i>', array('action' => 'add'),array('escape'=>false)); ?>
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