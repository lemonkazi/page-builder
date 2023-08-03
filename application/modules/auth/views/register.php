<body class="login">
<?php echo $this->session->flashdata('randomString'); ?>
    <div class="container">

    	<div class="row">

    		<div class="col-md-4 col-md-offset-4">

    			<h2 class="text-center">
                    <?php echo $this->lang->line('register_heading'); ?>
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

                <form role="form" action="auth/register" method="post">

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="<?php echo $this->lang->line('register_input_first_name_placeholder'); ?>" value="<?php if ( $this->session->flashdata('formData') ) {echo $this->session->flashdata('formData')['first_name'];}?>">
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="<?php echo $this->lang->line('register_input_last_name_placeholder'); ?>" value="<?php if ( $this->session->flashdata('formData') ) {echo $this->session->flashdata('formData')['last_name'];}?>">
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $this->lang->line('register_input_email_placeholder'); ?>" value="<?php if ( $this->session->flashdata('formData') ) {echo $this->session->flashdata('formData')['email'];}?>">
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $this->lang->line('register_input_password_placeholder'); ?>" value="<?php if ( $this->session->flashdata('formData') ) {echo $this->session->flashdata('formData')['password'];}?>">
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="<?php echo $this->lang->line('register_input_password_confirm_placeholder'); ?>" value="<?php if ( $this->session->flashdata('formData') ) {echo $this->session->flashdata('formData')['password_confirm'];}?>">
                    </div>

                    <div class="input-group" style="width:100%">
                        <select name="package_id" id="package_id" class="form-control select select-block mbl">
                            <option value="" selected=""><?php echo $this->lang->line('register_select_package'); ?></option>
                            <?php
                            foreach ($packages as $package)
                            {
                                ?>
                                <option <?php if ( $this->session->flashdata('formData') && $this->session->flashdata('formData')['package_id'] == $package['id'] ) {echo "selected";}?> value="<?php echo $package['id']; ?>"><?php echo $package['name'] . ' - ' . $package['price'] . ' ' . $package['currency'] . ' / ' . $package['subscription']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <?php echo $captcha; ?>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="captcha" name="captcha" placeholder="<?php echo $this->lang->line('register_input_captcha_placeholder'); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-embossed"><?php echo $this->lang->line('register_create_account_button'); ?></span></button>

                    <hr class="dashed light">

                    <div class="text-center">
                        <a href="auth" style="font-size: 15px"><span class="fui-arrow-left"></span> <?php echo $this->lang->line('register_backlink'); ?></a>
                    </div>

                </form>

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