<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $this->lang->line('alert_page_title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Flat UI -->
    <link href="<?php echo base_url(); ?>build/sent.css<?php echo '?' . filemtime( FCPATH . 'build/sent.css'); ?>" rel="stylesheet">
    <link rel="shortcut icon" href="<?php echo base_url('img/favicon.png'); ?>">
</head>
<body>
    <div class="container">
    	<div class="row">
    		<br>
    		<div class="col-md-offset-3 col-md-6">

    			<div class="alert alert-info">
                    <h4 class="text-center"><?php echo $data['header']; ?></h4>
                    <div id="content">
                        <p><?php echo $data['content']; ?></p>
                    </div>
                    <p class="text-center"><?php echo sprintf($this->lang->line('autoupdate_site_link'), site_url('sites')); ?></p>
                </div>

    			<p class="small text-center"><?php echo $this->lang->line('application_link'); ?></p>

    		</div><!-- /.col-md-12 -->
    	</div>
    </div>
    <!-- /.container -->
</body>
</html>
