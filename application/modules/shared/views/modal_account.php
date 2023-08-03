<div class="modal fade accountModal" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
				<h4 class="modal-title" id="myModalLabel"> <?php echo $this->lang->line('account_myaccount'); ?></h4>
			</div>

			<div class="modal-body padding-top-40">

				<ul class="nav nav-tabs nav-append-content">
					<li class="active"><a href="#myAccount"><span class="fui-user"></span> <?php echo $this->lang->line('account_tab_account'); ?></a></li>
					<?php if ($this->session->userdata('user_type') != 'Admin') : ?>
						<li><a href="#myMembership"><span class="fui-user"></span> <?php echo $this->lang->line('account_tab_membership'); ?></a></li>
					<?php endif; ?>
				</ul> <!-- /tabs -->

				<div class="tab-content">

					<div class="tab-pane active" id="myAccount">

						<form class="form-horizontal" role="form" id="account_details" >

							<div class="loader" style="display: none;">
								<img src="<?php echo base_url(); ?>img/91.gif" alt="<?php echo $this->lang->line('loading'); ?>">
							</div>

							<div class="alerts"></div>

							<input type="hidden" name="id" value="<?php echo $this->session->userdata('user_id'); ?>">
							<div class="form-group">
								<label for="first_name" class="col-md-3 control-label"><?php echo $this->lang->line('account_label_first_name'); ?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo $this->lang->line('account_label_first_name'); ?>" value="<?php echo $this->session->userdata('user_fname'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="last_name" class="col-md-3 control-label"><?php echo $this->lang->line('account_label_last_name')?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo $this->lang->line('account_label_last_name')?>" value="<?php echo $this->session->userdata('user_lname'); ?>">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" class="btn btn-primary btn-embossed btn-block" id="accountDetailsSubmit"><span class="fui-check"></span> <?php echo $this->lang->line('account_button_updatedetails')?></button>
								</div>
							</div>
						</form>

						<hr class="dashed">

						<form class="form-horizontal" role="form" id="account_login">

							<div class="loader" style="display: none;">
								<img src="<?php echo base_url();?>img/91.gif" alt="<?php echo $this->lang->line('loading'); ?>">
							</div>

							<div class="alerts"></div>

							<input type="hidden" name="id" value="<?php echo $this->session->userdata('user_id'); ?>">
							<div class="form-group">
								<label for="email" class="col-md-3 control-label"><?php echo $this->lang->line('account_label_username')?></label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="email" name="email" placeholder="<?php echo $this->lang->line('account_label_username')?>" value="<?php echo $this->session->userdata('user_email'); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="password" class="col-md-3 control-label"><?php echo $this->lang->line('account_label_password')?></label>
								<div class="col-md-9">
									<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('account_label_password')?>" value="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" class="btn btn-primary btn-embossed btn-block" id="accountLoginSubmit"><span class="fui-check"></span> <?php echo $this->lang->line('account_button_updatedetails')?></button>
								</div>
							</div>
						</form>

					</div>
					<?php if ($this->session->userdata('user_type') != 'Admin') : ?>
						<div class="tab-pane" id="myMembership">

							<form class="form-horizontal" role="form" id="package_update" >

								<div class="loader" style="display: none;">
									<img src="<?php echo base_url();?>img/91.gif" alt="<?php echo $this->lang->line('loading'); ?>">
								</div>

								<div class="alerts"></div>

								<input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
								<div class="form-group margin-bottom-0">
									<label for="package_id" class="col-md-3 control-label"><?php echo $this->lang->line('account_label_package'); ?></label>
									<div class="col-md-9">
										<select name="package_id" id="package_id" class="form-control select select-block mbl select-inverse">
											<option value="" selected=""><?php echo $this->lang->line('account_label_package_option'); ?></option>
											<?php
											foreach ($packages as $package)
											{
												?>
												<option value="<?php echo $package['id']; ?>" <?php if ($package['id'] == $this->session->userdata('package_id')) { echo 'selected'; } ?>><?php echo $package['name'] . ' - ' . $package['price'] . ' ' . $package['currency'] . ' / ' . $package['subscription'] . ' ' . $this->lang->line('account_label_package_months'); ?></option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-offset-3 col-md-9">
										<button type="button" class="btn btn-primary btn-embossed btn-block" id="accountPackageUpdate"><span class="fui-check"></span> <?php echo $this->lang->line('account_button_update_package')?></button>
									</div>
								</div>
							</form>

							<hr class="dashed">

							<form class="form-horizontal" role="form" id="package_cancel">

								<div class="loader" style="display: none;">
									<img src="<?php echo base_url();?>img/91.gif" alt="<?php echo $this->lang->line('loading'); ?>">
								</div>

								<div class="alerts"></div>

								<input type="hidden" name="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
								<div class="form-group">
									<div class="col-md-12">
										<button type="button" class="btn btn-danger btn-embossed btn-block" id="accountPackageCancel"><span class="fui-check"></span> <?php echo $this->lang->line('account_button_cancel_package')?></button>
									</div>
								</div>
							</form>

						</div>
					<?php endif; ?>

				</div> <!-- /tab-content -->

			</div><!-- /.modal-body -->

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose')?></button>
			</div>

		</div><!-- /.modal-content -->

	</div><!-- /.modal-dialog -->

</div><!-- /.modal -->