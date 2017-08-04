<div class="photos form">
<?php echo $this->Form->create('Photo'); ?>
	<fieldset>
		<legend><?php echo __('Admin Add Photo'); ?></legend>
	<?php
		echo $this->Form->input('eyeEm');
		echo $this->Form->input('results');
		echo $this->Form->input('amazonUrl');
		echo $this->Form->input('size');
		echo $this->Form->input('filename');
		echo $this->Form->input('rating');
		echo $this->Form->input('user_id');
		echo $this->Form->input('Tag');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Photos'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
