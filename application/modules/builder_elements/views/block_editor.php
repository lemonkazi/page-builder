<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editor</title>
    <?php if (ENVIRONMENT == 'production') : ?>
        <link href="<?php echo base_url(); ?>build/block_editor.css<?php echo '?' . filemtime( FCPATH . 'build/block_editor.css'); ?>" rel="stylesheet">
        <?php elseif (ENVIRONMENT == 'development') : ?>
        <link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/block_editor.css" rel="stylesheet">
        <?php endif; ?>
    <style>
    #editor {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    </style>
    <script>
    var file = '/<?php echo $file?>';
    </script>
</head>
<body>

    <div id="topbar">
        <button class="btn btn-danger btn-sm pull-right" data-toggle="confirmation" data-placement="bottom" data-title="Close editor?" data-content="Your changes will NOT be saved" data-btn-ok-label="Yes" data-btn-cancel-label="No" data-popout="true" data-on-confirm="closeEditor"><span class="fui-cross-circle"></span> Close editor</button>
        <button class="btn btn-primary btn-sm pull-right"><span class="fui-check-circle"></span> Save markup</button>
    </div>
    <div id="editor"></div>

	<!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/block_editor.bundle.js') . '?' . filemtime( FCPATH . 'build/block_editor.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/block_editor.bundle.js"></script>
    <?php endif; ?>

</body>