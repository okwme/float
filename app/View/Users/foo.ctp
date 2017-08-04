
<?
// debug($this->data);
?><div class="users form">
<?php echo $this->Form->create('User'); ?>
  <fieldset>
    <legend><?php echo __('Validate'); ?></legend>
  <?php
    echo $this->Form->input('id');
    echo $this->Form->input('username');
    echo $this->Form->input('email');
    // echo $this->Form->input('validated');
    echo $this->Form->input('validationCode');
    // echo $this->Form->input('group');
    // echo $this->Form->input('photo_count');
  ?>
  </fieldset>
<?php echo $this->Form->end(__('Save')); ?>
</div>
