<?php $this->load->view("shared/header.php"); ?>

<body class="builderElementsBlocks">

	<?php $this->load->view("shared/nav.php"); ?>

	<div class="container-fluid">

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-list"></span> <?php echo $this->lang->line('builder_elements_blocks_heading'); ?></h1>

                <select class="form-control select select-default mbl select-sm" id="selectBlockCategory" data-with-search>
                    <option value="0"><?php echo $this->lang->line('builder_elements_showblocksincat'); ?></option>
                    <?php foreach ($blockCategories as $blockCategory) : ?>
                    <option value="<?php echo $blockCategory['blocks_categories_id']; ?>"><?php echo $blockCategory['category_name']; ?></option>
                    <?php endforeach; ?>
                </select>

                <span style="font-size: 20px; margin-left:20px; margin-right: 20px">||</span>

                <a href="#manageCategoriesModal" data-toggle="modal" class="js-manageCategories"><span class="fui-arrow-right"></span> <?php echo $this->lang->line('builder_elements_managecats'); ?></a>

            </div><!-- /.col -->

            <div class="col-md-3 col-sm-4 text-right">

				<a href="#addBlockModal" data-toggle="modal" class="btn btn-lg btn-primary btn-embossed btn-wide margin-top-40 add"><span class="fui-plus"></span> <?php echo $this->lang->line('builder_elements_button_addnew'); ?></a>

			</div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed margin-bottom-50">

        <div class="row">

            <div class="col-md-12">

                <div class="blockMasonry masonry-5" id="allBlocks"></div>

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

                    <table class="table table-bordered" id="tableBlockCategories">
						<thead>
							<tr>
								<th>Name</th>
								<th style="width: 80px">Actions</th>
							</tr>
						</thead>
                        <?php echo $this->load->view('builder_elements/blockstbody', $this->data); ?>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_cancelclose'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div id="divModals">

        <div class="modal fade manageBlockModal" id="manageBlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">

                <form id="formBlockDetails" method="post" action="<?php echo site_url('builder_elements/updateBlock'); ?>">

                    <input type="hidden" name="blockHeight" value="0">

                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                            <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('blocks_modal_block_title'); ?></h4>
                        </div>

                        <div class="modal-body">

                            <div id="divBlockLoading" style="text-align: center">
                                <img src="<?php echo base_url('img/loading.gif'); ?>">
                            </div>

                            <div class="editBlockDetails" id="divBlockModalBody">

                            </div>

                        </div><!-- /.modal-body -->

                        <div class="modal-footer">
                            <div class="deleteBlock pull-left">
                                <a href="#" class="deleteBlock" id="buttonDeleteBlock" style="display: inline;">
                                    <span class="fui-cross"></span>
                                    <?php echo $this->lang->line('blocks_modal_block_delete'); ?>
                                </a>
                                <div class="confirm" id="confirmDeleteBlock" style="display: none;">
                                    <b><?php echo $this->lang->line('blocks_modal_block_delete_confirm'); ?></b>
                                    <a href="" class="confirmYes" id="blockDeleteYes"><?php echo $this->lang->line('blocks_modal_block_delete_confirm_yes'); ?></a> / <a href="" class="confirmNo" id="blockDeleteNo"><?php echo $this->lang->line('blocks_modal_block_delete_confirm_no'); ?></a>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="buttonUpdateBlock" data-loading="<?php echo $this->lang->line('blocks_modal_block_saving'); ?>" data-calc-height="<?php echo $this->lang->line('blocks_modal_block_calc_height');?>" data-text="<?php echo $this->lang->line('blocks_modal_block_save'); ?>">
                                <span class="fui-check"></span>
                                <span class="tlabel"><?php echo $this->lang->line('blocks_modal_block_save'); ?></span>
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <span class="fui-cross"></span>
                                <?php echo $this->lang->line('blocks_modal_block_cancel'); ?>
                            </button>
                        </div>

                    </div><!-- /.modal-content -->

                </form>

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

        <div class="modal fade addBlockModal" id="addBlockModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

            <div class="modal-dialog">

                <form id="formAddBlock" method="post" action="<?php echo site_url('builder_elements/addBlock'); ?>">

                    <input type="hidden" name="blockHeight" value="0">

                    <div class="modal-content">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                            <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('blocks_modal_block_addtitle'); ?></h4>
                        </div>

                        <div class="modal-body">

                            <div id="divBlockLoading" style="text-align: center; display: none">
                                <img src="<?php echo base_url('img/loading.gif'); ?>">
                            </div>

                            <div class="addBlockDetails" id="divNewBlockModalBody">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group margin-bottom-0" id="divAddBlockCatSelectWrapper">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('partial_blockdetails_label_category'); ?></label>
                                        </div><!-- /.form-group -->
                                    </div><!-- /.row -->
                                    <div class="col-md-6 blockTemplate">
                                        <div class="form-group margin-bottom-0">
                                            <label><?php echo $this->lang->line('partial_blockdetails_label_template'); ?></label>
                                            <select name="blockUrl" placeholder="<?php echo $this->lang->line('builder_elements_block_url'); ?>" class="form-control select select-default select-block mbl select-sm selectTemplateFile" id="selectTemplateFile" data-with-search>
                                                <?php foreach ($templates as $template) : ?>
                                                <option value="<?php echo $template;?>"><?php echo $template; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div><!-- /.form-group -->
                                    </div><!-- /.row -->
                                </div><!-- /.row -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="checkbox" for="blockFullHeight">
                                            <input type="checkbox" value="check" name="blockFullHeight" id="blockFullHeight" data-toggle="checkbox">
                                            <?php echo $this->lang->line('partial_blockdetails_label_fullheight'); ?> <span class="label label-default heightHelp" data-toggle="tooltip" title="<?php echo $this->lang->line('partial_blockdetails_help_fullheight'); ?>">?</span>
                                        </label>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

                            </div>

                        </div><!-- /.modal-body -->

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="buttonCreateBlock" data-loading="<?php echo $this->lang->line('blocks_modal_block_saving'); ?>" data-calc-height="<?php echo $this->lang->line('blocks_modal_block_calc_height');?>" data-text="<?php echo $this->lang->line('blocks_modal_addblock_create'); ?>">
                                <span class="fui-check"></span>
                                <span class="tlabel"><?php echo $this->lang->line('blocks_modal_addblock_create'); ?></span>
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <span class="fui-cross"></span>
                                <?php echo $this->lang->line('blocks_modal_addblock_cancel'); ?>
                            </button>
                        </div>

                    </div><!-- /.modal-content -->

                </form>

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

    </div><!-- /#divModals -->

    <template id="templateBlock">
        <div class="block" data-block-id="">
            <div class="imageWrapper">
                <img>
            </div>
        </div>
    </template>

	<!-- Load JS here for greater good =============================-->
    <?php if (ENVIRONMENT == 'production') : ?>
    <script src="<?php echo base_url('build/elements_blocks.bundle.js') . '?' . filemtime( FCPATH . 'build/elements_blocks.bundle.js'); ?>"></script>
    <?php elseif (ENVIRONMENT == 'development') : ?>
    <script src="<?php echo $this->config->item('webpack_dev_url'); ?>build/elements_blocks.bundle.js"></script>
    <?php endif; ?>

</body>