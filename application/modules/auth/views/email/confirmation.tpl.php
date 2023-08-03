<html>
<body>
	<h1><?php echo sprintf($this->lang->line('email_confirmation_heading'), $name); ?></h1>
	<p><?php echo sprintf($this->lang->line('email_confirmation_sub_heading'), anchor('auth', $this->lang->line('email_confirmation_link'))); ?></p>
	<p></p>
</body>
</html>