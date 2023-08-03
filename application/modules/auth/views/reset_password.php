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

    			<p><?php echo $this->lang->line('reset_password_subheading'); ?></p>

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

    			<form role="form" action="auth/reset_password" method="post">

    				<div class="input-group">
    					<span class="input-group-btn">
    						 <button class="btn"><span class="fui-user"></span></button>
    					</span>
    			    	<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('reset_password_input_password_placeholder')?>">
   					</div>

   					<div class="input-group">
    					<span class="input-group-btn">
    						 <button class="btn"><span class="fui-user"></span></button>
    					</span>
    			    	<input type="password" class="form-control" id="re_password" name="re_password" placeholder="<?php echo $this->lang->line('reset_password_input_re_password_placeholder')?>">
   					</div>

    			  	<button type="submit" class="btn btn-primary btn-block"><?php echo $this->lang->line('forgot_password_submit_btn')?> <span class="fui-triangle-right-large"></span></button>

                    <hr class="dashed light">
					<input type="hidden" name="email" value="<?php echo $email; ?>">
					<input type="hidden" name="forgot_code" value="<?php echo $forgot_code; ?>">
                    <div class="text-center">
                        <a href="auth" style="font-size: 15px"><span class="fui-arrow-left"></span> <?php echo $this->lang->line('forgot_backlink')?></a>
                    </div>

    			</form>

    		</div><!-- /.col -->

    	</div><!-- /.row -->

    </div><!-- /.container -->

    <!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/builder.bundle.js') . '?' . filemtime( FCPATH . 'build/builder.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/builder.bundle.js"></script>
    <?php endif; ?>
  </body>
</html>
