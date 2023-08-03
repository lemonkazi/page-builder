<?php foreach ($packages as $package) : ?>
	<div class="package" data-name="<?php echo $package['name']; ?>">

		<div class="topPart clearfix">
			<div class="details">
				<h4><?php echo $package['name']; ?></h4>
				<p><?php echo $this->lang->line('package_details_price_label'); ?><?php echo $package['price']; ?></p>
			</div><!-- /.details -->
		</div><!-- /.topPart -->

		<div class="bottom">
			<div class="loader" style="display: none;">
				<img src="<?php echo base_url(); ?>img/loading.gif" alt="<?php echo $this->lang->line('loading'); ?>">
			</div>
			<div class="alerts"></div>
			<div class="tab-content clearfix">
				<form class="form-horizontal" role="form" action="packages/update/<?php echo $package['id']; ?>">

					<input type="hidden" name="id" value="<?php echo $package['id']; ?>">

					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $this->lang->line('package_details_input_name_placeholder'); ?>" value="<?php echo $package['name']; ?>">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="sites_number" name="sites_number" placeholder="<?php echo $this->lang->line('package_details_input_sites_number_placeholder'); ?>" value="<?php echo $package['sites_number']; ?>" readonly >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="pages_number" name="pages_number" placeholder="<?php echo $this->lang->line('package_details_input_pages_number_placeholder'); ?>" value="<?php echo $package['pages_number']; ?>" readonly >
						</div>
					</div>					
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="hosting_option" name="hosting_option" placeholder="<?php echo $this->lang->line('package_details_input_hosting_option_placeholder')?>" value="<?php echo $package['hosting_option']; ?>" readonly >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="price" name="price" placeholder="<?php echo $this->lang->line('package_details_input_price_placeholder'); ?>" value="<?php echo $package['price']; ?>" readonly >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="currency" name="currency" placeholder="<?php echo $this->lang->line('package_details_input_currency_placeholder'); ?>" value="<?php echo $package['currency']; ?>" readonly >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="text" class="form-control" id="subscription" name="subscription" placeholder="<?php echo $this->lang->line('package_details_input_subscription_placeholder'); ?>" value="<?php echo $package['subscription']; ?>" readonly >
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary btn-embossed btn-block updatePackageButton" data-userid="<?php echo $package['id']; ?>"><span class="fui-check"></span> <?php echo $this->lang->line('package_details_button_udpate_package'); ?></button>
						</div>
					</div>
				</form>

				<hr class="dashed">

				<div>
					<a href="packages/delete/<?php echo $package['id']; ?>" class="btn btn-danger btn-embossed deletePackageButton"><span class="fui-cross-inverted"></span> <?php echo $this->lang->line('package_details_button_delete_package'); ?></a>
					<span>
						<?php if ($package['status'] == "Active") : ?>
							<a href="packages/toggle_status/<?php echo $package['id']; ?>" class="btn btn-default btn-embossed"><span class="fui-power"></span> <?php echo $this->lang->line('package_details_button_disable_package'); ?></a>
						<?php else : ?>
							<a href="packages/toggle_status/<?php echo $package['id']; ?>" class="btn btn-inverse btn-embossed"><span class="fui-power"></span> <?php echo $this->lang->line('package_details_button_enable_package'); ?></a>
						<?php endif; ?>
					</span>
				</div>

			</div> <!-- /tab-content -->

		</div><!-- /.bottom -->

		<?php if ($package['status'] == "Inactive") : ?>
			<div class="ribbon-wrapper"><div class="ribbon"><?php echo $this->lang->line('package_details_ribbon_label'); ?></div></div>
		<?php endif; ?>

	</div><!-- /.user -->

<?php endforeach; ?>