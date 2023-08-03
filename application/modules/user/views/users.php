<?php $this->load->view("shared/header.php"); ?>

<body>

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

		<div class="row">

			<div class="col-md-9 col-sm-8">
				<h1><span class="fui-user"></span> <?php echo $this->lang->line('users_heading'); ?></h1>
			</div><!-- /.col -->

			<div class="col-md-3 col-sm-4 text-right">
				<a href="#newUserModal" <?php if (bloxby_package() != 0 && count($this->MUsers->get_all('User', 'Active')) >= bloxby_package()) { ?> disabled="true"<?php } ?> data-toggle="modal" class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-40 add"><span class="fui-plus"></span> <?php echo $this->lang->line('users_button_addnew'); ?></a>
			</div><!-- /.col -->

		</div><!-- /.row -->

		<hr class="dashed margin-bottom-30">

    <div class="row">
        <table id="datatable" class="table table-striped table-bordered dt-responsive nowrap display" width="100%">
            <thead>
            <tr>
                <th>Id</th>
                <th>Email</th>
                <th>Name</th>
                <th>Admin</th>
                <th>Package</th>
                <th>Active</th>
                <th>Autologin token</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

		</div><!-- /.row -->

	</div><!-- /.container -->

	<!-- Modals -->
	<div class="modal fade deleteUserModal" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-dialog">

			<div class="modal-content">

				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
					<h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('modal_areyousure'); ?></h4>
				</div>

				<div class="modal-body">
					<p><?php echo $this->lang->line('users_modal_deleteuser_message'); ?></p>
				</div><!-- /.modal-body -->

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('cancel'); ?></button>
					<a href="" type="button" class="btn btn-primary" id="deleteUserButton"><span class="fui-check"></span> <?php echo $this->lang->line('imsure'); ?></a>
				</div>

			</div><!-- /.modal-content -->

		</div><!-- /.modal-dialog -->

	</div><!-- /.modal -->


	<div class="modal fade newUserModal" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<form class="form-horizontal" role="form" action="user/create">

			<div class="modal-dialog">

				<div class="modal-content">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
						<h4 class="modal-title" id="myModalLabel"><span class="fui-user"></span> <?php echo $this->lang->line('users_modal_newuser_heading'); ?></h4>
					</div>

					<div class="modal-body padding-top-40">

						<div class="loader" style="display: none;">
							<img src="<?php echo base_url(); ?>img/loading.gif" alt="Loading...">
							<?php echo $this->lang->line('users_modal_newuser_loadertext'); ?>
						</div>

						<div class="modal-alerts"></div>

						<div class="form-group">
							<label for="username" class="col-md-3 control-label"><?php echo $this->lang->line('users_modal_newuser_firstname'); ?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="first_name" placeholder="<?php echo $this->lang->line('users_modal_newuser_firstname'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="username" class="col-md-3 control-label"><?php echo $this->lang->line('users_modal_newuser_lastname'); ?>:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" name="last_name" placeholder="<?php echo $this->lang->line('users_modal_newuser_lastname'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="username" class="col-md-3 control-label"><?php echo $this->lang->line('users_modal_newuser_email'); ?>:</label>
							<div class="col-md-9">
								<input type="email" class="form-control" name="email" placeholder="<?php echo $this->lang->line('users_modal_newuser_email'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-3 control-label"><?php echo $this->lang->line('users_modal_newuser_password'); ?>:</label>
							<div class="col-md-9">
								<input type="password" class="form-control" name="password" placeholder="<?php echo $this->lang->line('users_modal_newuser_password'); ?>" value="">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-offset-3 col-md-9">
								<label class="checkbox" for="is_admin" style="padding-top: 0px;">
									<input type="checkbox" value="yes" name="is_admin" id="is_admin" data-toggle="checkbox">
									<?php echo $this->lang->line('users_modal_newuser_adminpermissions'); ?>
								</label>
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-md-3 control-label"><?php echo $this->lang->line('users_modal_newuser_package'); ?>:</label>
							<div class="col-md-9">
								<select name="package_id" id="package_id" class="form-control select select-inverse select-block mbl">
									<option value="" selected=""><?php echo $this->lang->line('users_choose_a_packge');?></option>
									<?php
									foreach ($packages as $package)
									{
										?>
										<option value="<?php echo $package['id']; ?>"><?php echo $package['name'] . ' - ' . $package['price'] . ' ' . $package['currency'] . ' / ' . $package['subscription'] . ' ' . $this->lang->line('account_label_package_months'); ?></option>
										<?php
									}
									?>
								</select>
							</div>
						</div>

					</div><!-- /.modal-body -->

					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('cancel'); ?></button>
						<button type="button" class="btn btn-primary" id="buttonCreateAccount"><span class="fui-check"></span> <?php echo $this->lang->line('users_modal_newuser_button_create'); ?></button>
					</div>

				</div><!-- /.modal-content -->

			</div><!-- /.modal-dialog -->

		</form>

	</div><!-- /.modal -->

	<?php $this->load->view("shared/modal_account.php"); ?>

	<!-- /modals -->

	<!-- Load JS here for greater good =============================-->
	<?php if (ENVIRONMENT == 'production') : ?>
		<script src="<?php echo base_url('build/users.bundle.js') . '?' . filemtime( FCPATH . 'build/users.bundle.js'); ?>"></script>
	<?php elseif (ENVIRONMENT == 'development') : ?>
		<script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/users.bundle.js"></script>
	<?php endif; ?>
    <!--[if lt IE 10]>
    <script>
    $(function(){
    	var msnry = new Masonry( '#users', {
	    	// options
	    	itemSelector: '.user',
	    	"gutter": 20
	    });

    })
    </script>
    <![endif]-->
</body>
</html>
