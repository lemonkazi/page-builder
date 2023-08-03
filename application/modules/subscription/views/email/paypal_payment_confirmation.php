<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_confirmation_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_confirmation_sub_heading'), anchor('auth', $this->lang->line('email_confirmation_link'))); ?></p>
	<p></p>
	<?php if($subscription):?>
	<p><?php echo $this->lang->line('paypal_payment_no_subscription');?><p/>
	<?php endif; ?>
</body>
</html>