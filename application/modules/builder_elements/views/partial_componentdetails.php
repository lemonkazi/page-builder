<div class="divBlockDetailsWrapper">

	<?php if (isset($forTemplate['info'])) { echo $forTemplate['info']; } ?>

	<input type="hidden" name="componentID" value="<?php echo $forTemplate['component']['components_id']; ?>">
	<div class="row">
		<div class="col-md-12 blockThumbnail">
			<img src="<?php echo $forTemplate['component']['components_thumb']; ?>">
		</div><!-- /.row -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12">
			<label><?php echo $this->lang->line('partial_componentdetails_label_thumbnail'); ?></label>
			<div class="form-group">
				<div class="fileinput fileinput-exists" data-provides="fileinput">
					<div class="input-group">
						<div class="form-control uneditable-input" data-trigger="fileinput">
							<span class="fui-clip fileinput-exists"></span>
							<span class="fileinput-filename"><?php echo basename($forTemplate['component']['components_thumb']); ?></span>
						</div>
						<span class="input-group-btn btn-file">
							<span class="btn btn-default fileinput-new" data-role="select-file"><?php echo $this->lang->line('partial_componentdetails_fileupload_select'); ?></span>
							<span class="btn btn-default fileinput-exists" data-role="change">
								<span class="fui-gear"></span>
								<?php echo $this->lang->line('partial_componentdetails_fileupload_change'); ?>
							</span>
							<input type="file" name="componentThumbnail">
							<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
								<span class="fui-trash"></span>
								<?php echo $this->lang->line('partial_componentdetails_fileupload_remove'); ?>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div><!-- /.col -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-6">
			<div class="form-group margin-bottom-0">
				<label for="exampleInputEmail1"><?php echo $this->lang->line('partial_componentdetails_label_category'); ?></label>
				<select class="form-control select select-default select-block select-sm mbl" name="componentCategory">
					<?php foreach ($forTemplate['componentCategories'] as $componentCategory) : ?>
					<option <?php if ($componentCategory['components_categories_id'] == $forTemplate['component']['components_category']) { echo "selected"; } ?> value="<?php echo $componentCategory['components_categories_id']; ?>"><?php echo $componentCategory['category_name']; ?></option>
					<?php endforeach; ?>
				</select>
			</div><!-- /.form-group -->
		</div><!-- /.col -->
	</div><!-- /.row -->
	<div class="row">
		<div class="col-md-12">
			<div class="form-group margin-bottom-0">
				<label for="exampleInputEmail1"><?php echo $this->lang->line('partial_componentdetails_label_html'); ?></label>
				<textarea name="componentMarkup" id="textareaComponentMarkup" style="display: none;"><?php echo $forTemplate['component']['components_markup']; ?></textarea>
				<div id="aceEditComponent"></div>
			</div>
		</div><!-- /.col -->
	</div><!-- /.row -->
</div>