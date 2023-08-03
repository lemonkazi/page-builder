<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<base href="<?php echo base_url(); ?>">

	<title><?php if (isset($title)) { echo $title; } else { echo $this->lang->line('application_name_text'); } ?></title>

	<?php if ($page == 'site_builder') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/builder.css<?php echo '?' . filemtime( FCPATH . 'build/builder.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/builder.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'site') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/sites.css<?php echo '?' . filemtime( FCPATH . 'build/sites.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/sites.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'asset') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/images.css<?php echo '?' . filemtime( FCPATH . 'build/images.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/images.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'user') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/users.css<?php echo '?' . filemtime( FCPATH . 'build/users.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/users.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'packages') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/packages.css<?php echo '?' . filemtime( FCPATH . 'build/packages.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/packages.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'settings') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/settings.css<?php echo '?' . filemtime( FCPATH . 'build/settings.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/settings.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'elements_blocks') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/elements_blocks.css<?php echo '?' . filemtime( FCPATH . 'build/elements_blocks.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_blocks.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'elements_components') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/elements_components.css<?php echo '?' . filemtime( FCPATH . 'build/elements_components.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_components.css" rel="stylesheet">
		<?php endif; ?>

	<?php elseif ($page == 'elements_browser') : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/elements_browser.css<?php echo '?' . filemtime( FCPATH . 'build/elements_browser.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_browser.css" rel="stylesheet">
		<?php endif; ?>

	<?php else : ?>

		<?php if (ENVIRONMENT == 'production') : ?>
		<link href="<?php echo base_url(); ?>build/login.css<?php echo '?' . filemtime( FCPATH . 'build/login.css'); ?>" rel="stylesheet">
		<?php elseif (ENVIRONMENT == 'development') : ?>
		<link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/login.css" rel="stylesheet">
		<?php endif; ?>

	<?php endif; ?>

	<link rel="shortcut icon" href="<?php echo base_url('img/favicon.png'); ?>">

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
	<!--[if lt IE 9]>
	<script src="<?php echo base_url('assets/js/html5shiv.js'); ?>"></script>
	<script src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
	<![endif]-->
	<!--[if lt IE 10]>
	<link href="<?php echo base_url('assets/css/ie-masonry.css'); ?>" rel="stylesheet">
	<script src="<?php echo base_url('assets/js/masonry.pkgd.min.js'); ?>"></script>
	<![endif]-->

	<script>
		var baseUrl = '<?php echo base_url('/'); ?>';
		var siteUrl = '<?php echo site_url('/'); ?>';
	</script>

	<?php if ( isset($whitelabel_general['css']) ) {echo $whitelabel_general['css'];}?>

	<?php if( isset($whitelabel_general['custom_css']) && $whitelabel_general['custom_css'] != '' ):?>
	<style>
	<?php echo $whitelabel_general['custom_css'];?>
	</style>
	<?php endif;?>
</head>