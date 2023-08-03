<div class="imageLibraryWrapper">
	<div class="images" id="myImages">

		<div class="slimWrapper">
			<div class="slim" id="slimWithSettings"
				data-meta-fresh="1"
		    	data-ratio="free"
		     	data-service="<?php echo site_url('asset/imageUploadAjax/');?>"
		     	data-fetcher="fetch.php"
		     	data-max-file-size="<?php echo $this->config->item('upload_max_size')/1000;?>"
		     	data-status-file-size="<?php echo $this->lang->line('asset_slim_filesize_error');?>"
		     	data-status-file-type="<?php echo $this->lang->line('asset_slim_filetype_error');?>"
		     	data-status-image-too-small="<?php echo $this->lang->line('asset_slim_toosmall_error');?>"
		     	data-status-unknown-response="<?php echo $this->lang->line('asset_slim_unkown_error');?>"
		     	data-status-upload-success="<?php echo $this->lang->line('asset_slim_upload_success');?>"
		     	data-did-upload="slimImageUpload"
		     	data-button-edit-title="<?php echo $this->lang->line('asset_slim_button_edit_title');?>"
		     	data-button-remove-title="<?php echo $this->lang->line('asset_slim_button_remove_title');?>"
		     	data-button-upload-title="<?php echo $this->lang->line('asset_slim_button_upload_title');?>"
		     	data-did-receive-server-error="slimHandleServerError"
		     	data-button-cancel-label="<?php echo $this->lang->line('asset_slim_button_cancel');?>"
		     	data-button-confirm-label="<?php echo $this->lang->line('asset_slim_button_confirm');?>"
		     	data-label="<?php printf( $this->lang->line('myimages_newimage_label'), $this->config->item('upload_max_size')/1000);?>">
		    	<input type="file" name="slim[]" id="slimUpload">
			</div>
		</div>

		<?php foreach ($userImages as $img) : ?>
			<?php
	     		$imageUrl = "./" . $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $img . '?' . str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);
	     	?>
			<div class="image">
				<div class="imageWrap" style="background-image: url('<?php echo thumb($imageUrl, 250, 140);?>')"
					data-ratio="free"
		     		data-service="<?php echo site_url('asset/imageUploadAjax/');?>"
		     		data-fetcher="fetch.php"
		     		data-max-file-size="<?php echo $this->config->item('upload_max_size')/1000;?>"
		     		data-status-file-type="<?php echo $this->lang->line('asset_slim_filetype_error');?>"
		     		data-status-image-too-small="<?php echo $this->lang->line('asset_slim_toosmall_error');?>"
		     		data-status-unknown-response="<?php echo $this->lang->line('asset_slim_unkown_error');?>"
		     		data-status-upload-success="<?php echo $this->lang->line('asset_slim_upload_success');?>"
		     		data-button-cancel-label="<?php echo $this->lang->line('asset_slim_button_cancel');?>"
		     		data-button-confirm-label="<?php echo $this->lang->line('asset_slim_button_confirm');?>"
		     		data-label="<?php echo $this->lang->line('myimages_newimage_label');?>"
		     		data-org-src="<?php echo $this->config->item('images_uploadDir') . "/" . $this->session->userdata('user_id') . "/" . $img . '?' . str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT);?>"
		     		data-thumb="<?php echo thumb($imageUrl, 250, 140);?>"
				>
				</div>
			</div>
		<?php endforeach; ?>

	</div><!-- /.images -->
	<div class="imageDetailPanel" id="imageDetailPanel">

		<a href="" id="linkFullImage" class="linkFullImage" target="_blank" data-toggle="tooltip" title="<?php echo $this->lang->line('asset_open_image');?>"></a>

		<div class="slimEditImageWrapper">
    		<img src="" alt="" id="slimEditImage">
    	</div><!-- /.slimEditImage -->

    	<div class="imageDimensionsWrapper">
    		<p><?php echo $this->lang->line('asset_dimensions_label');?></p>
			<div class="fileDimensions clearfix">
				<div class="first">
					<div class="form-group has-feedback">
  						<input type="number" class="form-control input-sm" id="inputImageWidth" name="inputImageWidth" placeholder="" value="">
  						<span class="form-control-feedback">px</span>
					</div>
				</div>
				<div class="second">
					<?php echo $this->lang->line('asset_dimensions_by');?>
				</div>
				<div class="third">
					<div class="form-group has-feedback">
  						<input type="number" class="form-control input-sm" id="inputImageHeight" name="inputImageHeight" placeholder="" value="">
  						<span class="form-control-feedback">px</span>
					</div>
				</div>
			</div>
			<label class="checkbox" for="checkFixedRation">
				<input type="checkbox" value="" checked id="checkFixedRation" data-toggle="checkbox">
				<?php echo $this->lang->line('asset_dimensions_aspect_ratio');?>
  			</label>
			<button class="btn btn-info btn-sm btn-block btn-embossed" id="buttonUpdateImageDimensions" data-loading="<?php echo $this->lang->line('asset_imagedelete_button_loading');?>" data-confirm="<?php echo $this->lang->line('asset_dimensions_button_confirm');?>"><?php echo $this->lang->line('asset_dimensions_button');?></button>
    	</div>

    	<div class="imageMoreActions">
    		<div class="deleteImage">
    			<a href="#" class="deleteImage" id="buttonDeleteImage"><?php echo $this->lang->line('asset_imagedelete_button');?></a>
    			<div class="confirm" id="confirmDeleteImage"><b><?php echo $this->lang->line('asset_imagedelete_areyousure');?></b> <a href="" class="confirmYes" id="imageDeleteYes"><?php echo $this->lang->line('asset_imagedelete_areyousure_yes');?></a> / <a href="" class="confirmNo" id="imageDeleteNo"><?php echo $this->lang->line('asset_imagedelete_areyousure_no');?></a></div>
    		</div>
    	</div>

	</div>
</div><!-- /.imageLibraryWrapper -->