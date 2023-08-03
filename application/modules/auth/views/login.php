<body class="login">

	<div class="container">

		<div class="row">

			<div class="col-md-4 col-md-offset-4">

				<div class="logo">

					<?php if( isset($whitelabel_general['logo_image']) && $whitelabel_general['logo_image'] != '' ):?>
						<img src="<?php echo base_url('images/uploads/' . $whitelabel_general['logo_image']);?>">
					<?php elseif ( isset($whitelabel_general['logo_text']) && $whitelabel_general['logo_text'] != '' ):?>
						<?php echo $whitelabel_general['logo_text'];?>
					<?php else:?>
						<img src="<?php echo base_url('img/logo.svg'); ?>" alt="<?php echo $this->lang->line('application_name_text'); ?>">
					<?php endif;?>
					
				</div>

				<?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close fui-cross" type="button"></button>
                        <strong><?php echo $this->lang->line('flashdata_success'); ?></strong> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <button data-dismiss="alert" class="close fui-cross" type="button"></button>
                        <strong><?php echo $this->lang->line('flashdata_error'); ?></strong> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

				<form role="form" action="auth<?php if (isset($_GET['url']) && $_GET['url'] != '') {echo "/?url=" . urlencode($_GET['url']);}?>" method="post">

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-user"></span></button>
						</span>
						<input type="email" class="form-control" id="email" name="email" tabindex="1" autofocus placeholder="<?php echo $this->lang->line('login_input_email_placeholder'); ?>">
					</div>

					<div class="input-group">
						<span class="input-group-btn">
							<button class="btn"><span class="fui-lock"></span></button>
						</span>
						<input type="password" class="form-control" id="password" name="password" tabindex="2" placeholder="<?php echo $this->lang->line('login_input_password_placeholder'); ?>">
					</div>

					<label class="checkbox margin-bottom-20" for="remember">
						<input type="checkbox" value="1" id="remember" name="remember" tabindex="3" data-toggle="checkbox">
						<?php echo $this->lang->line('login_remember_me'); ?>
					</label>

					<button type="submit" class="btn btn-primary btn-block btn-embossed" tabindex="4"><?php echo $this->lang->line('login_button_login'); ?><span class="fui-arrow-right"></span></button>

					<div class="row">
						<div class="col-md-12 text-center">
							<a href="auth/forgot"><?php echo $this->lang->line('login_lost_password'); ?></a>
						</div>
					</div><!-- /.row -->

				</form>

				<div class="divider">
					<span><?php echo $this->lang->line('OR'); ?></span>
				</div>

				<h2 class="text-center margin-bottom-25">
					<?php echo $this->lang->line('login_signup_heading'); ?>
				</h2>

				<a href="auth/register" class="btn btn-block btn-inverse btn-embossed"><?php echo $this->lang->line('login_button_signup'); ?> <span class="fui-new"></span></a>

			</div><!-- /.col -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<!-- Load JS here for greater good =============================-->
	<?php if (ENVIRONMENT == 'production') : ?>
	<script src="<?php echo base_url('build/login.bundle.js') . '?' . filemtime( FCPATH . 'build/login.bundle.js'); ?>"></script>
	<?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/login.bundle.js"></script>
    <?php endif; ?>
</body>
</html>