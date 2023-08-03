<div class="optionPane export">

	<h6>Site Assets</h6>

	<?
		$c = 1;
	?>

	<?php foreach ($data['assetFolders'] as $folder) : ?>
	<label class="checkbox" for="checkbox1">
		<input type="checkbox" value="<?php echo $folder; ?>" id="<?php echo $folder . $c; ?>" name="assetFolders[]" data-toggle="checkbox">
	  	<?php echo $folder; ?>
	</label>
	<?php $c++;?>
	<?php endforeach; ?>

</div><!-- /.optionPane -->

<div class="optionPane export">

	<h6>Site Pages</h6>

	<?php
		$c = 0;
	?>

	<?php foreach ($data['pages'] as $page) : ?>
	<label class="checkbox" for="checkbox1">
		<input type="checkbox" value="<?php echo $page->pages_name; ?>" id="<?php echo $page->pages_name . $c; ?>" name="pages" data-toggle="checkbox">
	  	<?php echo $page->pages_name; ?>
	</label>
	<?php $c++;?>
	<?php endforeach; ?>

</div><!-- /.optionPane -->