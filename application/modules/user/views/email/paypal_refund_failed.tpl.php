<html>
<body>
	<h1><?php echo $this->lang->line('email_update_heading'); ?></h1>
	<p></p>
	<p></p>
	<p><?php echo $this->lang->line('refund_failed_message'); ?></p>
	<p></p>
	<p><?php echo $this->lang->line('refund_failed_user_id'); ?>: <?php echo $user_id;?></p>
	<p><?php echo $this->lang->line('refund_failed_name'); ?>: <?php echo $name;?></p>
	<p></p>
	<p><?php echo $this->lang->line('refund_failed_trnsctn'); ?>: <?php echo $transactionid;?></p>
	<p><?php echo $this->lang->line('refund_failed_amount'); ?>: <?php echo $amount;?></p>
</body>
</html>