<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_update_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_update_sub_heading'), anchor('auth/payment_stripe/' . $user_id, $this->lang->line('email_update_link'))); ?></p>
	<p>
	Your user details is as follows,<br>
	User ID: <?php echo $this->input->post('email'); ?><br>
	Password: *****<br>
	</p>
</body>
</html>