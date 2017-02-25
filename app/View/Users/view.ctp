<div class="users view">
<h2><?php echo __('User'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('First Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['first_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Name'); ?></dt>
		<dd>
			<?php echo h($user['User']['last_name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Username'); ?></dt>
		<dd>
			<?php echo h($user['User']['username']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Password'); ?></dt>
		<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Email'); ?></dt>
		<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Signature'); ?></dt>
		<dd>
			<?php echo h($user['User']['signature']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Updated'); ?></dt>
		<dd>
			<?php echo h($user['User']['updated']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User Type'); ?></dt>
		<dd>
			<?php echo $this->Html->link($user['UserType']['name'], array('controller' => 'user_types', 'action' => 'view', $user['UserType']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), array(), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List User Types'), array('controller' => 'user_types', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User Type'), array('controller' => 'user_types', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Action Logs'), array('controller' => 'action_logs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Action Log'), array('controller' => 'action_logs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Action Targets'), array('controller' => 'action_targets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Action Target'), array('controller' => 'action_targets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Activity Targets'), array('controller' => 'activity_targets', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Activity Target'), array('controller' => 'activity_targets', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Members'), array('controller' => 'group_members', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Member'), array('controller' => 'group_members', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Requests'), array('controller' => 'requests', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request'), array('controller' => 'requests', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Request Notes'), array('controller' => 'request_notes', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request Note'), array('controller' => 'request_notes', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Request Stakeholders'), array('controller' => 'request_stakeholders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Request Stakeholder'), array('controller' => 'request_stakeholders', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Action Logs'); ?></h3>
	<?php if (!empty($user['ActionLog'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Ip'); ?></th>
		<th><?php echo __('Action Id'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['ActionLog'] as $actionLog): ?>
		<tr>
			<td><?php echo $actionLog['id']; ?></td>
			<td><?php echo $actionLog['user_id']; ?></td>
			<td><?php echo $actionLog['created']; ?></td>
			<td><?php echo $actionLog['ip']; ?></td>
			<td><?php echo $actionLog['action_id']; ?></td>
			<td><?php echo $actionLog['description']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'action_logs', 'action' => 'view', $actionLog['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'action_logs', 'action' => 'edit', $actionLog['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'action_logs', 'action' => 'delete', $actionLog['id']), array(), __('Are you sure you want to delete # %s?', $actionLog['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Action Log'), array('controller' => 'action_logs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Action Targets'); ?></h3>
	<?php if (!empty($user['ActionTarget'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Action Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['ActionTarget'] as $actionTarget): ?>
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
	<?php if (!empty($user['ActivityTarget'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Activity Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['ActivityTarget'] as $activityTarget): ?>
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
	<h3><?php echo __('Related Group Members'); ?></h3>
	<?php if (!empty($user['GroupMember'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Group Id'); ?></th>
		<th><?php echo __('Group Group Type Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['GroupMember'] as $groupMember): ?>
		<tr>
			<td><?php echo $groupMember['user_id']; ?></td>
			<td><?php echo $groupMember['group_id']; ?></td>
			<td><?php echo $groupMember['group_group_type_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'group_members', 'action' => 'view', $groupMember['user_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'group_members', 'action' => 'edit', $groupMember['user_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'group_members', 'action' => 'delete', $groupMember['user_id']), array(), __('Are you sure you want to delete # %s?', $groupMember['user_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Group Member'), array('controller' => 'group_members', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Requests'); ?></h3>
	<?php if (!empty($user['Request'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Number'); ?></th>
		<th><?php echo __('Request Type Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Process Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Current State Id'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['Request'] as $request): ?>
		<tr>
			<td><?php echo $request['id']; ?></td>
			<td><?php echo $request['number']; ?></td>
			<td><?php echo $request['request_type_id']; ?></td>
			<td><?php echo $request['user_id']; ?></td>
			<td><?php echo $request['process_id']; ?></td>
			<td><?php echo $request['title']; ?></td>
			<td><?php echo $request['current_state_id']; ?></td>
			<td><?php echo $request['created']; ?></td>
			<td><?php echo $request['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'requests', 'action' => 'view', $request['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'requests', 'action' => 'edit', $request['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'requests', 'action' => 'delete', $request['id']), array(), __('Are you sure you want to delete # %s?', $request['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Request'), array('controller' => 'requests', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Request Notes'); ?></h3>
	<?php if (!empty($user['RequestNote'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Request Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Note'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Updated'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['RequestNote'] as $requestNote): ?>
		<tr>
			<td><?php echo $requestNote['id']; ?></td>
			<td><?php echo $requestNote['request_id']; ?></td>
			<td><?php echo $requestNote['user_id']; ?></td>
			<td><?php echo $requestNote['note']; ?></td>
			<td><?php echo $requestNote['created']; ?></td>
			<td><?php echo $requestNote['updated']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'request_notes', 'action' => 'view', $requestNote['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'request_notes', 'action' => 'edit', $requestNote['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'request_notes', 'action' => 'delete', $requestNote['id']), array(), __('Are you sure you want to delete # %s?', $requestNote['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Request Note'), array('controller' => 'request_notes', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
<div class="related">
	<h3><?php echo __('Related Request Stakeholders'); ?></h3>
	<?php if (!empty($user['RequestStakeholder'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Request Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($user['RequestStakeholder'] as $requestStakeholder): ?>
		<tr>
			<td><?php echo $requestStakeholder['request_id']; ?></td>
			<td><?php echo $requestStakeholder['user_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'request_stakeholders', 'action' => 'view', $requestStakeholder['request_id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'request_stakeholders', 'action' => 'edit', $requestStakeholder['request_id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'request_stakeholders', 'action' => 'delete', $requestStakeholder['request_id']), array(), __('Are you sure you want to delete # %s?', $requestStakeholder['request_id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Request Stakeholder'), array('controller' => 'request_stakeholders', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
