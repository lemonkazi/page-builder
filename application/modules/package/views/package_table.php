<?php if (count($packages) > 0) : ?>
	<table class="table table-bordered table-hover packages">
		<thead>
			<tr>
				<th class="th_package"><?php echo $this->lang->line('package_table_package'); ?></th>
				<th class="th_price"><?php echo $this->lang->line('package_table_price'); ?></th>
				<th class="th_currency"><?php echo $this->lang->line('package_table_currency'); ?></th>
				<th class="th_interval"><?php echo $this->lang->line('package_table_interval'); ?></th>
				<th class="th_id"><?php echo $this->lang->line('package_table_id'); ?></th>
				<th class="th_status"><?php echo $this->lang->line('package_table_status'); ?></th>
				<th class="th_actions"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($packages as $package) : ?>
				<tr>
					<td><?php echo $package['name']; ?></td>
					<td><?php echo $package['price']; ?></td>
					<td><?php echo $package['currency']; ?></td>
					<td><?php echo $package['subscription']; ?></td>
					<td><?php echo $package['id']; ?></td>
					<td>
						<span class="<?php echo ($package['status'] == 'Active') ? "text-primary" : "text-danger"; ?>"><?php echo $package['status']; ?></span>
					</td>
					<td class="actions">
						<a href="#editPackageModal" data-toggle="modal" data-package-id="<?php echo $package['id']; ?>"><span class="fui-new"></span></a>
						<a href="packages/delete/<?php echo $package['id']; ?>" class="deletePackageButton"><span class="fui-cross-circle text-danger"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>