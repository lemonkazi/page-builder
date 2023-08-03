<?php $this->load->view("shared/header.php"); ?>

<body class="builderElementsComponents">

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-list"></span> <?php echo $this->lang->line('builder_elements_components_heading'); ?></h1>

                <select class="form-control select select-default mbl select-sm" id="selectComponentCategory" data-with-search>
                    <option value="0"><?php echo $this->lang->line('builder_elements_showblocksincat'); ?></option>
                    <?php foreach ($componentsCategories as $componentsCategory) : ?>
                    <option value="<?php echo $componentsCategory['components_categories_id']; ?>"><?php echo $componentsCategory['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <span style="font-size: 20px; margin-left:20px; margin-right: 20px">||</span>

                <a href="#manageCategoriesModal" data-toggle="modal" class="js-manageCategories"><span class="fui-arrow-right"></span> <?php echo $this->lang->line('builder_elements_managecomponentcats'); ?></a>

            </div><!-- /.col -->

            <div class="col-md-3 col-sm-4 text-right">

				<a href="#addComponentModal" data-toggle="modal" class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-40 add"><span class="fui-plus"></span> <?php echo $this->lang->line('builder_elements_button_addnewcomponent'); ?></a>

			</div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed margin-bottom-50">

        <div class="row">

            <div class="col-md-12">

                <div class="blockMasonry masonry-5" id="allComponents"></div>

            </div><!-- /.col -->

        </div><!-- /row -->

    </div><!-- /.container -->

    <div class="modal fade manageCategoriesModal" id="manageCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('blocks_modal_categories_title'); ?></h4>
                </div>

                <div class="modal-body">

                    <table class="table table-bordered" id="tableComponentCategories">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="width: 80px">Actions</th>
                            </tr>
                        </thead>
                        <?php echo $this->load->view('builder_elements/componentstbody.php', $this->data); ?>
                        <tfoot>
                            <tr class="rowAddCategory">
                                <td colspan="2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="inputNewCategory" id="inputNewCategory" placeholder="<?php echo $this->lang->line('blocks_modal_categories_newcat_placeholder'); ?>" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-primary" id="buttonAddNewCategory" disabled data-loading="<?php echo $this->lang->line('blocks_modal_categories_newcat_adding'); ?>" data-text="<?php echo $this->lang->line('blocks_modal_categories_newcat_add'); ?>"><?php echo $this->lang->line('blocks_modal_categories_newcat_add'); ?></button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_deleteimage_button_cancel'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div class="modal fade manageComponentModal" id="manageComponentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <form id="formComponentDetails" method="post" action="<?php echo site_url('builder_elements/updateComponent'); ?>">

                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('components_modal_componen_title'); ?></h4>
                    </div>

                    <div class="modal-body">

                        <div id="divComponentLoading" style="text-align: center">
                            <img src="<?php echo base_url('img/loading.gif'); ?>">
                        </div>

                        <div class="editComponentDetails" id="divComponentModalBody">

                        </div>

                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <div class="deleteBlock pull-left">
                            <a href="#" class="deleteComponent" id="buttonDeleteComponent" style="display: inline;">
                                <span class="fui-cross"></span>
                                <?php echo $this->lang->line('components_modal_componen_delete'); ?>
                            </a>
                            <div class="confirm" id="confirmDeleteComponent" style="display: none;">
                                <b><?php echo $this->lang->line('components_modal_componen_delete_confirm'); ?></b>
                                <a href="" class="confirmYes" id="componentDeleteYes"><?php echo $this->lang->line('components_modal_componen_delete_confirm_yes'); ?></a> / <a href="" class="confirmNo" id="componentDeleteNo"><?php echo $this->lang->line('components_modal_componen_delete_confirm_no'); ?></a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" id="buttonUpdateComponent" data-loading="<?php echo $this->lang->line('components_modal_component_saving'); ?>" data-text="<?php echo $this->lang->line('components_modal_components_save'); ?>">
                            <span class="fui-check"></span>
                            <?php echo $this->lang->line('components_modal_components_save'); ?>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <span class="fui-cross"></span>
                            <?php echo $this->lang->line('components_modal_component_cancel'); ?>
                        </button>
                    </div>

                </div><!-- /.modal-content -->

            </form>

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div class="modal fade addComponentModal" id="addComponentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <form id="formAddComponent" method="post" action="<?php echo site_url('builder_elements/addComponent'); ?>">

                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                        <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('components_modal_component_addtitle'); ?></h4>
                    </div>

                    <div class="modal-body">

                        <div id="divComponentLoading" style="text-align: center; display: none">
                            <img src="<?php echo base_url('img/loading.gif'); ?>">
                        </div>

                        <div class="addComponentDetails" id="divNewComponentModalBody">

                            <div class="row">
                                <div class="col-md-12">
                                    <label><?php echo $this->lang->line('partial_componentdetails_label_thumbnail'); ?></label>
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="input-group">
                                                <div class="form-control uneditable-input" data-trigger="fileinput">
                                                    <span class="fui-clip fileinput-exists"></span>
                                                    <span class="fileinput-filename"></span>
                                                </div>
                                                <span class="input-group-btn btn-file">
                                                    <span class="btn btn-default fileinput-new" data-role="select-file"><?php echo $this->lang->line('partial_componentdetails_fileupload_select'); ?></span>
                                                    <span class="btn btn-default fileinput-exists" data-role="change">
                                                        <span class="fui-gear"></span>
                                                        <?php echo $this->lang->line('partial_componentdetails_fileupload_change'); ?>
                                                    </span>
                                                    <input type="file" name="componentThumbnail">
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                                        <span class="fui-trash"></span>
                                                        <?php echo $this->lang->line('partial_componentdetails_fileupload_remove'); ?>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group margin-bottom-0">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('partial_componentdetails_label_category'); ?></label>
                                        <select class="form-control select select-default select-block select-sm mbl" name="componentCategory">
                                            <?php foreach ($componentsCategories as $componentCategory) : ?>
                                            <option value="<?php echo $componentCategory['components_categories_id']; ?>"><?php echo $componentCategory['category_name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div><!-- /.form-group -->
                                </div><!-- /.col -->
                            </div><!-- /.row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group margin-bottom-0">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('partial_componentdetails_label_html'); ?></label>
                                        <textarea class="form-control" name="componentMarkup" rows="5"></textarea>
                                    </div>
                                </div><!-- /.col -->
                            </div><!-- /.row -->

                        </div>

                    </div><!-- /.modal-body -->

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="buttonCreateComponent" data-loading="<?php echo $this->lang->line('components_modal_component_saving'); ?>" data-text="<?php echo $this->lang->line('blocks_modal_addblock_create'); ?>">
                            <span class="fui-check"></span>
                            <?php echo $this->lang->line('comopnents_modal_addcomponent_create'); ?>
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <span class="fui-cross"></span>
                            <?php echo $this->lang->line('components_modal_addcomponent_cancel'); ?>
                        </button>
                    </div>

                </div><!-- /.modal-content -->

            </form>

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <template id="templateComponent">
        <div class="component" data-block-id="">
            <div class="imageWrapper">
                <img>
            </div>
        </div>
    </template>

	<!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/elements_components.bundle.js') . '?' . filemtime( FCPATH . 'build/elements_components.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_components.bundle.js"></script>
    <?php endif; ?>

</body>