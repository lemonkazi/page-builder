<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_update_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_update_sub_heading'), anchor('subscription/paypal/' . $user_id, $this->lang->line('email_update_link'))); ?></p>
</body>
</html>