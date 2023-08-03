<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->lang->line('alert_page_title');?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="<?php echo $this->config->item('base_url');?>bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="<?php echo base_url(); ?>build/sent.css<?php echo '?' . filemtime( FCPATH . 'build/sent.css'); ?>" rel="stylesheet">

</head>
<body>

    <div class="container">

    	<div class="row">

    		<br>

    		<div class="col-md-offset-3 col-md-6">

    			<?php
    				if ($data['alert_type'] == 'error')
                    {
    					$this->load->view("shared/error", $data);
    				}
                    else if ($data['alert_type'] == 'info')
                    {
    					$this->load->view("shared/info", $data);
    				}
                    else if ($data['alert_type'] == 'success')
                    {
    					$this->load->view("shared/success", $data);
    				}
    			?>

    			<p class="small text-center"><?php echo $this->lang->line('application_link'); ?></p>

    		</div><!-- /.col-md-12 -->
    	</div>
    </div>
    <!-- /.container -->

  </body>
</html>
