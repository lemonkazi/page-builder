<?php $this->load->view("shared/header.php"); ?>

<body>

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-md-9 col-sm-8">
				<h1><span class="fui-list-bulleted"></span> <?php echo $this->lang->line('package_heading'); ?></h1>
			</div><!-- /.col -->

			<div class="col-md-3 col-sm-4 text-right">
				<a href="#newPackageModal" data-toggle="modal" class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-30 add"><span class="fui-plus"></span> <?php echo $this->lang->line('package_button_addnew'); ?></a>
			</div><!-- /.col -->

		</div><!-- /.row -->

		<hr class="dashed margin-bottom-30">

		<div class="row">

			<div class="col-md-12">

				<?php if ($this->session->flashdata('success') != '') : ?>
					<div class="alert alert-success">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<?php echo $this->session->flashdata('success'); ?>
					</div>
				<?php endif; ?>

				<?php if ($this->session->flashdata('error') != '') : ?>
					<div class="alert alert-error">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<?php echo $this->session->flashdata('error'); ?>
					</div>
				<?php endif; ?>

				<?php if ($this->session->flashdata('stripe_error') != '') : ?>
					<div class="alert alert-error">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<?php echo $this->session->flashdata('stripe_error'); ?>
					</div>
				<?php endif; ?>

				<?php if (isset($paypal_api) && $paypal_api == 'no') : ?>
					<div class="alert alert-info" style="margin-top: 30px">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<h4><?php echo $this->lang->line('package_empty_paypal_api_heading'); ?></h4>
						<p><?php echo $this->lang->line('package_empty_paypal_api_message'); ?></p>
					</div>
				<?php endif; ?>
				<?php if (isset($stripe_key) && $stripe_key == 'no') : ?>
					<div class="alert alert-info" style="margin-top: 30px">
						<button type="button" class="close fui-cross" data-dismiss="alert"></button>
						<h4><?php echo $this->lang->line('package_empty_stripe_key_heading'); ?></h4>
						<p><?php echo $this->lang->line('package_empty_stripe_key_message'); ?></p>
					</div>
				<?php endif; ?>
				<?php if (count($packages) <= 0) : ?>
					<div id="empty_package">
						<div class="alert alert-info" style="margin-top: 30px">
							<button type="button" class="close fui-cross" data-dismiss="alert"></button>
							<h4><?php echo $this->lang->line('package_empty_package_heading'); ?></h4>
							<p><?php echo $this->lang->line('package_empty_package_message'); ?></p>
						</div>
					</div><!-- ./col -->
				<?php endif; ?>

				<div id="packages">
					<?php $this->load->view('package/package_table', array('packages' => $packages)); ?>
				</div>

			</div><!-- /.col -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<!-- Modals -->

	<div class="modal fade deletePackageModal" id="deletePackageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-dialog">

			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
					<h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('modal_areyousure'); ?></h4>
				</div>

				<div class="modal-body">
					<p><?php echo $this->lang->line('package_modal_delete_package_message'); ?></p>
					<?php if (isset($stripe_key) && $stripe_key == "yes") { ?>
					<p>
						<label class="checkbox" for="checkbox1">
							<?php echo $this->lang->line('package_modal_delete_package_delete_in_stripe')?>
						</label>
					</p>
					<?php } ?>
				</div><!-- /.modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('cancel'); ?></button>
					<a href="" type="button" class="btn btn-primary" id="deletePackageButton"><span class="fui-check"></span> <?php echo $this->lang->line('imsure'); ?></a>
				</div>

			</div><!-- /.modal-content -->

		</div><!-- /.modal-dialog -->

	</div><!-- /.modal -->


	<div class="modal fade newPackageModal" id="newPackageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<form class="form-horizontal" role="form" action="packages/create">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
						<h4 class="modal-title" id="myModalLabel"><span class="fui-list-bulleted"></span> <?php echo $this->lang->line('package_modal_newpackage_heading'); ?></h4>
					</div>

					<div class="modal-body padding-top-40">
						<div class="loader" style="display: none;">
							<img src="<?php echo base_url(); ?>img/91.gif" alt="<?php echo $this->lang->line('loading'); ?>">
							<?php echo $this->lang->line('package_modal_newpackage_loadertext'); ?>
						</div>
						<div class="modal-alerts"></div>
						<div class="form-group">
							<label for="name" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_name'); ?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $this->lang->line('package_modal_newpackage_name'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="sites_number" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_number_of_sites')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="sites_number" name="sites_number" placeholder="<?php echo $this->lang->line('package_modal_newpackage_number_of_sites'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="pages_number" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_pages_number')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="pages_number" name="pages_number" placeholder="<?php echo $this->lang->line('package_modal_newpackage_pages_number_option'); ?>" value="">
							</div>
						</div>						
						<div class="form-group margin-bottom-0">
							<label for="hosting_option" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_hosting_option'); ?>:</label>
							<div class="col-md-9">
								<select multiple="multiple" name="hosting_option[]" id="hosting_option" class="form-control select multiselect multiselect-default btn-block mbl" placeholder="<?php echo $this->lang->line('package_modal_newpackage_placeholder_hosting_option');?>">
									<option value="Sub-Folder"><?php echo $this->lang->line('package_modal_newpackage_hosting_subfolder')?></option>
									<option value="Sub-Domain"><?php echo $this->lang->line('package_modal_newpackage_hosting_subdomain')?></option>
									<option value="Custom Domain"><?php echo $this->lang->line('package_modal_newpackage_hosting_custom_domain')?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="export_site" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_export_site')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="export_site" >
								<input type="checkbox" value="yes" name="export_site" id="export_site" data-toggle="switch">
							</div>
						</div>
						<div class="form-group">
							<label for="ftp_publish" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_ftp_publish')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="ftp_publish" >
								<input type="checkbox" value="yes" name="ftp_publish" id="ftp_publish" data-toggle="switch">
							</div>
						</div>
						<div class="form-group">
							<label for="edit_sourcecode" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_edit_sourcecode')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="edit_sourcecode" >
								<input type="checkbox" value="yes" name="edit_sourcecode" id="edit_sourcecode" data-toggle="switch">
							</div>
						</div>
						<div class="form-group">
							<label for="disk_space" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_disk_space')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="disk_space" name="disk_space" placeholder="<?php echo $this->lang->line('package_modal_newpackage_placeholder_disk_space');?>" value="">
							</div>
						</div>
						<?php if ($templates) : ?>
							<div class="form-group">
								<label for="" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_templates')?>:</label>
								<div class="col-md-9">
									<div class="packageTemplateSelection">
										<ul>
											<?php foreach ($templates as $key => $value) : ?>
												<li>
													<label class="checkbox" for="templates_<?php echo $value->pages_id; ?>">
														<?php echo ($value->category_name)? $value->category_name : $this->lang->line('package_modal_newpackage_nocategory') . " > " . $value->pages_name; ?> : <a href="<?php echo base_url('temple/' . $value->pages_id); ?>" target="_blank"><?php echo date('l, Ymd', $value->pages_timestamp); ?> <span class="fui-export"></span></a>
														<input type="checkbox" value="<?php echo $value->pages_id; ?>" id="templates_<?php echo $value->pages_id; ?>" data-toggle="checkbox" name="templates[]">
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div><!-- /.packageTemplateSelection -->
								</div>
							</div>
						<?php endif; ?>
						<?php if ( $blockCategories ) :?>
							<div class="form-group">
								<label for="" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_blocks')?>:</label>
								<div class="col-md-9">
									<div class="margin-bottom-10" style="margin-top: 7px;">
										<?php echo $this->lang->line('package_modal_newpackage_blocksenable');?>: <input type="checkbox" value="yes" name="limit_blocks" id="limit_blocks" data-toggle="switch">
									</div>
									<div class="blockCatSelection">
										<ul>
											<?php foreach ($blockCategories as $cat) : ?>
												<li>
													<label class="checkbox" for="blockcat_<?php echo $cat['blocks_categories_id']; ?>">
														<?php echo $cat['category_name']; ?>
														<input type="checkbox" disabled value="<?php echo $cat['blocks_categories_id']; ?>" id="blockcat_<?php echo $cat['blocks_categories_id']; ?>" data-toggle="checkbox" name="blockcats[]">
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div><!-- /.blockCatSelection -->
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label for="price" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_price')?>:</label>
							<div class="col-md-9">
								<input <?php if ((isset($stripe_key) && $stripe_key == "no") || ((isset($paypal_api) && $paypal_api == "no"))) { ?> readonly="true"<?php } ?> type="text" class="form-control" id="price" name="price" placeholder="<?php echo $this->lang->line('package_modal_newpackage_price'); ?>" value="<?php if ((isset($stripe_key) && $stripe_key == "no") || ((isset($paypal_api) && $paypal_api == "no"))) { ?>0<?php } ?>">
							</div>
						</div>
						<?php
						$currencies = (isset($stripe_key)) ? stripe_currency_array() : paypal_currency_array();
						asort($currencies);
						?>
						<div class="form-group margin-bottom-0">
							<label for="currency" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_currency'); ?>:</label>
							<div class="col-md-9">
								<select name="currency" id="currency" class="form-control select select-inverse btn-block mbl show-select-search">
									<?php foreach ($currencies as $key => $value) : ?>
										<option value="<?php echo $key; ?>"><?php echo $value . ' (' . $key . ')'; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group margin-bottom-0">
							<label for="subscription" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_subscription'); ?>:</label>
							<div class="col-md-9">
								<select name="subscription" id="subscription" class="form-control select select-inverse btn-block mbl">
									<option value="Weekly"><?php echo $this->lang->line('package_modal_newpackage_subscription_weekly');?></option>
									<option value="Monthly"><?php echo $this->lang->line('package_modal_newpackage_subscription_monthly');?></option>
									<option value="Yearly"><?php echo $this->lang->line('package_modal_newpackage_subscription_yearly');?></option>
									<option value="Every 3 months"><?php echo $this->lang->line('package_modal_newpackage_subscription_threemonths');?></option>
									<option value="Every 6 months"><?php echo $this->lang->line('package_modal_newpackage_subscription_sixmonths');?></option>
								</select>
							</div>
						</div>
						<div class="form-group margin-bottom-0">
							<label for="status" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_status'); ?>:</label>
							<div class="col-md-9">
								<select name="status" id="status" class="form-control select select-inverse btn-block mbl">
									<option value="Active"><?php echo $this->lang->line('package_modal_newpackage_status_active')?></option>
									<option value="Inactive"><?php echo $this->lang->line('package_modal_newpackage_status_inactive')?></option>
								</select>
							</div>
						</div>
					</div><!-- /.modal-body -->

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('cancel'); ?></button>
						<button type="button" class="btn btn-primary" id="buttonCreatePackage"><span class="fui-check"></span> <?php echo $this->lang->line('package_modal_newpackage_button_create'); ?></button>
					</div>
				</div><!-- /.modal-content -->

			</div><!-- /.modal-dialog -->

		</form>

	</div><!-- /.modal -->

	<div class="modal fade newPackageModal" id="editPackageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<form class="form-horizontal" role="form" action="packages/update" id="updatePackageForm">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
						<h4 class="modal-title" id="myModalLabel"><span class="fui-list-bulleted"></span> <?php echo $this->lang->line('package_modal_edit_heading'); ?></h4>
					</div>

					<div class="modal-body padding-top-40">
						<div class="loader" style="display: none;">
							<img src="<?php echo base_url(); ?>img/91.gif" alt="Loading...">
							<?php echo $this->lang->line('package_modal_newpackage_loadertext'); ?>
						</div>
						<div class="modal-alerts"></div>
						<input type="hidden" id="e_id" name="id" value="">
						<div class="form-group">
							<label for="name" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_name'); ?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="e_name" name="name" placeholder="<?php echo $this->lang->line('package_modal_newpackage_name'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="sites_number" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_number_of_sites')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="e_sites_number" name="sites_number" placeholder="<?php echo $this->lang->line('package_modal_newpackage_number_of_sites'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="pages_number" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_pages_number')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="e_pages_number" name="pages_number" placeholder="<?php echo $this->lang->line('package_modal_newpackage_pages_number_option'); ?>" value="">
							</div>
						</div>	
						<div class="form-group">
							<label for="hosting_option" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_hosting_option'); ?>:</label>
							<div class="col-md-9">
								<select multiple="multiple" name="hosting_option[]" id="e_hosting_option" class="form-control select multiselect multiselect-default btn-block mbl">
									<option value="Sub-Folder"><?php echo $this->lang->line('package_modal_newpackage_hosting_subfolder')?></option>
									<option value="Sub-Domain"><?php echo $this->lang->line('package_modal_newpackage_hosting_subdomain')?></option>
									<option value="Custom Domain"><?php echo $this->lang->line('package_modal_newpackage_hosting_custom_domain')?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="export_site" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_export_site')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="export_site" >
								<input type="checkbox" value="yes" name="export_site" id="e_export_site" data-toggle="switch">
							</div>
						</div>
						<div class="form-group">
							<label for="ftp_publish" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_ftp_publish')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="ftp_publish" >
								<input type="checkbox" value="yes" name="ftp_publish" id="e_ftp_publish" data-toggle="switch">
							</div>
						</div>
						<div class="form-group">
							<label for="edit_sourcecode" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_edit_sourcecode')?>:</label>
							<div class="col-md-9">
								<input type="hidden" value="no" name="edit_sourcecode" >
								<input type="checkbox" value="yes" name="edit_sourcecode" id="e_edit_sourcecode" data-toggle="switch">
							</div>
						</div>						
						<div class="form-group">
							<label for="disk_space" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_disk_space')?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" id="e_disk_space" name="disk_space" placeholder="<?php echo $this->lang->line('package_modal_newpackage_placeholder_disk_space');?>" value="">
							</div>
						</div>
						<?php if ($templates) : ?>
							<div class="form-group">
								<label for="" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_templates')?>:</label>
								<div class="col-md-9">
									<div class="packageTemplateSelection">
										<ul>
											<?php foreach ($templates as $key => $value) : ?>
												<li>
													<label class="checkbox" for="e_templates_<?php echo $value->pages_id; ?>">
														<?php echo ($value->category_name)? $value->category_name : $this->lang->line('package_modal_newpackage_nocategory') . " > " . $value->pages_name; ?> : <a href="<?php echo base_url('temple/' . $value->pages_id); ?>" target="_blank"><?php echo date('l, Ymd', $value->pages_timestamp); ?> <span class="fui-export"></span></a>
														<input type="checkbox" value="<?php echo $value->pages_id; ?>" id="e_templates_<?php echo $value->pages_id; ?>" data-toggle="checkbox" name="templates[]">
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div><!-- /.packageTemplateSelection -->
								</div>
							</div>
						<?php endif; ?>
						<?php if ( $blockCategories ) :?>
							<div class="form-group">
								<label for="" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_blocks')?>:</label>
								<div class="col-md-9">
									<div class="margin-bottom-10" style="margin-top: 7px;">
										<?php echo $this->lang->line('package_modal_newpackage_blocksenable');?>: <input type="checkbox" value="yes" name="limit_blocks" id="e_limit_blocks" data-toggle="switch">
									</div>
									<div class="blockCatSelection">
										<ul>
											<?php foreach ($blockCategories as $cat) : ?>
												<li>
													<label class="checkbox" for="e_blockcat_<?php echo $cat['blocks_categories_id']; ?>">
														<?php echo $cat['category_name']; ?>
														<input type="checkbox" value="<?php echo $cat['blocks_categories_id']; ?>" disabled id="e_blockcat_<?php echo $cat['blocks_categories_id']; ?>" data-toggle="checkbox" name="blockcats[]" class="blockcats">
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									</div><!-- /.blockCatSelection -->
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label for="price" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_price')?>:</label>
							<div class="col-md-9">
								<input disabled="true" type="text" class="form-control" id="e_price" name="price" placeholder="<?php echo $this->lang->line('package_modal_newpackage_price'); ?>" value="">
							</div>
						</div>
						<?php
						$currencies = (isset($stripe_key)) ? stripe_currency_array() : paypal_currency_array();
						asort($currencies);
						?>
						<div class="form-group">
							<label for="currency" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_currency'); ?>:</label>
							<div class="col-md-9">
								<select disabled="true" name="currency" id="e_currency" class="form-control select select-inverse btn-block mbl">
									<?php foreach ($currencies as $key => $value) : ?>
										<option value="<?php echo $key; ?>"><?php echo $value . ' (' . $key . ')'; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="subscription" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_subscription'); ?>:</label>
							<div class="col-md-9">
								<select disabled="true" name="subscription" id="e_subscription" class="form-control select select-inverse btn-block mbl">
									<option value="Weekly"><?php echo $this->lang->line('package_modal_newpackage_subscription_weekly');?></option>
									<option value="Monthly"><?php echo $this->lang->line('package_modal_newpackage_subscription_monthly');?></option>
									<option value="Yearly"><?php echo $this->lang->line('package_modal_newpackage_subscription_yearly');?></option>
									<option value="Every 3 months"><?php echo $this->lang->line('package_modal_newpackage_subscription_threemonths');?></option>
									<option value="Every 6 months"><?php echo $this->lang->line('package_modal_newpackage_subscription_sixmonths');?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="status" class="col-md-3 control-label"><?php echo $this->lang->line('package_modal_newpackage_status'); ?>:</label>
							<div class="col-md-9">
								<select name="status" id="e_status" class="form-control select select-inverse btn-block mbl">
									<option value="Active"><?php echo $this->lang->line('package_modal_newpackage_status_active')?></option>
									<option value="Inactive"><?php echo $this->lang->line('package_modal_newpackage_status_inactive')?></option>
								</select>
							</div>
						</div>
					</div><!-- /.modal-body -->

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('cancel'); ?></button>
						<button type="button" class="btn btn-primary" id="buttonUpdatePackage"><span class="fui-check"></span> <?php echo $this->lang->line('package_modal_edit_package_button_update'); ?></button>
					</div>
				</div><!-- /.modal-content -->

			</div><!-- /.modal-dialog -->

		</form>

	</div><!-- /.modal -->

	<?php $this->load->view("shared/modal_account.php"); ?>

	<!-- /modals -->

	<!-- Load JS here for greater good =============================-->
	<?php if (ENVIRONMENT == 'production') : ?>
		<script src="<?php echo base_url('build/packages.bundle.js') . '?' . filemtime( FCPATH . 'build/packages.bundle.js'); ?>"></script>
	<?php elseif (ENVIRONMENT == 'development') : ?>
		<script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/packages.bundle.js"></script>
	<?php endif; ?>
</body>
</html>
