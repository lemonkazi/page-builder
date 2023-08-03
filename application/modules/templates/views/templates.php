<body class="sites">

    <?php $this->load->view("shared/nav.php"); ?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-window"></span> <?php echo $this->lang->line('templates_header'); ?></h1>

                <select class="form-control select select-default mbl select-sm" id="selectBlockCategory">
                    <option value="0"><?php echo $this->lang->line('templates_showblocksincat'); ?></option>
                    <?php foreach($categories as $category):?>
                    <option value="<?php echo $category['templates_categories_id'];?>"><?php echo $category['category_name'];?></option>
                    <?php endforeach;?>
                </select>

                <span style="font-size: 20px; margin-left:20px; margin-right: 20px">||</span>

                <a href="#manageCategoriesModal" data-toggle="modal" class="js-manageCategories"><span class="fui-arrow-right"></span> <?php echo $this->lang->line('templates_managecats'); ?></a>

            </div><!-- /.col -->

            <div class="col-md-3 col-sm-4 text-right">

                <a href="templates/createTemplate" class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-30 add"><span class="fui-plus"></span> <?php echo $this->lang->line('templates_createnewsite'); ?></a>

            </div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed">

        <div class="row">

            <?php if (isset($templates) && count($templates) > 0) : ?>

                <div class="col-md-12">

                    <div class="sites" id="sites">

                        <?php echo $this->load->view('templates/partial_templates', $templates); ?>

                    </div><!-- /.masonry -->

                </div><!-- /.col -->

            <?php else : ?>

                <div class="col-md-6 col-md-offset-3">

                    <div class="alert alert-info" style="margin-top: 30px">
                        <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                        <h2><?php echo $this->lang->line('templates_nosites_heading'); ?></h2>
                        <p>
                            <?php echo $this->lang->line('templates_nosites_message'); ?>
                        </p>
                        <br><br>
                        <a href="templates/createTemplate" class="btn btn-primary btn-lg btn-wide">
                            <?php echo $this->lang->line('templates_nosites_button_confirm'); ?>
                        </a>
                        <a href="#" class="btn btn-default btn-lg btn-wide" data-dismiss="alert">
                            <?php echo $this->lang->line('templates_nosites_button_cancel'); ?>
                        </a>
                    </div>

                </div><!-- ./col -->

            <?php endif; ?>

        </div><!-- /.row -->

    </div><!-- /.container -->

    <div class="modal fade manageCategoriesModal" id="manageCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('templates_modal_categories_title'); ?></h4>
                </div>

                <div class="modal-body">

                    <table class="table table-bordered" id="tableTemplateCategories">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width: 80px">Actions</th>
                            </tr>
                        </thead>
                        <?php echo $this->load->view('templates/catstbody', $categories); ?>
                        <tfoot>
                            <tr class="rowAddCategory">
                                <td colspan="2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="inputNewCategory" id="inputNewCategory" placeholder="<?php echo $this->lang->line('templates_modal_categories_newcat_placeholder'); ?>" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" id="buttonAddNewCategory" disabled data-loading="<?php echo $this->lang->line('templates_modal_categories_newcat_adding'); ?>" data-text="<?php echo $this->lang->line('templates_modal_categories_newcat_add'); ?>"><?php echo $this->lang->line('templates_modal_categories_newcat_add'); ?></button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/templates.bundle.js') . '?' . filemtime( FCPATH . 'build/templates.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/templates.bundle.js"></script>
    <?php endif; ?>

    <!--[if lt IE 10]>
    <script>
    $(function(){
    	var msnry = new Masonry( '#sites', {
	    	// options
	    	itemSelector: '.site',
	    	"gutter": 20
	    });

    })
    </script>
    <![endif]-->
</body>
</html>
