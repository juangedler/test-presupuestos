<link rel="stylesheet" href="<?php echo $this->Html->url('/cms/vendor/datatables_plugins/integration/bootstrap/3/dataTables.bootstrap.css');?>" />


<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Group Types'); ?></h3>
</div>
<div class="panel-body">
<div class="groups index">
	<table id ="table" class="table" cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th>Name<?php //echo $this->Paginator->sort('name'); ?></th>
			<th>Description<?php //echo $this->Paginator->sort('description'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($groupTypes as $groupType): ?>
	<tr>
		<td><?php echo h($groupType['GroupType']['name']); ?>&nbsp;</td>
		<td><?php echo h($groupType['GroupType']['description']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $groupType['GroupType']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $groupType['GroupType']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $groupType['GroupType']['id']), array('confirm' => __('Are you sure you want to delete %s?', $groupType['GroupType']['name']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Group Type'), array('action' => 'add')); ?></li>
	</ul>
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
    $('#table').dataTable();
});

</script>