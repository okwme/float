<div class="row">
  <div class="columns small-12 medium-8 medium-offset-2 large-6 large-offset-3">
		<div id="validate-form" class="users form">
		<?php echo $this->Form->create('User'); ?>
		  <fieldset>
		    <legend><?php echo __('Validate with your email'); ?></legend>
		    <p>We'll send you a one-time token to access you account</p>
		  <?php
		    echo $this->Form->input('email');
		  ?>
		  </fieldset>
		<?php echo $this->Form->end(__('Send login link')); ?>
		</div>
	</div>
</div>