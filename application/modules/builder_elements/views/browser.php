<?php $this->load->view("shared/header.php"); ?>

<body class="filemanager">

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-list"></span> <?php echo $this->lang->line('builder_elements_browser_heading'); ?></h1>

                <div class="breadcrumbs"><b><?php echo $this->lang->line('builder_elements_browser_label_browsing');?>:</b> /<span id="spanPath"></span></div>

            </div><!-- /.col -->

            <div class="col-md-3 col-sm-4 text-right">


			</div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed margin-bottom-50">

    </div><!-- /.container -->

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-6">

                <div class="search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="<?php echo $this->lang->line('builder_elements_browser_placeholder_search');?>" id="inputSearchSites">
                        <span class="input-group-btn">
                            <button type="submit" class="btn" id="buttonSearchSites"><span class="fui-search"></span></button>
                        </span>
                    </div>
                </div>

            </div><!-- /.col -->

            <div class="col-md-6">

                <form action="<?php echo site_url('builder_elements/upload');?>" id="formUploadFile">

                    <input type="hidden" value="" id="inputPath" name="inputPath">

                    <div class="pull-left" style="width: 77%">

                        <div class="form-group">
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="input-group">
                                    <div class="form-control uneditable-input" data-trigger="fileinput">
                                        <span class="fui-clip fileinput-exists"></span>
                                        <span class="fileinput-filename"><span style="color: #A9B4BE"><?php echo $this->lang->line('builder_elements_browser_placeholder_choosefile');?></span></span>
                                    </div>
                                    <span class="input-group-btn btn-file">
                                        <span class="btn btn-default fileinput-new" data-role="select-file"><?php echo $this->lang->line('builder_elements_browser_button_selectfile');?></span>
                                        <span class="btn btn-default fileinput-exists" data-role="change">
                                            <span class="fui-gear"></span>  <?php echo $this->lang->line('builder_elements_browser_button_change');?>
                                        </span>
                                        <input type="file" name="inputBrowserFile" id="inputBrowserFile">
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                            <span class="fui-trash"></span>  <?php echo $this->lang->line('builder_elements_browser_button_remove');?>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div><!-- /.col -->

                    <div class="pull-right" style="width: 22%">

                        <button type="submit" class="btn btn-primary btn-block disabled" id="buttonFileUpload"><span class="fui-upload"></span> <?php echo $this->lang->line('builder_elements_browser_button_upload');?></button>

                    </div>

                </form>

            </div><!-- /.col -->

        </div><!-- /.row -->

        <div class="row">

            <div class="col-md-12">

                <ul class="data" id="data"></ul>

                <div class="nothingfound">
                    <div class="nofiles"></div>
                    <span><?php echo $this->lang->line('builder_elements_browser_empty');?></span>
                </div>

            </div><!-- /.col -->

        </div><!-- /.row -->

    </div><!-- /.container -->

    <template id="additionalItems">
        <li class="add">
            <form id="formAddFolder" class="formAddFolder" data-warning="<?php echo $this->lang->line('builder_elements_browser_warning_invalidcharacter');?>" action="<?php echo site_url('builder_elements/addFolder');?>">
                <input type="text" placeholder="<?php echo $this->lang->line('builder_elements_browser_placeholder_addfolder');?>" class="form-control input-sm" name="inputNewFolder" id="inputNewFolder" data-warning="<?php echo $this->lang->line('builder_elements_browser_warning_invalidcharacter');?>"><button class="btn btn-sm btn-primary btn-block disabled" id="buttonAddFolder" type="submit"><?php echo $this->lang->line('builder_elements_browser_button');?></button>
            </form>
        </li>
        <li></li>
        <li></li>
        <li></li>
    </template>


	<!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/elements_browser.bundle.js') . '?' . filemtime( FCPATH . 'build/elements_browser.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_browser.bundle.js"></script>
    <?php endif; ?>

</body>