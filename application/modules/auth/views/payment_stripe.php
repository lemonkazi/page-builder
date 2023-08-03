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

                <form role="form" action="auth/payment_stripe" method="post">

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="package_details" name="package_details" value="<?php echo $user['package_name'] . ' - ' . $user['package_price'] . ' ' . $user['package_currency'] . ' / ' . $user['package_subscription']; ?>" readonly>
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="<?php echo $this->lang->line('payment_stripe_input_card_number_placeholder'); ?>" value="">
                    </div>

                    <div class="input-group" style="width:100%">
                        <select name="card_month" id="card_month" class="form-control select select-block mbl">
                            <option value="" selected=""><?php echo $this->lang->line('payment_stripe_select_card_month_placeholder')?></option>
                            <?php
                            for ($i=1; $i < 13; $i++)
                            {
                                ?>
                                <option value="<?php printf("%02d", $i); ?>"><?php printf("%02d", $i); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group" style="width:100%">
                        <select name="card_year" id="card_year" class="form-control select select-block mbl">
                            <option value="" selected=""><?php echo $this->lang->line('payment_stripe_select_card_year_placeholder'); ?></option>
                            <?php
                            $year = date('Y');
                            $till = $year + 10;

                            for ($i = $year; $i < $till; $i++)
                            {
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn"><span class="fui-arrow-right"></span></button>
                        </span>
                        <input type="text" class="form-control" id="card_cvc" name="card_cvc" placeholder="<?php echo $this->lang->line('payment_stripe_input_card_cvc_placeholder'); ?>" value="">
                    </div>

                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="cust_id" value="<?php echo $user['stripe_cus_id']; ?>">
                    <input type="hidden" name="plan" value="<?php echo $user['package_stripe_id']; ?>">
                    <input type="hidden" name="name" value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>">
                    <input type="hidden" name="metadata" value="<?php echo $user['package_subscription']; ?>">

                    <button type="submit" class="btn btn-primary btn-block btn-embossed"><?php echo $this->lang->line('payment_stripe_button'); ?></span></button>

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