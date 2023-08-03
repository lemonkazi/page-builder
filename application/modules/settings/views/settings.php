<?php $this->load->view("shared/header.php"); ?>

<body>

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-md-9 col-sm-8">
				<h1><span class="fui-gear"></span> <?php echo $this->lang->line('settings_heading'); ?></h1>
			</div><!-- /.col -->

			<div class="col-md-3 col-sm-4 text-right">

			</div><!-- /.col -->

		</div><!-- /.row -->

		<hr class="dashed margin-bottom-30">

		<div class="row">

			<div class="col-md-12">

				<ul class="nav nav-tabs nav-append-content" id="settingsTabs">
					<li class="active">
						<a href="settings#appSettings">
							<span class="fui-gear"></span> 
							<?php echo $this->lang->line('settings_tab_application_settings'); ?>
						</a>
					</li>
					<li>
						<a href="settings#paymentSettings">
							<span class="fui-credit-card"></span> 
							<?php echo $this->lang->line('settings_tab_payment_settings'); ?>
						</a>
					</li>
					<li>
						<a href="settings#updateSettings">
							<span class="fui-windows"></span> 
							<?php echo $this->lang->line('settings_tab_update_settings'); ?>
						</a>
					</li>
					<li>
						<a href="settings#whiteLabel">
							<span class="fui-eye"></span> 
							<?php echo $this->lang->line('settings_tab_white_label'); ?>
						</a>
					</li>
					<li>
						<a href="settings#integration">
							<span class="fui-upload"></span> 
							<?php echo $this->lang->line('settings_tab_integration'); ?>
						</a>
					</li>
				</ul> <!-- /tabs -->

				<div class="tab-content">

					<div class="tab-pane active" id="appSettings">

						<div class="row">

							<div class="col-md-8">

								<?php if ($this->session->flashdata('error') == '' && $this->session->flashdata('success') == '') : ?>

									<div class="alert alert-warning">
										<button type="button" class="close fui-cross" data-dismiss="alert"></button>
										<h4><?php echo $this->lang->line('settings_warning_heading'); ?></h4>
										<p>
											<?php echo $this->lang->line('settings_warning_message'); ?>
										</p>
									</div>

								<?php else : ?>

									<?php if ($this->session->flashdata('error') != '') : ?>
										<div class="alert alert-warning">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_error'); ?></h4>
											<?php echo $this->session->flashdata('error'); ?>
										</div>
									<?php endif; ?>

									<?php if ($this->session->flashdata('success') != '') : ?>
										<div class="alert alert-success">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_success'); ?></h4>
											<?php echo $this->session->flashdata('success'); ?>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<form class="form-horizontal settingsForm" role="form" method="post" action="<?php echo site_url() . 'settings/update';?>">
									<?php foreach ($apps as $app) : ?>
										<?php if($app->id == 4 || $app->id == 8) continue;?>
										<?php if ($app->id == '14') : ?>
											<div class="form-group">
												<label for="<?php echo $app->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($app->name); ?> <?php if ($app->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
													<input type="hidden" value="no" name="<?php echo $app->name; ?>" ><div class="margin-top-5"></div>													
													<input type="checkbox" value="yes" <?php if ($app->value == 'yes') : ?>checked<?php endif; ?> name="<?php echo $app->name; ?>" data-toggle="switch" id="<?php echo $app->name; ?>">
													<div class="settingDescription">
													<?php echo $app->description; ?>
													</div>
												</div>

											</div>
										<?php continue; endif; ?>
										<div class="form-group">
											<label for="<?php echo $app->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($app->name); ?> <?php if ($app->required == 1) : ?>*<?php endif; ?></label>
											<div class="col-sm-9">
												<textarea class="form-control" name="<?php echo $app->name; ?>" id="<?php echo $app->name; ?>"><?php echo $app->value; ?></textarea>
												<div class="settingDescription">
													<?php echo $app->description; ?>
												</div>
											</div>
										</div>
									<?php endforeach; ?>

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<p class="text-danger">
												<?php echo $this->lang->line('settings_requiredfields'); ?>
											</p>
											<button type="submit" class="btn btn-primary btn-wide"><span class="fui-check"></span> <?php echo $this->lang->line('settings_button_update'); ?></button>
										</div>
									</div>
								</form>

							</div><!-- /.col -->

							<div class="col-md-4">

								<div class="alert alert-info configHelp" id="configHelp">
									<button type="button" class="close fui-cross" data-dismiss="alert"></button>
									<div>
										<h4>
											<?php echo $this->lang->line('settings_confighelp_heading'); ?>
										</h4>
										<p>
											<?php echo $this->lang->line('settings_confighelp_message'); ?>
										</p>
									</div>
								</div>

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div>

					<div class="tab-pane" id="paymentSettings">

						<div class="row">

							<div class="col-md-8">

								<?php if ($this->session->flashdata('error') == '' && $this->session->flashdata('success') == '') : ?>

									<div class="alert alert-warning">
										<button type="button" class="close fui-cross" data-dismiss="alert"></button>
										<h4><?php echo $this->lang->line('settings_warning_heading'); ?></h4>
										<p>
											<?php echo $this->lang->line('settings_warning_message'); ?>
										</p>
									</div>

								<?php else : ?>

									<?php if ($this->session->flashdata('error') != '') : ?>
										<div class="alert alert-warning">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_error'); ?></h4>
											<?php echo $this->session->flashdata('error'); ?>
										</div>
									<?php endif; ?>

									<?php if ($this->session->flashdata('success') != '') : ?>
										<div class="alert alert-success">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_success'); ?></h4>
											<?php echo $this->session->flashdata('success'); ?>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<form class="form-horizontal settingsForm" role="form" method="post" action="<?php echo site_url() . 'settings/update_payment#paymentSettings';?>">
									<?php foreach ($payments as $payment) : ?>
										<?php if ($payment->name == 'stripe_test_mode') : ?>
											<div class="form-group">
												<label for="<?php echo $payment->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($payment->name); ?> <?php if ($payment->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
													<input type="hidden" value="off" name="<?php echo $payment->name; ?>" ><div class="margin-top-5"></div>	
													<input type="checkbox" value="test" <?php if ($payment->value == 'test') : ?>checked<?php endif; ?> name="<?php echo $payment->name; ?>" data-toggle="switch" id="<?php echo $payment->name; ?>">
												</div>
											</div>
										<?php elseif($payment->name == 'paypal_test_mode') : ?>

											<div class="form-group">
												<label for="<?php echo $payment->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($payment->name); ?> <?php if ($payment->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
													<input type="hidden" value="off" name="<?php echo $payment->name; ?>" ><div class="margin-top-5"></div>	
													<input type="checkbox" value="test" <?php if ($payment->value == 'test') : ?>checked<?php endif; ?> name="<?php echo $payment->name; ?>" data-toggle="switch" id="<?php echo $payment->name; ?>">
												</div>
											</div>
										<?php else : ?>

											<div class="form-group">
												<label for="<?php echo $payment->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($payment->name); ?> <?php if ($payment->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
												<?php if($payment->id == 8) :?>
												<select name="<?php echo $payment->name; ?>" id="<?php echo $payment->name; ?>" class="form-control select select-primary select-block mbl">
													<option value="stripe" <?php if($payment->value == "stripe"){ echo 'selected="selected"'; } ?>>Stripe</option>
													<option value="paypal" <?php if($payment->value == "paypal"){ echo 'selected="selected"'; } ?>>Paypal</option>
												</select>
												<?php else :?>
													<textarea class="form-control" style="height: 64px" name="<?php echo $payment->name; ?>" id="<?php echo $payment->name; ?>"><?php echo $payment->value; ?></textarea>
													<div class="settingDescription">
														<?php echo $payment->description; ?>
													</div>
												<?php endif; ?>
												</div>

											</div>
										<?php endif; ?>
									<?php endforeach;?>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<p class="text-danger">
												<?php echo $this->lang->line('settings_requiredfields'); ?>
											</p>
											<button type="submit" class="btn btn-primary btn-wide"><span class="fui-check"></span> <?php echo $this->lang->line('settings_button_update'); ?></button>
										</div>
									</div>
								</form>

							</div><!-- /.col -->

							<div class="col-md-4">

								<div class="alert alert-info configHelp" id="configHelp">
									<button type="button" class="close fui-cross" data-dismiss="alert"></button>
									<div>
										<h4>
											<?php echo $this->lang->line('settings_confighelp_heading'); ?>
										</h4>
										<p>
											<?php echo $this->lang->line('settings_confighelp_message'); ?>
										</p>
									</div>
								</div>

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div>

					<div class="tab-pane" id="updateSettings">

						<div class="row">

							<div class="col-md-8">

								<?php if ($this->session->flashdata('error') == '' && $this->session->flashdata('success') == '') : ?>

									<div class="alert alert-warning">
										<button type="button" class="close fui-cross" data-dismiss="alert"></button>
										<h4><?php echo $this->lang->line('settings_warning_heading'); ?></h4>
										<p>
											<?php echo $this->lang->line('settings_warning_message'); ?>
										</p>
									</div>

								<?php else : ?>

									<?php if ($this->session->flashdata('error') != '') : ?>
										<div class="alert alert-warning">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_error'); ?></h4>
											<?php echo $this->session->flashdata('error'); ?>
										</div>
									<?php endif; ?>

									<?php if ($this->session->flashdata('success') != '') : ?>
										<div class="alert alert-success">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_success'); ?></h4>
											<?php echo $this->session->flashdata('success'); ?>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<form class="form-horizontal settingsForm" role="form" method="post" action="<?php echo site_url() . 'settings/update_core#updateSettings';?>">
									<?php foreach ($cores as $core) : ?>
										<?php if ($core->name == 'auto_update' || $core->name == 'overwrite_blocks') : ?>
											<div class="form-group">
												<label for="<?php echo $core->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($core->name); ?> <?php if ($core->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
													<input type="hidden" value="no" name="<?php echo $core->name; ?>" ><div class="margin-top-5"></div>	
													<input type="checkbox" value="yes" <?php if ($core->value == 'yes') : ?>checked<?php endif; ?> name="<?php echo $core->name; ?>" data-toggle="switch" id="<?php echo $core->name; ?>">
												</div>
											</div>
										<?php else : ?>
											<div class="form-group">
												<label for="<?php echo $core->name; ?>" class="col-sm-3 control-label"><?php echo $this->lang->line($core->name); ?> <?php if ($core->required == 1) : ?>*<?php endif; ?></label>
												<div class="col-sm-9">
													<textarea class="form-control" style="height: 64px" name="<?php echo $core->name; ?>" id="<?php echo $core->name; ?>"><?php echo $core->value; ?></textarea>
													<div class="settingDescription">
														<?php echo $core->description; ?>
													</div>
												</div>
											</div>
										<?php endif; ?>
									<?php endforeach; ?>
									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<p class="text-danger">
												<?php echo $this->lang->line('settings_requiredfields'); ?>
											</p>
											<button type="submit" class="btn btn-primary btn-wide"><span class="fui-check"></span> <?php echo $this->lang->line('settings_button_update'); ?></button>
										</div>
									</div>
								</form>

							</div><!-- /.col -->

							<div class="col-md-4">

								<div class="alert alert-info configHelp" id="configHelp">
									<button type="button" class="close fui-cross" data-dismiss="alert"></button>
									<div>
										<h4>
											<?php echo $this->lang->line('settings_confighelp_heading'); ?>
										</h4>
										<p>
											<?php echo $this->lang->line('settings_confighelp_message'); ?>
										</p>
									</div>
								</div>

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div>

					<div class="tab-pane" id="whiteLabel">

						<div class="row">

							<div class="col-md-12">

								<?php if ($this->session->flashdata('error') == '' && $this->session->flashdata('success') == '') : ?>

									<div class="alert alert-warning">
										<button type="button" class="close fui-cross" data-dismiss="alert"></button>
										<h4><?php echo $this->lang->line('settings_warning_heading'); ?></h4>
										<p>
											<?php echo $this->lang->line('settings_warning_message'); ?>
										</p>
									</div>

								<?php else : ?>

									<?php if ($this->session->flashdata('error') != '') : ?>
										<div class="alert alert-warning">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_error'); ?></h4>
											<?php echo $this->session->flashdata('error'); ?>
										</div>
									<?php endif; ?>

									<?php if ($this->session->flashdata('success') != '') : ?>
										<div class="alert alert-success">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('flashdata_success'); ?></h4>
											<?php echo $this->session->flashdata('success'); ?>
										</div>
									<?php endif; ?>

								<?php endif; ?>

								<form class="form-horizontal settingsForm" role="form" enctype='multipart/form-data' mean method="post" id="formWhiteLabel" action="<?php echo site_url() . 'settings/update_whitelabel#whiteLabel';?>">

									<div class="optionPane">

										<button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="confirmation" data-title="<?php echo $this->lang->line('settings_confirmation_are_you_sure');?>" data-btn-ok-label="<?php echo $this->lang->line('settings_confirmation_yes');?>" data-btn-cancel-label="<?php echo $this->lang->line('settings_confirmation_no');?>" data-on-confirm="resetLogo" data-placement="left"> <?php echo $this->lang->line('settings_button_reset_logo');?></button>

										<h6><?php echo $this->lang->line('settings_heading_logo');?></h6>
										
										<div class="form-group">
											<label for="" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_label_logo_image');?></label>
											<div class="col-sm-9">
												<div class="fileinput <?php if( $whitelabel_logo_image ):?>fileinput-exists<?php else:?>fileinput-new<?php endif;?>" data-provides="fileinput" id="fileiInputWidget">
											    <div class="input-group">
											      	<div class="form-control uneditable-input" data-trigger="fileinput">
											        	<span class="fui-clip fileinput-exists"></span>
											        	<span class="fileinput-filename"><?php if( $whitelabel_logo_image ){echo $whitelabel_logo_image;}?></span>
											      	</div>
											      	<span class="input-group-btn btn-file">
											        	<span class="btn btn-default fileinput-new" data-role="select-file">	<?php echo $this->lang->line('settings_fileinput_select');?>
											        	</span>
											        	<span class="btn btn-default fileinput-exists" data-role="change">
											        		<span class="fui-gear"></span> 
											        		<?php echo $this->lang->line('settings_fileinput_change');?>
											        	</span>
											        	<input type="file" name="inputLogoFile" value="" id="inputLogoUpload">
											        	<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
											        		<span class="fui-trash"></span>  
											        		<?php echo $this->lang->line('settings_fileinput_remove');?>
											        	</a>
											      </span>
											    </div>
											  </div>
											  <?php if( $whitelabel_logo_image ):?>
											  <input type="hidden" name="inputLogoFile_" id="inputLogoFile_" value="<?php echo $whitelabel_logo_image;?>">
											  <?php endif;?>
											</div>
										</div>

										<div class="form-group">
											<label for="textLogoText" class="col-sm-3 control-label"><?php echo $this->lang->line('settings_label_logo_text');?></label>
											<div class="col-sm-9">
												<textarea class="form-control" style="height: 40px" name="textLogoText" id="textLogoText"><?php if ( $whitelabel_logo_text ){echo $whitelabel_logo_text;}?></textarea>
											</div>
										</div>

									</div><!-- /.optionPane -->

									<div class="optionPane">

										<button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="confirmation" data-title="<?php echo $this->lang->line('settings_confirmation_are_you_sure');?>" data-btn-ok-label="<?php echo $this->lang->line('settings_confirmation_yes');?>" data-btn-cancel-label="<?php echo $this->lang->line('settings_confirmation_no');?>" data-on-confirm="resetColors" data-placement="left"> <?php echo $this->lang->line('settings_button_reset_colors');?></button>

										<h6><?php echo $this->lang->line('settings_heading_colors');?></h6>

										<ul class="nav nav-tabs nav-append-content">
  											<li class="active"><a href="#colors_general"><?php echo $this->lang->line('settings_colors_tab_general');?></a></li>
									  		<li><a href="#colors_sites"><?php echo $this->lang->line('settings_colors_tab_sites');?></a></li>
									  		<li><a href="#colors_pagebuilder"><?php echo $this->lang->line('settings_colors_tab_pagebuilder');?></a></li>
											<li><a href="#colors_images"><?php echo $this->lang->line('settings_colors_tab_images');?></a></li>
											<li><a href="#colors_templates"><?php echo $this->lang->line('settings_colors_tab_templates');?></a></li>
											<li><a href="#colors_elements"><?php echo $this->lang->line('settings_colors_tab_elements');?></a></li>
											<li><a href="#colors_packages"><?php echo $this->lang->line('settings_colors_tab_packages');?></a></li>
											<li><a href="#colors_users"><?php echo $this->lang->line('settings_colors_tab_users');?></a></li>
										</ul>

										<!-- Tab content -->
										<div class="tab-content">

											<div class="tab-pane active" id="colors_general">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_general');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_general_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body": "background-color"}' value="<?php echo (isset( $whitelabel_settings['body: background-color'] ))? $whitelabel_settings['body: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_general_page_h1_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"h1": "color"}' value="<?php echo (isset( $whitelabel_settings['h1: color'] ))? $whitelabel_settings['h1: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_general_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"hr.dashed": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['hr.dashed: border-top-color'] ))? $whitelabel_settings['hr.dashed: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_navigation');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav": "background-color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav: background-color'] ))? $whitelabel_settings['nav.mainnav: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_logo_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-brand": "color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-brand: color'] ))? $whitelabel_settings['nav.mainnav .navbar-brand: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_link_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-nav > li > a": "color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-nav > li > a: color'] ))? $whitelabel_settings['nav.mainnav .navbar-nav > li > a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_active_link_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-nav > .active > a, nav.mainnav .navbar-nav > li > a:hover, nav.mainnav .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:hover, .navbar-inverse .navbar-nav > .open > a:focus, nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-nav > .active > a, nav.mainnav .navbar-nav > li > a:hover, nav.mainnav .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:hover, .navbar-inverse .navbar-nav > .open > a:focus, nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a:hover: background-color'] ))? $whitelabel_settings['nav.mainnav .navbar-nav > .active > a, nav.mainnav .navbar-nav > li > a:hover, nav.mainnav .navbar-nav > .active > a:hover, .navbar-inverse .navbar-nav > .open > a, .navbar-inverse .navbar-nav > .open > a:hover, .navbar-inverse .navbar-nav > .open > a:focus, nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_active_link_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body nav.mainnav .navbar-nav > .active > a": "color"}' value="<?php echo (isset( $whitelabel_settings['body nav.mainnav .navbar-nav > .active > a: color'] ))? $whitelabel_settings['body nav.mainnav .navbar-nav > .active > a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_dropdown_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-nav > .open > .dropdown-menu": "background-color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-nav > .open > .dropdown-menu: background-color'] ))? $whitelabel_settings['nav.mainnav .navbar-nav > .open > .dropdown-menu: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_dropdown_arrow_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-nav > .dropdown > a .caret": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-nav > .dropdown > a .caret: border-top-color'] ))? $whitelabel_settings['nav.mainnav .navbar-nav > .dropdown > a .caret: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_navigation_dropdown_link_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a": "color"}' value="<?php echo (isset( $whitelabel_settings['nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a: color'] ))? $whitelabel_settings['nav.mainnav .navbar-nav > .open > .dropdown-menu > li > a: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_dropdown_selectors');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_button_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select .select2-choice": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.select .select2-choice: background-color'] ))? $whitelabel_settings['.select .select2-choice: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select2-drop": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.select2-drop: background-color'] ))? $whitelabel_settings['.select2-drop: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_link_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select2-drop .select2-result-selectable .select2-result-label": "color"}' value="<?php echo (isset( $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label: color'] ))? $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_active_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select2-drop .select2-highlighted > .select2-result-label": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.select2-drop .select2-highlighted > .select2-result-label: background-color'] ))? $whitelabel_settings['.select2-drop .select2-highlighted > .select2-result-label: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select2-drop .select2-result-selectable .select2-result-label:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label:hover: background-color'] ))? $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_dropdown_selectors_dropdown_hover_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".select2-drop .select2-result-selectable .select2-result-label:hover": "color"}' value="<?php echo (isset( $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label:hover: color'] ))? $whitelabel_settings['.select2-drop .select2-result-selectable .select2-result-label:hover: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_fields');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_fields_field_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".form-control, .input-group-btn .btn": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.form-control, .input-group-btn .btn: border-color'] ))? $whitelabel_settings['.form-control, .input-group-btn .btn: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_fields_focused_field_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".form-control:focus, .input-group.focus .input-group-btn .btn": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.form-control:focus, .input-group.focus .input-group-btn .btn: border-color'] ))? $whitelabel_settings['.form-control:focus, .input-group.focus .input-group-btn .btn: border-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_tabs');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_tabs_tab_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus": "border-color", ".tab-content": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: border-color'] ))? $whitelabel_settings['.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_tabs_tab_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus": "border-bottom-color", ".tab-content": "background-color", ".nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus": "background-color", ".nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus": "border-bottom-color", "body .nav-tabs > li.active > a": "background-color"}' value="<?php echo (isset( $whitelabel_settings['body .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: border-bottom-color'] ))? $whitelabel_settings['body .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: border-bottom-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_tabs_tab_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".nav-tabs > li > a": "color"}' value="<?php echo (isset( $whitelabel_settings['.nav-tabs > li > a: color'] ))? $whitelabel_settings['.nav-tabs > li > a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_tabs_tab_active_hover_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".nav-tabs > li.active > a, .nav-tabs > li > a:hover, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.nav-tabs > li.active > a, .nav-tabs > li > a:hover, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: color'] ))? $whitelabel_settings['.nav-tabs > li.active > a, .nav-tabs > li > a:hover, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_buttons');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_primary_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-primary, .open > .dropdown-toggle.btn-primary": "background-color"}' name='{".nav-tabs > li > a": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-primary, .open > .dropdown-toggle.btn-primary: background-color'] ))? $whitelabel_settings['.btn-primary, .open > .dropdown-toggle.btn-primary: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_primary_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-primary:hover, .btn-primary:active, .btn-primary:focus": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-primary:hover, .btn-primary:active, .btn-primary:focus: background-color'] ))? $whitelabel_settings['.btn-primary:hover, .btn-primary:active, .btn-primary:focus: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_primary_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus: color'] ))? $whitelabel_settings['.btn-primary, .btn-primary:hover, .btn-primary:active, .btn-primary:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_info_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-info": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-info: background-color'] ))? $whitelabel_settings['.btn-info: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_info_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-info:hover, .btn-info:active, .btn-info:focus": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-info:hover, .btn-info:active, .btn-info:focus: background-color'] ))? $whitelabel_settings['.btn-info:hover, .btn-info:active, .btn-info:focus: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_info_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-info, .btn-info:hover, .btn-info:active, .btn-info:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-info, .btn-info:hover, .btn-info:active, .btn-info:focus: color'] ))? $whitelabel_settings['.btn-info, .btn-info:hover, .btn-info:active, .btn-info:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_default_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-default": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-default: background-color'] ))? $whitelabel_settings['.btn-default: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_default_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-default:hover, .btn-default:active, .btn-default:focus": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-default:hover, .btn-default:active, .btn-default:focus: background-color'] ))? $whitelabel_settings['.btn-default:hover, .btn-default:active, .btn-default:focus: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_default_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-default, .btn-default:hover, .btn-default:active, .btn-default:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-default, .btn-default:hover, .btn-default:active, .btn-default:focus: color'] ))? $whitelabel_settings['.btn-default, .btn-default:hover, .btn-default:active, .btn-default:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_danger_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-danger": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-danger: background-color'] ))? $whitelabel_settings['.btn-danger: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_danger_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-danger:hover, .btn-danger:active, .btn-danger:focus": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-danger:hover, .btn-danger:active, .btn-danger:focus: background-color'] ))? $whitelabel_settings['.btn-danger:hover, .btn-danger:active, .btn-danger:focus: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_danger_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-danger, .btn-danger:hover, .btn-danger:active, .btn-danger:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-danger, .btn-danger:hover, .btn-danger:active, .btn-danger:focus: color'] ))? $whitelabel_settings['.btn-danger, .btn-danger:hover, .btn-danger:active, .btn-danger:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_inverse_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-inverse": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-inverse: background-color'] ))? $whitelabel_settings['.btn-inverse: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_inverse_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus: background-color'] ))? $whitelabel_settings['.btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_buttons_inverse_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".btn-inverse, .btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus": "color"}' value="<?php echo (isset( $whitelabel_settings['.btn-inverse, .btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus: color'] ))? $whitelabel_settings['.btn-inverse, .btn-inverse:hover, .btn-inverse:active, .btn-inverse:focus: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_modals');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_header_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-header": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.modal-header: background-color'] ))? $whitelabel_settings['.modal-header: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_header_text_color');?>r</label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-header": "color"}' value="<?php echo (isset( $whitelabel_settings['.modal-header: color'] ))? $whitelabel_settings['.modal-header: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_body_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-body": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.modal-body: background-color'] ))? $whitelabel_settings['.modal-body: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_body_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-body": "color"}' value="<?php echo (isset( $whitelabel_settings['.modal-body: color'] ))? $whitelabel_settings['.modal-body: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_footer_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-footer": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.modal-footer: background-color'] ))? $whitelabel_settings['.modal-footer: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_footer_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".modal-footer": "color"}' value="<?php echo (isset( $whitelabel_settings['.modal-footer: color'] ))? $whitelabel_settings['.modal-footer: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_panel_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".optionPane": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.optionPane: border-color'] ))? $whitelabel_settings['.optionPane: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_panel_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".optionPane": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.optionPane: background-color'] ))? $whitelabel_settings['.optionPane: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_modals_modal_panel_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".optionPane": "color"}' value="<?php echo (isset( $whitelabel_settings['.optionPane: color'] ))? $whitelabel_settings['.optionPane: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_general_alerts');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_success_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-success": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-success: background-color'] ))? $whitelabel_settings['.alert-success: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_success_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-success": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-success: border-color'] ))? $whitelabel_settings['.alert-success: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_success_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-success": "color"}' value="<?php echo (isset( $whitelabel_settings['.alert-success: color'] ))? $whitelabel_settings['.alert-success: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_danger_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-danger": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-danger: background-color'] ))? $whitelabel_settings['.alert-danger: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_danger_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-danger": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-danger: border-color'] ))? $whitelabel_settings['.alert-danger: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_danger_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-danger": "color"}' value="<?php echo (isset( $whitelabel_settings['.alert-danger: color'] ))? $whitelabel_settings['.alert-danger: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_warning_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-warning": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-warning: background-color'] ))? $whitelabel_settings['.alert-warning: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_warning_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-warning": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.alert-warning: border-color'] ))? $whitelabel_settings['.alert-warning: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_general_alerts_alert_warning_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".alert-warning": "color"}' value="<?php echo (isset( $whitelabel_settings['.alert-warning: color'] ))? $whitelabel_settings['.alert-warning: color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_sites">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_sites_site_grid');?></span></div>

												<div class="alert alert-info">
													<button class="close fui-cross" data-dismiss="alert"></button>
												  	<?php echo $this->lang->line('settings_colors_sites_alert');?>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_site_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site: border-color'] ))? $whitelabel_settings['.sites .site: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_site_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site: background-color'] ))? $whitelabel_settings['.sites .site: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_site_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site": "color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site: color'] ))? $whitelabel_settings['.sites .site: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_window_top_bottom_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".window .top, .window .bottom": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.window .top, .window .bottom: background-color'] ))? $whitelabel_settings['.window .top, .window .bottom: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_window_top_bottom_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".window .top, .window .bottom": "color"}' value="<?php echo (isset( $whitelabel_settings['.window .top, .window .bottom: color'] ))? $whitelabel_settings['.window .top, .window .bottom: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_window_top_red_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".window .top .buttons .red": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.window .top .buttons .red: background-color'] ))? $whitelabel_settings['.window .top .buttons .red: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_window_top_yellow_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".window .top .buttons .yellow": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.window .top .buttons .yellow: background-color'] ))? $whitelabel_settings['.window .top .buttons .yellow: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_window_top_green_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".window .top .buttons .green": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.window .top .buttons .green: background-color'] ))? $whitelabel_settings['.window .top .buttons .green: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites hr.dashed.light": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['.sites hr.dashed.light: border-top-color'] ))? $whitelabel_settings['.sites hr.dashed.light: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_public_site_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site.homepage": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site.homepage: background-color'] ))? $whitelabel_settings['.sites .site.homepage: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_public_site_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site.homepage": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site.homepage: border-color'] ))? $whitelabel_settings['.sites .site.homepage: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_public_site_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .site.homepage": "color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site.homepage: color'] ))? $whitelabel_settings['.sites .site.homepage: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_site_grid_public_site_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .homepage hr.dashed.light": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .site.homepage: border-top-color'] ))? $whitelabel_settings['.sites .site.homepage: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_sites_new_site_modal');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_new_site_modal_sidebar_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .newSiteModal .modal-content .modal-body ul.catList": "background-color", ".sites .newSiteModal .modal-content .modal-body ul.catList li button": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList: background-color'] ))? $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_new_site_modal_sidebar_button_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .newSiteModal .modal-content .modal-body ul.catList li button": "color"}' value="<?php echo (isset( $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button: color'] ))? $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_new_site_modal_sidebar_active_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover: background-color'] ))? $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_new_site_modal_sidebar_active_hover_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover": "color"}' value="<?php echo (isset( $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover: color'] ))? $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body ul.catList li button.active, .sites .newSiteModal .modal-content .modal-body ul.catList li button:hover: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_sites_new_site_modal_sidebar_selected_template_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".sites .newSiteModal .modal-content .modal-body .templateWrapper ul.templateList li a.active img": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body .templateWrapper ul.templateList li a.active img: border-color'] ))? $whitelabel_settings['.sites .newSiteModal .modal-content .modal-body .templateWrapper ul.templateList li a.active img: border-color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_pagebuilder">

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_top_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body.builderUI > header": "background-color"}' value="<?php echo (isset( $whitelabel_settings['body.builderUI > header: background-color'] ))? $whitelabel_settings['body.builderUI > header: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_top_button_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".nav-pills.responsiveToggle > li > a, body.builderUI > header .slick, body.builderUI > header .slick button": "background-color", ".btn-inverse": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.nav-pills.responsiveToggle > li > a, body.builderUI > header .slick, body.builderUI > header .slick button: background-color'] ))? $whitelabel_settings['.nav-pills.responsiveToggle > li > a, body.builderUI > header .slick, body.builderUI > header .slick button: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_top_button_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body.builderUI > header .slick, body.builderUI > header .slick button": "color", ".nav-pills.responsiveToggle > li > a svg g path": "fill", "header .btn-inverse .caret": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['body.builderUI > header .slick, body.builderUI > header .slick button: color'] ))? $whitelabel_settings['body.builderUI > header .slick, body.builderUI > header .slick button: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_top_button_active_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".nav-pills.responsiveToggle > li.active > a": "background-color", ".builderUI .open > .dropdown-toggle.btn-inverse, header .dropdown-menu-inverse": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.nav-pills.responsiveToggle > li.active > a: background-color'] ))? $whitelabel_settings['.nav-pills.responsiveToggle > li.active > a: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_left_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".bootstrap-switch-off .bootstrap-switch-handle-on ~ .bootstrap-switch-handle-off.bootstrap-switch-default:before": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.bootstrap-switch-off .bootstrap-switch-handle-on ~ .bootstrap-switch-handle-off.bootstrap-switch-default:before: background-color'] ))? $whitelabel_settings['.bootstrap-switch-off .bootstrap-switch-handle-on ~ .bootstrap-switch-handle-off.bootstrap-switch-default:before: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_right_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"header .bootstrap-switch-default": "background-color"}' value="<?php echo (isset( $whitelabel_settings['header .bootstrap-switch-default: background-color'] ))? $whitelabel_settings['header .bootstrap-switch-default: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_deactivated_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"header .bootstrap-switch-default svg g path": "fill"}' value="<?php echo (isset( $whitelabel_settings['header .bootstrap-switch-default svg g path: fill'] ))? $whitelabel_settings['header .bootstrap-switch-default svg g path: fill']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_left_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"header .bootstrap-switch-primary": "background-color"}' value="<?php echo (isset( $whitelabel_settings['header .bootstrap-switch-primary: background-color'] ))? $whitelabel_settings['header .bootstrap-switch-primary: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_right_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"header .bootstrap-switch-primary ~ .bootstrap-switch-handle-off:before": "background-color"}' value="<?php echo (isset( $whitelabel_settings['header .bootstrap-switch-primary ~ .bootstrap-switch-handle-off:before: background-color'] ))? $whitelabel_settings['header .bootstrap-switch-primary ~ .bootstrap-switch-handle-off:before: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_topnavbar_gridview_toggle_activated_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"header .bootstrap-switch-primary svg g path": "fill"}' value="<?php echo (isset( $whitelabel_settings['header .bootstrap-switch-primary svg g path: fill'] ))? $whitelabel_settings['header .bootstrap-switch-primary svg g path: fill']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .side": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .side: background-color'] ))? $whitelabel_settings['.builderUI .side: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_link_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI nav button svg g path": "fill", ".builderUI .side button span, .sideSecondInner ul li a": "color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI nav button svg g path: fill'] ))? $whitelabel_settings['.builderUI nav button svg g path: fill']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_link_icon_active_hover_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI nav button:hover svg g path, .builderUI nav button.active svg g path": "fill", ".builderUI .side button:hover span, .builderUI .side button.active span": "color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI nav button:hover svg g path, .builderUI nav button.active svg g path: fill'] ))? $whitelabel_settings['.builderUI nav button:hover svg g path, .builderUI nav button.active svg g path: fill']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_link_icon_active_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .side button:hover, .builderUI .side button.active": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .side button:hover, .builderUI .side button.active: background-color'] ))? $whitelabel_settings['.builderUI .side button:hover, .builderUI .side button.active: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_slideout_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .builderLayout > .sideSecond, .builderUI .builderLayout > .sideSecond .sideSecondInner .heading": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .builderLayout > .sideSecond, .builderUI .builderLayout > .sideSecond .sideSecondInner .heading: background-color'] ))? $whitelabel_settings['.builderUI .builderLayout > .sideSecond, .builderUI .builderLayout > .sideSecond .sideSecondInner .heading: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_slideout_header_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .builderLayout > .sideSecond .sideSecondInner h4": "color", ".builderUI .builderLayout > .sideSecond .sideSecondInner button.closeSideSecond svg g path, .builderUI .builderLayout > .sideSecond .sideSecondInner button.closeSideSecond svg g polygon": "fill"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .builderLayout > .sideSecond .sideSecondInner h4: color'] ))? $whitelabel_settings['.builderUI .builderLayout > .sideSecond .sideSecondInner h4: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_slideout_button_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .builderLayout > .sideSecond nav button span": "color", ".builderUI .builderLayout > .sideSecond nav button svg g polygon": "fill"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .builderLayout > .sideSecond nav button span: color'] ))? $whitelabel_settings['.builderUI .builderLayout > .sideSecond nav button span: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_slideout_button_hover_active_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .builderLayout > .sideSecond nav button:hover span": "color", ".builderUI .builderLayout > .sideSecond nav button:hover svg g polygon": "fill", ".builderUI .builderLayout > .sideSecond .sideSecondInner ul li.active a, .builderUI .builderLayout > .sideSecond .sideSecondInner ul li a:hover": "color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .builderLayout > .sideSecond nav button:hover span: color'] ))? $whitelabel_settings['.builderUI .builderLayout > .sideSecond nav button:hover span: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_slideout_button_hover_active_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body.builderUI .builderLayout > .sideSecond nav button.active, body.builderUI .builderLayout > .sideSecond nav button:hover": "background-color", ".builderUI .builderLayout > .sideSecond .sideSecondInner ul li.active, .builderUI .builderLayout > .sideSecond .sideSecondInner ul li:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['body.builderUI .builderLayout > .sideSecond nav button.active, body.builderUI .builderLayout > .sideSecond nav button:hover: background-color'] ))? $whitelabel_settings['body.builderUI .builderLayout > .sideSecond nav button.active, body.builderUI .builderLayout > .sideSecond nav button:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_sidebar_dashed_border_color');?>r</label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderUI .builderLayout > .sideSecond .sideSecondInner hr": "border-top-color"}' value="<?php echo (isset( $whitelabel_settings['.builderUI .builderLayout > .sideSecond .sideSecondInner hr border-top-color'] ))? $whitelabel_settings['.builderUI .builderLayout > .sideSecond .sideSecondInner hr: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_pagebuilder_canvas');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"body.builderUI": "background-color"}' value="<?php echo (isset( $whitelabel_settings['body.builderUI: background-color'] ))? $whitelabel_settings['body.builderUI: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_window_bar_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .toolbar": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .toolbar: background-color'] ))? $whitelabel_settings['.screen .toolbar: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_window_bar_title_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .toolbar .title": "color"}' value="<?php echo (isset( $whitelabel_settings['.screen .toolbar .title: color'] ))? $whitelabel_settings['.screen .toolbar .title: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_window_bar_red_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .toolbar .left.red": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .toolbar .left.red: background-color'] ))? $whitelabel_settings['.screen .toolbar .left.red: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_window_bar_yellow_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .toolbar .left.yellow": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .toolbar .left.yellow: background-color'] ))? $whitelabel_settings['.screen .toolbar .left.yellow: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_canvas_window_bar_green_button');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .toolbar .left.green": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .toolbar .left.green: background-color'] ))? $whitelabel_settings['.screen .toolbar .left.green: background-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_left_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover: background-color'] ))? $whitelabel_settings['.screen .frameCover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_left_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover > span": "color", ".screen .frameCover label.checkbox .icons, .screen .frameCover label.checkbox": "color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover > span: color'] ))? $whitelabel_settings['.screen .frameCover > span: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_checkbox_checked_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover label.checkbox .icons .icon-checked": "color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover label.checkbox .icons .icon-checked: color'] ))? $whitelabel_settings['.screen .frameCover label.checkbox .icons .icon-checked: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_button_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover button": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover button: background-color'] ))? $whitelabel_settings['.screen .frameCover button: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_button_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover button i": "color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover button i: color'] ))? $whitelabel_settings['.screen .frameCover button i: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_button_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover button:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover button:hover: background-color'] ))? $whitelabel_settings['.screen .frameCover button:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_frametoolbar_button_hover_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".screen .frameCover button:hover i": "color"}' value="<?php echo (isset( $whitelabel_settings['.screen .frameCover button:hover i: color'] ))? $whitelabel_settings['.screen .frameCover button:hover i: color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor: background-color'] ))? $whitelabel_settings['.styleEditor: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_title_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor h3": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor h3: color'] ))? $whitelabel_settings['.styleEditor h3: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_close_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".close": "color"}' value="<?php echo (isset( $whitelabel_settings['.close: color'] ))? $whitelabel_settings['.close: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_breadcrumb_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor ul.breadcrumb": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor ul.breadcrumb: background-color'] ))? $whitelabel_settings['.styleEditor ul.breadcrumb: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_breadcrumb_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor ul.breadcrumb": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor ul.breadcrumb: color'] ))? $whitelabel_settings['.styleEditor ul.breadcrumb: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_breadcrumd_linkicon_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .breadcrumb > li:after, .styleEditor .breadcrumb .active": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .breadcrumb > li:after, .styleEditor .breadcrumb .active: color'] ))? $whitelabel_settings['.styleEditor .breadcrumb > li:after, .styleEditor .breadcrumb .active: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_tab_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .nav-tabs > li > a": "color", ".styleEditor .nav-tabs li span": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .nav-tabs > li > a: color'] ))? $whitelabel_settings['.styleEditor .nav-tabs > li > a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_active_tab_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor ul.nav-tabs li.active a": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: background-color'] ))? $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label">active tab border color</label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor ul.nav-tabs li.active a": "border-top-color", "body .styleEditor ul.nav-tabs li.active a": "border-left-color", ".builderUI .styleEditor ul.nav-tabs li.active a": "border-right-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: border-top-color'] ))? $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: border-top-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_active_tab_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor ul.nav-tabs li.active a": "color", ".styleEditor ul.nav-tabs li.active a span": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: color'] ))? $whitelabel_settings['.styleEditor ul.nav-tabs li.active a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_label_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .control-label": "color", ".iconTab label": "color", ".link_Tab .checkbox": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .control-label: color'] ))? $whitelabel_settings['.styleEditor .control-label: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_parallax_warning_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .alert": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .alert: border-color'] ))? $whitelabel_settings['.styleEditor .alert: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_parallax_warning_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .alert": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .alert: background-color'] ))? $whitelabel_settings['.styleEditor .alert: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_parallax_warning_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .alert": "color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .alert: color'] ))? $whitelabel_settings['.styleEditor .alert: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_parallax_warning_close_icon');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .alert .close": "color"}' value="<?php echo (isset( $whitelabel_settings['.alert .close: color'] ))? $whitelabel_settings['.alert .close: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".styleEditor .tab-content": "border-bottom-color"}' value="<?php echo (isset( $whitelabel_settings['.styleEditor .tab-content: border-bottom-color'] ))? $whitelabel_settings['.styleEditor .tab-content: border-bottom-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_pagebuilder_detaileditor_image_tab_toggle_link');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".showHide > label": "color"}' value="<?php echo (isset( $whitelabel_settings['.showHide > label: color'] ))? $whitelabel_settings['.showHide > label: color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_images">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_images_grid');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_add_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .slimWrapper .slim": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.images .slimWrapper .slim: background-color'] ))? $whitelabel_settings['.images .slimWrapper .slim: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_add_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .slimWrapper .slim": "color"}' value="<?php echo (isset( $whitelabel_settings['.images .slimWrapper .slim: color'] ))? $whitelabel_settings['.images .slimWrapper .slim: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_add_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .slimWrapper": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.images .slimWrapper: border-color'] ))? $whitelabel_settings['.images .slimWrapper: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_add_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .slimWrapper .slim:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.images .slimWrapper .slim:hover: background-color'] ))? $whitelabel_settings['.images .slimWrapper .slim:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_add_hover_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .slimWrapper .slim:hover": "color"}' value="<?php echo (isset( $whitelabel_settings['.images .slimWrapper .slim:hover: color'] ))? $whitelabel_settings['.images .slimWrapper .slim:hover: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .image": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.images .image: background-color'] ))? $whitelabel_settings['.images .image: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .image": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.images .image: border-color'] ))? $whitelabel_settings['.images .image: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .image:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.images .image:hover: background-color'] ))? $whitelabel_settings['.images .image:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_hover_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".images .image:hover": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.images .image:hover: border-color'] ))? $whitelabel_settings['.images .image:hover: border-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_images_edit_panel');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_edit_panel_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".imageLibraryWrapper > .imageDetailPanel": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel: background-color'] ))? $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_edit_panel_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".imageLibraryWrapper > .imageDetailPanel": "color"}' value="<?php echo (isset( $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel: color'] ))? $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_edit_panel_image_link_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".imageLibraryWrapper > .imageDetailPanel .linkFullImage": "color"}' value="<?php echo (isset( $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .linkFullImage: color'] ))? $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .linkFullImage: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_edit_panel_dashed_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".imageLibraryWrapper > .imageDetailPanel .slimEditImageWrapper": "border-bottom-color", ".imageLibraryWrapper > .imageDetailPanel .imageDimensionsWrapper": "border-bottom-color"}' value="<?php echo (isset( $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .slimEditImageWrapper: border-bottom-color'] ))? $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .slimEditImageWrapper: border-bottom-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_images_edit_panel_delete_link_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".imageLibraryWrapper > .imageDetailPanel .imageMoreActions a.deleteImage": "color"}' value="<?php echo (isset( $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .imageMoreActions a.deleteImage: color'] ))? $whitelabel_settings['.imageLibraryWrapper > .imageDetailPanel .imageMoreActions a.deleteImage: color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_templates">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_templates_category_modal');?></span></div>

												<div class="alert alert-info">
													<button class="close fui-cross" data-dismiss="alert"></button>
												  	<?php echo $this->lang->line('settings_colors_templates_alert');?>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_templates_category_modal_table_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: border-color'] ))? $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_templates_category_modal_table_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: background-color'] ))? $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_templates_category_modal_table_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th": "color"}' value="<?php echo (isset( $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: color'] ))? $whitelabel_settings['.manageCategoriesModal .modal-body table, .manageCategoriesModal .modal-body table td, .manageCategoriesModal .modal-body table th: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_templates_category_modal_table_heading_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".manageCategoriesModal .modal-body table th": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.manageCategoriesModal .modal-body table th: background-color'] ))? $whitelabel_settings['.manageCategoriesModal .modal-body table th: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_templates_category_modal_table_heading_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".manageCategoriesModal .modal-body table th": "color"}' value="<?php echo (isset( $whitelabel_settings['.manageCategoriesModal .modal-body table th: color'] ))? $whitelabel_settings['.manageCategoriesModal .modal-body table th: color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_elements">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_elements_blocks_grid');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_blocks_grid_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsBlocks .block": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsBlocks .block: border-color'] ))? $whitelabel_settings['.builderElementsBlocks .block: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_blocks_grid_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsBlocks .block": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsBlocks .block: background-color'] ))? $whitelabel_settings['.builderElementsBlocks .block: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_blocks_grid_hover_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsBlocks .block:hover": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsBlocks .block:hover: border-color'] ))? $whitelabel_settings['.builderElementsBlocks .block:hover: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_blocks_grid_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsBlocks .block:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsBlocks .block:hover: background-color'] ))? $whitelabel_settings['.builderElementsBlocks .block:hover: background-color']: ''; ?>">
													</div>
												</div>

												<div class="divider"><span><?php echo $this->lang->line('settings_colors_elements_components_grid');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_components_grid_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsComponents .component": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsComponents .component: border-color'] ))? $whitelabel_settings['.builderElementsComponents .component: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_components_grid_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsComponents .component": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsComponents .component: background-color'] ))? $whitelabel_settings['.builderElementsComponents .component: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_components_grid_hover_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsComponents .component:hover": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsComponents .component:hover: border-color'] ))? $whitelabel_settings['.builderElementsComponents .component:hover: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_elements_components_grid_hover_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".builderElementsComponents .component:hover": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.builderElementsComponents .component:hover: background-color'] ))? $whitelabel_settings['.builderElementsComponents .component:hover: background-color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_packages">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_packages_table');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_packages_table_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"table.packages, table.packages td, table.packages th": "border-color"}' value="<?php echo (isset( $whitelabel_settings['table.packages, table.packages td, table.packages th: border-color'] ))? $whitelabel_settings['table.packages, table.packages td, table.packages th: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_packages_table_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"table.packages, table.packages td, table.packages th": "background-color"}' value="<?php echo (isset( $whitelabel_settings['table.packages, table.packages td, table.packages th: background-color'] ))? $whitelabel_settings['table.packages, table.packages td, table.packages th: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_packages_table_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"table.packages, table.packages td, table.packages th": "color"}' value="<?php echo (isset( $whitelabel_settings['table.packages, table.packages td, table.packages th: color'] ))? $whitelabel_settings['table.packages, table.packages td, table.packages th: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_packages_table_heading_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"table.packages th": "background-color"}' value="<?php echo (isset( $whitelabel_settings['table.packages th: background-color'] ))? $whitelabel_settings['table.packages th: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_packages_table_heading_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{"table.packages th": "color"}' value="<?php echo (isset( $whitelabel_settings['table.packages th: color'] ))? $whitelabel_settings['table.packages th: color']: ''; ?>">
													</div>
												</div>

											</div>

											<div class="tab-pane" id="colors_users">
												
												<div class="divider"><span><?php echo $this->lang->line('settings_colors_user_grid');?></span></div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user": "border-color"}' value="<?php echo (isset( $whitelabel_settings['.users .user: border-color'] ))? $whitelabel_settings['.users .user: border-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_top_background_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user .topPart": "background-color"}' value="<?php echo (isset( $whitelabel_settings['.users .user .topPart: background-color'] ))? $whitelabel_settings['.users .user .topPart: background-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_top_bottom_border_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user .topPart": "border-bottom-color"}' value="<?php echo (isset( $whitelabel_settings['.users .user .topPart: border-bottom-color'] ))? $whitelabel_settings['.users .user .topPart: border-bottom-color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_top_heading_text_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user .topPart .details h4": "color"}' value="<?php echo (isset( $whitelabel_settings['.users .user .topPart .details h4: color'] ))? $whitelabel_settings['.users .user .topPart .details h4: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_top_link_text_color');?>r</label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user .topPart .details a": "color"}' value="<?php echo (isset( $whitelabel_settings['.users .user .topPart .details a: color'] ))? $whitelabel_settings['.users .user .topPart .details a: color']: ''; ?>">
													</div>
												</div>

												<div class="form-group">
													<label for="" class="col-sm-6 control-label"><?php echo $this->lang->line('settings_colors_user_top_link_icon_color');?></label>
													<div class="col-sm-6">
														<input class="form-control spectrum" style="height: 40px" name='{".users .user .topPart .details p > span": "color"}' value="<?php echo (isset( $whitelabel_settings['.users .user .topPart .details p > span: color'] ))? $whitelabel_settings['.users .user .topPart .details p > span: color']: ''; ?>">
													</div>
												</div>

											</div>

										</div><!-- /.tab-content -->

									</div><!-- /.optionPane -->

									<div class="optionPane">

										<button type="button" class="btn btn-sm btn-warning pull-right" data-toggle="confirmation" data-title="Are you sure?" data-btn-ok-label="Yes" data-btn-cancel-label="Cancel" data-on-confirm="resetCss" data-placement="left"> Reset white-label CSS</button>

										<h6>Custom CSS</h6>

										<div class="form-group">
											<label for="" class="col-sm-3 control-label">Custom CSS</label>
											<div class="col-sm-9">
												<div style="height: 200px" id="customCSS"></div>
											</div>
											<textarea name="textAreaCustomCSS" id="textAreaCustomCSS" style="display: none"><?php if ( isset($whitelabel_custom_css) && $whitelabel_custom_css && $whitelabel_custom_css != '' ) {echo $whitelabel_custom_css;}?></textarea>
										</div>

									</div><!-- /.optionPane -->

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-9">
											<p class="text-danger">
												<?php echo $this->lang->line('settings_requiredfields'); ?>
											</p>
											<button type="submit" class="btn btn-primary btn-wide" id="buttonWhiteLabelUpdate"><span class="fui-check"></span> <?php echo $this->lang->line('settings_button_update'); ?></button>
										</div>
									</div>

								</form>

							</div><!-- /.col -->

						</div><!-- /.row -->

					</div>

					<div class="tab-pane" id="integration">

						<div class="row">

							<div class="col-md-8">

								<form class="form-horizontal settingsForm" action="<?php echo site_url() . 'settings/update_integration#integration';?>" method="post">

									<?php if ($this->session->flashdata('error') == '' && $this->session->flashdata('success') == '') : ?>

										<div class="alert alert-warning">
											<button type="button" class="close fui-cross" data-dismiss="alert"></button>
											<h4><?php echo $this->lang->line('settings_warning_heading'); ?></h4>
											<p>
												<?php echo $this->lang->line('settings_warning_message'); ?>
											</p>
										</div>

									<?php else : ?>

										<?php if ($this->session->flashdata('error') != '') : ?>
											<div class="alert alert-warning">
												<button type="button" class="close fui-cross" data-dismiss="alert"></button>
												<h4><?php echo $this->lang->line('flashdata_error'); ?></h4>
												<?php echo $this->session->flashdata('error'); ?>
											</div>
										<?php endif; ?>

										<?php if ($this->session->flashdata('success') != '') : ?>
											<div class="alert alert-success">
												<button type="button" class="close fui-cross" data-dismiss="alert"></button>
												<h4><?php echo $this->lang->line('flashdata_success'); ?></h4>
												<?php echo $this->session->flashdata('success'); ?>
											</div>
										<?php endif; ?>

									<?php endif; ?>

									<div class="optionPane">

										<h6><?php echo $this->lang->line('settings_heading_api');?></h6>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_integration_api_on');?></label>
											<div class="col-sm-9">
												<input type="checkbox" value="1" <?php if ($this->config->item('rest_on') == 1) : ?>checked<?php endif; ?> name="rest_on" data-toggle="switch" id="rest_on">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_integration_api_whitelist_enabled');?></label>
											<div class="col-sm-9">
												<input type="checkbox" value="1" <?php if ($this->config->item('rest_ip_whitelist_enabled') == 1) : ?>checked<?php endif; ?> name="rest_ip_whitelist_enabled" data-toggle="switch" id="rest_ip_whitelist_enabled">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_integration_api_whitelist');?></label>
											<div class="col-sm-9">
												<input type="text" class="tagsinput" data-role="tagsinput" name="rest_ip_whitelist" id="rest_ip_whitelist" value="<?php echo $this->config->item('rest_ip_whitelist');?>">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_integration_api_keys');?></label>
											<div class="col-sm-9">
												<input type="text" class="tagsinput" data-role="tagsinput" name="rest_keys" id="rest_keys" value="<?php echo $rest_api_keys;?>">
											</div>
										</div>

									</div><!-- /.optionPane -->

									<div class="optionPane">

										<h6><?php echo $this->lang->line('settings_heading_alogin');?></h6>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_heading_alogin_on');?></label>
											<div class="col-sm-9">
												<input type="checkbox" value="1" <?php if ($this->config->item('alogin_on') == 1) : ?>checked<?php endif; ?> name="alogin_on" data-toggle="switch" id="alogin_on">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-3 control-label"><?php echo $this->lang->line('settings_heading_alogin_key');?></label>
											<div class="col-sm-9">
												<input type="text" class="form-control" name="alogin_key" id="alogin_key" value="<?php echo $this->config->item('alogin_key');?>">
											</div>
										</div>

									</div><!-- /.optionPane -->

									<div class="form-group">
											<div class="col-sm-offset-3 col-sm-9">
												<p class="text-danger">
													<?php echo $this->lang->line('settings_requiredfields'); ?>
												</p>
												<button type="submit" class="btn btn-primary btn-wide"><span class="fui-check"></span> <?php echo $this->lang->line('settings_button_update'); ?></button>
											</div>
										</div>

								</form>

							</div><!-- /.col -->

							<div class="col-md-4">

								<div class="alert alert-info configHelp" id="configHelp">
									<button type="button" class="close fui-cross" data-dismiss="alert"></button>
									<div>
										<h4>
											<?php echo $this->lang->line('settings_confighelp_heading'); ?>
										</h4>
										<p>
											<?php echo $this->lang->line('settings_confighelp_message'); ?>
										</p>
									</div>
								</div>

							</div><!-- /.col -->

						</div>

					</div>

				</div> <!-- /tab-content -->

			</div><!-- /.col -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<!-- Modal -->

	<?php $this->load->view("shared/modal_account.php"); ?>

	<div class="modal fade paypalWarningModal" id="paypalWarningModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-dialog">

			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
					<h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> Using Paypal to process payments</h4>
				</div>

				<div class="modal-body">

					<p>
						To be able to use Paypal to process payments for your Bloxby application, you will need to make sure of the following:
					</p>

					<ol>
						<li>
							You have a <b>Paypal Business account</b><br>
							To learn more on how to setup a Paypal Business account, please have a look at the following articles:
							<ul>
								<li><a href="http://www.makeuseof.com/tag/set-paypal-account-business/" target="_blank">http://www.makeuseof.com/tag/set-paypal-account-business/</a></li>
								<li><a href="https://www.paypal.com/us/webapps/mpp/merchant" target="_blank">https://www.paypal.com/us/webapps/mpp/merchant</a></li>
							</ul>
						</li>
						<li>
							Your Paypal account needs to be configured to accept payment for digitally delivered goods (Express Checkout). If you're having trouble activating Express Checkout for digital goods, we advice to reach out to Paypal customer support.
						</li>
					</ol>

				</div><!-- /.modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_close'); ?></button>
				</div>

			</div><!-- /.modal-content -->

		</div><!-- /.modal-dialog -->

	</div><!-- /.modal -->


	<!-- /modals -->

	<!-- Load JS here for greater good =============================-->
	<?php if (ENVIRONMENT == 'production') : ?>
		<script src="<?php echo base_url('build/settings.bundle.js') . '?' . filemtime( FCPATH . 'build/settings.bundle.js'); ?>"></script>
	<?php elseif (ENVIRONMENT == 'development') : ?>
		<script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/settings.bundle.js"></script>
	<?php endif; ?>

</body>
</html>