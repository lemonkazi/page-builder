<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_payment_failed_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_payment_failed_sub_heading'), $amount, anchor('auth/payment_card_update/' . $user_id , $this->lang->line('email_payment_failed_link'))); ?></p>
	<p></p>
</body>
</html>