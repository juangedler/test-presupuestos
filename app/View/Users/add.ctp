<div class="users form">
<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
		echo $this->Form->input('email');
		echo $this->Form->input('signature');
		echo $this->Form->input('user_type_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
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
