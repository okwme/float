<div class="users form">
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend><?php echo __('Admin Add User'); ?></legend>
  <?php
    echo $this->Form->input('username');
    echo $this->Form->input('email');
    echo $this->Form->input('validated');
    echo $this->Form->input('validationCode');
    echo $this->Form->input('group');
    echo $this->Form->input('photo_count');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
  <h3><?php echo __('Actions'); ?></h3>
  <ul>

    <li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?></li>
    <li><?php echo $this->Html->link(__('List Photos'), array('controller' => 'photos', 'action' => 'index')); ?> </li>
    <li><?php echo $this->Html->link(__('New Photo'), array('controller' => 'photos', 'action' => 'add')); ?> </li>
  </ul>
</div>
