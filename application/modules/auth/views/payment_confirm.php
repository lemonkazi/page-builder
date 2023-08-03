<body class="login">

	<div class="container">

		<div class="row">

			<div class="col-md-4 col-md-offset-4">

				<div class="logo">
					<?php echo $this->lang->line('payment_stripe_heading'); ?>
				</div>

				<?php if ($this->session->flashdata('success')) : ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close fui-cross" type="button"></button>
                        <strong><?php echo $this->lang->line('flashdata_success'); ?></strong> <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close fui-cross" type="button"></button>
                        <strong><?php echo $this->lang->line('flashdata_error'); ?></strong> <?php echo $this->session->flashdata('error'); ?>
                    </div>
                <?php endif; ?>

				<hr class="dashed light">

				<div class="text-center">
					<a href="auth" style="font-size: 15px"><span class="fui-arrow-left"></span> <?php echo $this->lang->line('payment_confirm_backlink'); ?></a>
				</div>

			</div><!-- /.col -->

		</div><!-- /.row -->

	</div><!-- /.container -->

	<!-- Load JS here for greater good =============================-->
	<script src="<?php echo base_url('build/login.bundle.js') . '?' . filemtime( FCPATH . 'build/login.bundle.js'); ?>"></script>
</body>
</html>