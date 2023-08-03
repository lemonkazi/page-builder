<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_login_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_login_sub_heading'), anchor('auth', $this->lang->line('email_login_link'))); ?></p>
	<p>
	Your user details is as follows,<br>
	User ID: <?php echo $this->input->post('email'); ?><br>
	Password: <?php echo $this->input->post('password'); ?><br>
	</p>
</body>
</html>