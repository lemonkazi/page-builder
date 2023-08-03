<form class="form-horizontal" role="form" action="user/update/<?php echo $user['userData']['id']; ?>">

	<input type="hidden" name="user_id" value="<?php echo $user['userData']['id']; ?>">

	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="email-<?php echo $user['userData']['id']; ?>" name="email" placeholder="<?php echo $this->lang->line('users_emailfield_placeholder'); ?>" value="<?php echo $user['userData']['email']; ?>">
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="password" class="form-control" id="password-<?php echo $user['userData']['id']; ?>" name="password" placeholder="<?php echo $this->lang->line('users_emailfield_password'); ?>" value="">
		</div>
	</div>
	<?php if ( $this->config->item('alogin_on') == 1 ):?>
	<div class="form-group">
		<div class="col-md-12">
			<div class="input-group">
				<input type="text" readonly class="form-control specialDisabled" id="atoken-<?php echo $user['userData']['id']; ?>" name="atoken" placeholder="<?php echo $this->lang->line('users_field_atoken'); ?>" value="<?php echo $user['userData']['auto_login_token']; ?>">
				<span class="input-group-btn">
					<button class="btn btn-default buttonRegenerateToken" data-target="#atoken-<?php echo $user['userData']['id']; ?>">
						<i class="fas fa-redo"></i>
						<?php echo $this->lang->line('users_field_atoken_regenerate');?>
					</button>
				</span>
			</div>
		</div>
	</div>
	<?php endif;?>
	<div class="form-group">
		<div class="col-md-12">
			<label class="checkbox" for="checkbox-admin-<?php echo $user['userData']['id']; ?>" style="padding-top: 0px;">
				<input type="checkbox" value="yes" <?php if ($user['is_admin'] == 'yes') : ?>checked<?php endif; ?> name="is_admin" data-toggle="checkbox" id="checkbox-admin-<?php echo $user['userData']['id']; ?>">
				<?php echo $this->lang->line('users_adminpermissions'); ?>
			</label>
		</div>
	</div>
	<div class="form-group margin-bottom-0">
		<div class="col-md-12">
			<select name="package_id" class="form-control select select-block mbl select-inverse">
				<option value="" selected=""><?php echo $this->lang->line('users_choose_a_packge'); ?></option>
				<?php
				foreach ($packages as $package)
				{
					?>
					<option value="<?php echo $package['id']; ?>" <?php if ($user['userData']['package_id'] == $package['id']) : ?>selected<?php endif; ?>><?php echo $package['name'] . ' - ' . $package['price'] . ' ' . $package['currency'] . ' / ' . $package['subscription'] . ' ' . $this->lang->line('account_label_package_months'); ?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<button type="button" class="btn btn-primary btn-embossed btn-block updateUserButton" data-userid="<?php echo $user['userData']['id']; ?>"><span class="fui-check"></span> <?php echo $this->lang->line('users_button_udpate'); ?></button>
		</div>
	</div>
</form>