<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editor</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta charset="utf-8">
    <?php if (ENVIRONMENT == 'production') : ?>
        <link href="<?php echo base_url(); ?>build/file_editor.css<?php echo '?' . filemtime( FCPATH . 'build/file_editor.css'); ?>" rel="stylesheet">
        <?php elseif (ENVIRONMENT == 'development') : ?>
        <link href="<?php echo $this->config->item('webpack_dev_url'); ?>build/file_editor.css" rel="stylesheet">
        <?php endif; ?>
    <style>
    #editor {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    </style>
    <script>
    var file = '<?php echo $file?>';
    var baseUrl = '<?php echo base_url('/'); ?>';
    var siteUrl = '<?php echo site_url('/'); ?>';
    </script>
</head>
<body>

    <div id="topbar">

        <div class="editing">
            <span class="fui-arrow-right"></span> <?php echo urldecode($file);?>
        </div>

        <button class="btn btn-danger btn-sm pull-right" data-toggle="confirmation" data-placement="bottom" data-title="<?php echo $this->lang->line('file_editor_confirmation_close_title');?>" data-content="<?php echo $this->lang->line('file_editor_confirmation_close_content');?>" data-btn-ok-label="<?php echo $this->lang->line('file_editor_confirmation_yes');?>" data-btn-cancel-label="<?php echo $this->lang->line('file_editor_confirmation_no');?>" data-popout="true" data-on-confirm="closeEditor" data-singleton="true">
            <span class="fui-cross-circle"></span> 
            <?php echo $this->lang->line('file_editor_button_label_close');?>
        </button>
        <button class="btn btn-primary btn-sm pull-right" id="buttonSaveFile" data-toggle="confirmation" data-placement="bottom" data-title="<?php echo $this->lang->line('file_editor_confirmation_close_title2');?>" data-content="<?php echo $this->lang->line('file_editor_confirmation_close_content2');?>" data-btn-ok-label="<?php echo $this->lang->line('file_editor_confirmation_yes');?>" data-btn-cancel-label="<?php echo $this->lang->line('file_editor_confirmation_no');?>" data-popout="true" data-on-confirm="updateFile" data-singleton="true">
            <span class="fui-check-circle"></span> 
            <?php echo $this->lang->line('file_editor_button_label_confirm');?>
        </button>
    </div>
    <div id="editor"></div>

	<!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/file_editor.bundle.js') . '?' . filemtime( FCPATH . 'build/file_editor.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/file_editor.bundle.js"></script>
    <?php endif; ?>

</body>