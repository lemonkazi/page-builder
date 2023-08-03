<body>

    <?php $this->load->view("shared/nav.php"); ?>

    <div class="container-fluid upgradeStripe">

        <div class="row">

            <div class="col-md-4 col-md-offset-4">

                <h2 class="text-center">
                    <?php echo $this->lang->line('users_payment_paypal_heading'); ?>
                </h2>

                <p class="text-center">
                    <?php echo $this->lang->line('users_payment_paypal_message'); ?>
                </p>

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

                <form role="form" action="" method="post">

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="package_details" name="package_details" value="<?php echo $package['name'] . ' - ' . $package['price'] . ' ' . $package['currency'] . ' / ' . $package['subscription']; ?>" readonly >
                        <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>" >
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>" >
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-embossed"><?php echo $this->lang->line('pay_now_txt'); ?></span></button>

                </form>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.container -->

    <!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
        <script src="<?php echo base_url('build/users.bundle.js') . '?' . filemtime( FCPATH . 'build/users.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
        <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/users.bundle.js"></script>
    <?php endif; ?>

    <!--[if lt IE 10]>
    <script>
    $(function(){
    	var msnry = new Masonry( '#sites', {
	    	// options
	    	itemSelector: '.site',
	    	"gutter": 20
	    });

    })
    </script>
    <![endif]-->
</body>
</html>
