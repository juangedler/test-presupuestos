<div class="groups view">
<h2><?php echo __('Group'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($group['Group']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($group['GroupType']['name'], array('controller' => 'group_types', 'action' => 'view', $group['GroupType']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($group['Group']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($group['Group']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($group['Group']['updated']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('City'); ?></dt>
		<dd>
			<?php echo h($group['Group']['city']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group'), array('action' => 'edit', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group'), array('action' => 'delete', $group['Group']['id']), array(), __('Are you sure you want to delete # %s?', $group['Group']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Types'), array('controller' => 'group_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Type'), array('controller' => 'group_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Action Targets'), array('controller' => 'action_targets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Action Target'), array('controller' => 'action_targets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Activity Targets'), array('controller' => 'activity_targets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Activity Target'), array('controller' => 'activity_targets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Balances'), array('controller' => 'balances', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Balance'), array('controller' => 'balances', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Movements'), array('controller' => 'movements', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Movement'), array('controller' => 'movements', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Processes'), array('controller' => 'processes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Process'), array('controller' => 'processes', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Action Targets'); ?></h3>
	<?php if (!empty($group['ActionTarget'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Action Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['ActionTarget'] as $actionTarget): ?>
		<tr>
			<td><?php echo $actionTarget['id']; ?></td>
			<td><?php echo $actionTarget['action_id']; ?></td>
			<td><?php echo $actionTarget['group_id']; ?></td>
			<td><?php echo $actionTarget['user_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'action_targets', 'action' => 'view', $actionTarget['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'action_targets', 'action' => 'edit', $actionTarget['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'action_targets', 'action' => 'delete', $actionTarget['id']), array(), __('Are you sure you want to delete # %s?', $actionTarget['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Action Target'), array('controller' => 'action_targets', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Activity Targets'); ?></h3>
	<?php if (!empty($group['ActivityTarget'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Activity Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['ActivityTarget'] as $activityTarget): ?>
		<tr>
			<td><?php echo $activityTarget['id']; ?></td>
			<td><?php echo $activityTarget['activity_id']; ?></td>
			<td><?php echo $activityTarget['group_id']; ?></td>
			<td><?php echo $activityTarget['user_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'activity_targets', 'action' => 'view', $activityTarget['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'activity_targets', 'action' => 'edit', $activityTarget['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'activity_targets', 'action' => 'delete', $activityTarget['id']), array(), __('Are you sure you want to delete # %s?', $activityTarget['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Activity Target'), array('controller' => 'activity_targets', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Balances'); ?></h3>
	<?php if (!empty($group['Balance'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Balance'); ?></th>
		<th><?php echo __('Pending'); ?></th>
		<th><?php echo __('Group Id1'); ?></th>
		<th><?php echo __('Group Group Type Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['Balance'] as $balance): ?>
		<tr>
			<td><?php echo $balance['id']; ?></td>
			<td><?php echo $balance['group_id']; ?></td>
			<td><?php echo $balance['balance']; ?></td>
			<td><?php echo $balance['pending']; ?></td>
			<td><?php echo $balance['group_id1']; ?></td>
			<td><?php echo $balance['group_group_type_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'balances', 'action' => 'view', $balance['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'balances', 'action' => 'edit', $balance['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'balances', 'action' => 'delete', $balance['id']), array(), __('Are you sure you want to delete # %s?', $balance['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Balance'), array('controller' => 'balances', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Movements'); ?></h3>
	<?php if (!empty($group['Movement'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Request Id'); ?></th>
		<th><?php echo __('Type'); ?></th>
		<th><?php echo __('Amount'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Group Group Type Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['Movement'] as $movement): ?>
		<tr>
			<td><?php echo $movement['id']; ?></td>
			<td><?php echo $movement['request_id']; ?></td>
			<td><?php echo $movement['type']; ?></td>
			<td><?php echo $movement['amount']; ?></td>
			<td><?php echo $movement['group_id']; ?></td>
			<td><?php echo $movement['group_group_type_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'movements', 'action' => 'view', $movement['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'movements', 'action' => 'edit', $movement['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'movements', 'action' => 'delete', $movement['id']), array(), __('Are you sure you want to delete # %s?', $movement['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Movement'), array('controller' => 'movements', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Processes'); ?></h3>
	<?php if (!empty($group['Process'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($group['Process'] as $process): ?>
		<tr>
			<td><?php echo $process['id']; ?></td>
			<td><?php echo $process['name']; ?></td>
			<td><?php echo $process['created']; ?></td>
			<td><?php echo $process['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'processes', 'action' => 'view', $process['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'processes', 'action' => 'edit', $process['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'processes', 'action' => 'delete', $process['id']), array(), __('Are you sure you want to delete # %s?', $process['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Process'), array('controller' => 'processes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
