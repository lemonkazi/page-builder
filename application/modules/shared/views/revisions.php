<?php if ($revisions) : ?>
	<?php foreach ($revisions as $revision) : ?>
		<li>
			<span class="fui-arrow-right"></span>
			<?php echo date('Y-m-d H:i:s', $revision->frames_timestamp); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="sites/rpreview/<?php echo $siteID . "/" . $revision->frames_timestamp; ?>?p=<?php echo $page; ?>" target="_blank" title="<?php echo $this->lang->line('actionbuttons_versions_tooltip_preview'); ?>">
				<span class="fui-export"></span>
			</a>
			&nbsp;
			<a href="sites/deleterevision/<?php echo $siteID . "/" . $revision->frames_timestamp . "/" . $page;?>" title="<?php echo $this->lang->line('actionbuttons_versions_tooltip_delete'); ?>" class="link_deleteRevision">
				<span class="fui-trash text-danger"></span>
			</a>
			&nbsp;
			<a href="sites/restorerevision/<?php echo $siteID . "/" . $revision->frames_timestamp . "/" . $page;?>" title="<?php echo $this->lang->line('actionbuttons_versions_tooltip_restore'); ?>" class="link_restoreRevision">
				<span class="fui-power text-primary"></span>
			</a>
		</li>
	<?php endforeach; ?>
<?php endif; ?>
