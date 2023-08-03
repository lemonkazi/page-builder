<body class="login">

    <div class="container">

    	<div class="row">

    		<div class="col-md-4 col-md-offset-4">

    			<h2 class="text-center">
                    <?php echo $this->lang->line('payment_stripe_heading'); ?>
                </h2>

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
                        <input type="text" class="form-control" id="package_details" name="package_details" value="<?php echo $user['package_name'] . ' - ' . $user['package_price'] . ' ' . $user['package_currency'] . ' / ' . $user['package_subscription']; ?>" readonly>
                    </div>

                   
                    <button type="submit" class="btn btn-primary btn-block btn-embossed"><?php echo $this->lang->line('pay_now_txt'); ?></span></button>

                    <hr class="dashed light">

                    <div class="text-center">
                        <a href="auth" style="font-size: 15px"><span class="fui-arrow-left"></span> <?php echo $this->lang->line('payment_stripe_backlink'); ?></a>
                    </div>

                </form>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.container -->


    <!-- Load JS here for greater good =============================-->
    <script src="<?php echo base_url('build/register.bundle.js') . '?' . filemtime( FCPATH . 'build/register.bundle.js'); ?>"></script>
</body>
</html>