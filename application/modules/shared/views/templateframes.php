<?php foreach ($pages as $page) : ?>

	<li class="templ" data-id="<?php echo $page['pages_id']?>">
		<?php if ( $page['pagethumb'] == '' ):?>
		<img src="<?php echo base_url('img/nothumb.png');?>">
		<?php else: ?>
		<img data-original-src="<?php echo base_url($page['pagethumb']);?>">
		<?php endif;?>
	</li>
<?php endforeach;?>