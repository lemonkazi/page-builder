<div class="divBlockDetailsWrapper">

	<?php if (isset($forTemplate['info'])) { echo $forTemplate['info']; } ?>

	<input type="hidden" name="blockID" value="<?php echo $forTemplate['block']['blocks_id']; ?>">
	<div class="row">
		<div class="col-md-12 blockThumbnail">
			<img src="<?php echo $forTemplate['block']['blocks_thumb']; ?>">
		</div><!-- /.row -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-6">
			<div class="form-group margin-bottom-0">
				<label for="exampleInputEmail1"><?php echo $this->lang->line('partial_blockdetails_label_category'); ?></label>
				<select class="form-control select select-default select-block select-sm mbl" name="blockCategory">
					<?php foreach ($forTemplate['blockCategories'] as $blockCategory ) : ?>
					<option <?php if ($blockCategory['blocks_categories_id'] == $forTemplate['block']['blocks_category']) { echo "selected"; } ?> value="<?php echo $blockCategory['blocks_categories_id']; ?>"><?php echo $blockCategory['category_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div><!-- /.form-group -->
		</div><!-- /.row -->
		<div class="col-md-6 blockTemplate">
			<div class="form-group margin-bottom-0">
				<label for="exampleInputEmail1"><?php echo $this->lang->line('partial_blockdetails_label_template'); ?></label>
				<select name="blockUrl" placeholder="<?php echo $this->lang->line('builder_elements_block_url'); ?>" class="form-control select select-default select-block mbl select-sm selectTemplateFile">
	                <?php foreach ($templates as $template) : ?>
	                <option value="<?php echo $template; ?>" <?php if ($template == $forTemplate['block']['blocks_url']) { echo "selected"; } ?>><?php echo $template; ?></option>
	                <?php endforeach; ?>
	            </select>
			</div><!-- /.form-group -->
			<a href="<?php echo site_url('builder_elements/editBlock/' . $forTemplate['block']['blocks_id']);?>" target="_blank" class="pull-right"><?php echo $this->lang->line('builder_elements_block_edit');?> <span class="fui-export"></span></a>
		</div><!-- /.row -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12">
			<label class="checkbox" for="blockFullHeight">
				<input type="checkbox" value="check" name="blockFullHeight" id="blockFullHeight" data-toggle="checkbox" <?php if ($forTemplate['block']['blocks_height'] == '90vh' ) { echo "checked"; } ?> >
				<?php echo $this->lang->line('partial_blockdetails_label_fullheight'); ?> <span class="label label-default heightHelp" data-toggle="tooltip" title="<?php echo $this->lang->line('partial_blockdetails_help_fullheight'); ?>">?</span>
			</label>
			<label class="checkbox" for="remakeThumb">
				<input type="checkbox" value="check" name="remakeThumb" id="remakeThumb" data-toggle="checkbox">
				<?php echo $this->lang->line('partial_blockdetails_label_remakethumb'); ?> <span class="label label-default heightHelp" data-toggle="tooltip" title="<?php echo $this->lang->line('partial_blockdetails_help_remakethumb'); ?>">?</span>
			</label>
		</div><!-- /.col -->
	</div><!-- /.row -->
</div>