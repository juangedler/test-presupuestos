<div class="content animate-panel">
<div class="row">
<div class="col-lg-12">
<div class="hpanel hblue">
<div class="panel-heading hbuilt">
	<h3><?php echo __('Group Type'); ?></h3>
</div>
<div class="panel-body">
<div class="groups view col-sm-9">
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($groupType['GroupType']['id']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($groupType['GroupType']['name']).'<br>'; ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($groupType['GroupType']['description']).'<br>'; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions col-sm-3">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Group Type'), array('action' => 'edit', $groupType['GroupType']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Group Type'), array('action' => 'delete', $groupType['GroupType']['id']), array(), __('Are you sure you want to delete %s?', $groupType['GroupType']['name'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Group Types'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Group Type'), array('action' => 'add')); ?> </li>
	</ul>
</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>