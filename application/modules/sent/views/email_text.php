You've got mail!

<?php foreach ($_POST as $key=>$value): ?>

	<?php if (substr($key, 0, 1) != "_" && $key != "ci_session" && $key != 'cc' && $key != 'bcc' && $key != 'hiddenInputSiteID'): ?>
		<?php echo $key?>: <?php echo $value; ?>

	<?php endif; ?>

<?php endforeach; ?>