<div class="groupMembers view">
<h2><?php echo __('Group Member'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($groupMember['GroupMember']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($groupMember['User']['username'], array('controller' => 'users', 'action' => 'view', $groupMember['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Group'); ?></dt>
		<dd>
			<?php echo $this->Html->link($groupMember['Group']['name'], array('controller' => 'groups', 'action' => 'view', $groupMember['Group']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group Member'), array('action' => 'edit', $groupMember['GroupMember']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group Member'), array('action' => 'delete', $groupMember['GroupMember']['id']), array(), __('Are you sure you want to delete # %s?', $groupMember['GroupMember']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Members'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Member'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Groups'), array('controller' => 'groups', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group'), array('controller' => 'groups', 'action' => 'add')); ?> </li>
	</ul>
</div>
