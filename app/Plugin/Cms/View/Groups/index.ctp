<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Grupos'); echo $this->Html->link('<i style="font-size: 30px;" title="Nuevo Grupo"class="pe-7s-plus pull-right"></i>', array('action' => 'add'),array('escape'=>false));?></h3>
</div>
<div class="panel-body">
<div class="groups index col-sm-12">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th>Nombre<?php //echo $this->Paginator->sort('name'); ?></th>
			<th>Tipo<?php //echo $this->Paginator->sort('group_type_id'); ?></th>
			<th>Ciudad<?php //echo $this->Paginator->sort('city'); ?></th>
			<th class="actions"><?php echo __('Acciones'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groups as $group): ?>
	<tr>
		<td><?php echo h($group['Group']['name']); ?>&nbsp;</td>
		<td>
			<?php echo h($group['GroupType']['name']); ?>
		</td>
		<td><?php echo h($group['Group']['city']); ?>&nbsp;</td>
		<td class="actions" align="center">
			<?php echo $this->Html->link('<i class="pe-7s-search" title="Ver"></i>', array('action' => 'view', $group['Group']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
			<?php echo $this->Html->link('<i class="pe-7s-pen" title="Editar"></i>', array('action' => 'edit', $group['Group']['id']),array('escape'=>false)).'&nbsp;&nbsp;&nbsp;'; ?>
			<?php echo $this->Form->postLink('<i class="pe-7s-trash" title="Borrar"></i>', array('action' => 'delete', $group['Group']['id']),array('escape'=>false), __('¿Está seguro que desea borrar a %s?', $group['Group']['name'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>
<div class="actions col-sm-12">
	<h3><?php echo __('Acciones'); ?></h3>
	<?php echo $this->Html->link('<i style="font-size: 30px;" title="Nuevo Grupo" class="pe-7s-plus"></i>', array('action' => 'add'),array('escape'=>false)); ?>
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