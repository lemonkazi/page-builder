<form class="form-horizontal" role="form" action="packages/update/<?php echo $package['id']; ?>">

	<input type="hidden" name="id" value="<?php echo $package['id']; ?>">

	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $this->lang->line('package_details_form_input_name_placeholder'); ?>" value="<?php echo $package['name']; ?>">
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="sites_number" name="sites_number" placeholder="<?php echo $this->lang->line('package_details_form_input_sites_number_placeholder'); ?>" value="<?php echo $package['sites_number']; ?>" readonly>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="hosting_option" name="hosting_option" placeholder="<?php echo $this->lang->line('package_details_form_input_hosting_option_placeholder')?>" value="<?php echo $package['hosting_option']; ?>" readonly >
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="price" name="price" placeholder="<?php echo $this->lang->line('package_details_form_input_price_placeholder'); ?>" value="<?php echo $package['price']; ?>" readonly >
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="currency" name="currency" placeholder="<?php echo $this->lang->line('package_details_form_input_currency_placeholder'); ?>" value="<?php echo $package['currency']; ?>" readonly >
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<input type="text" class="form-control" id="subscription" name="subscription" placeholder="<?php echo $this->lang->line('package_details_form_input_subscription_placeholder'); ?>" value="<?php echo $package['subscription']; ?>" readonly >
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-12">
			<button type="button" class="btn btn-primary btn-embossed btn-block updatePackageButton" data-userid="<?php echo $package['id']; ?>"><span class="fui-check"></span> <?php echo $this->lang->line('package_details_form_button_udpate_package'); ?></button>
		</div>
	</div>
</form>