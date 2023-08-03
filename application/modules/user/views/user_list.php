<?php foreach ($users as $user) : ?>
	<div class="user" data-name="<?php echo $user['userData']['first_name']; ?> <?php echo $user['userData']['last_name']; ?>">

		<div class="topPart clearfix">
			<img src="<?php echo base_url(); ?>/img/dude.png" class="pic">
			<div class="details">
				<h4><?php echo $user['userData']['first_name']; ?> <?php echo $user['userData']['last_name']; ?></h4>
				<p>
					<span class="fui-mail"></span> <a href=""><?php echo $user['userData']['email']; ?></a>
				</p>
			</div><!-- /.details -->
		</div><!-- /.topPart -->

		<div class="bottom">
			<div class="loader" style="display: none;">
				<img src="<?php echo base_url(); ?>img/loading.gif" alt="Loading...">
			</div>
			<div class="alerts"></div>
			<ul class="nav nav-tabs nav-append-content">
				<li class="active"><a href="#<?php echo $user['userData']['id']; ?>_account"><span class="fui-user"></span> <?php echo $this->lang->line('users_tab_account'); ?></a></li>
			</ul><!-- /tabs -->
			<div class="tab-content clearfix">

				<div class="tab-pane active" id="<?php echo $user['userData']['id']; ?>_account">

					<?php $this->load->view('user/user_details_form', array('user'=>$user)); ?>

					<hr class="dashed">

					<div class="actions clearfix">
						<a href="#" class="btn btn-info btn-embossed btn-block passwordReset" data-userid="<?php echo $user['userData']['id']; ?>"><span class="fui-mail"></span> <?php echo $this->lang->line('users_button_sendpasswordreset'); ?></a>
						<div>
							<a href="user/delete/<?php echo $user['userData']['id']; ?>" class="btn btn-danger btn-embossed deleteUserButton"><span class="fui-cross-inverted"></span> <?php echo $this->lang->line('users_button_deleteaccount'); ?></a>
							<span>
								<?php if ($user['userData']['status'] == "Active") : ?>
									<a href="user/toggle_status/<?php echo $user['userData']['id']; ?>" class="btn btn-inverse btn-embossed" data-toggle="tooltip" title="Disable account"><span class="fui-power"></span></a>
								<?php else: ?>
									<a href="user/toggle_status/<?php echo $user['userData']['id']; ?>" class="btn btn-default btn-embossed" data-toggle="tooltip" title="Enable account"><span class="fui-power"></span></a>
								<?php endif; ?>
							</span>

						</div>
					</div><!-- /.actions -->

				</div><!-- /.tab-pane -->

			</div> <!-- /tab-content -->

		</div><!-- /.bottom -->

		<?php if ($user['userData']['status'] == "Inactive") : ?>
			<div class="ribbon-wrapper"><div class="ribbon">disabled</div></div>
		<?php endif; ?>

	</div><!-- /.user -->

<?php endforeach; ?>