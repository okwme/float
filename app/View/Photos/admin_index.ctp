<div class="photos index">
	<h2><?php echo __('Photos'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('amazonUrl'); ?></th>
			<th><?php echo $this->Paginator->sort('rating'); ?></th>
			<th><?php echo 'tags'; ?></th>
			<th><?php echo $this->Paginator->sort('size'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($photos as $photo): ?>
	<tr>
		<td><?php echo h($photo['Photo']['id']); ?>&nbsp;</td>
		<td><?php echo $this->Html->image($photo['Photo']['amazonUrl'], array("width"=>"300px")); ?>&nbsp;</td>
		<td><?php echo h($photo['Photo']['rating']); ?>&nbsp;</td>
		<td><?
		foreach($photo["Tag"] as $i=>$tag):
			echo $this->Html->link($tag["name"]."(".$tag["photo_count"].")", array("controller"=>"tags", "action"=>"view", $tag["id"], "admin"=>true));
		
			echo $i != count($photo["Tag"])  - 1 ? "," : "" ;
		endforeach;
		?>&nbsp;</td>
				<td><?php echo h($photo['Photo']['size']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($photo['User']['username'], array('controller' => 'users', 'action' => 'view', $photo['User']['id'])); ?>
		</td>
		<td><?php echo h($photo['Photo']['created']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('Rate'), array('action' => 'pollRating', $photo['Photo']['id'], "admin"=>false)); ?>
			<?php echo $this->Html->link(__('View'), array('controller'=>'users', 'action' => 'profile', $photo['Photo']['id'], "admin"=>false)); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $photo['Photo']['id'])); ?>
			<?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $photo['Photo']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Photo'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Tags'), array('controller' => 'tags', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Tag'), array('controller' => 'tags', 'action' => 'add')); ?> </li>
	</ul>
</div>
