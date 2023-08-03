<?php $this->load->view("shared/header.php");?>

<body>

    <?php $this->load->view("shared/nav.php");?>

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-9 col-sm-8">

                <h1><span class="fui-image"></span> <?php echo $this->lang->line('images_heading'); ?></h1>

            </div><!-- /.col -->

        </div><!-- /.row -->

        <hr class="dashed margin-bottom-50">

        <div class="row">

            <div class="col-md-12">

                <?php $this->load->view("shared/imageLibrary", array('userImages' => $userImages, 'adminImages' => $adminImages)); ?>

            </div><!-- /.col -->

        </div><!-- /row -->

    </div><!-- /.container -->

    <!-- modals -->

    <div class="modal fade viewPic" id="viewPic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-body">

                    <img src="" id="thePic">

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_close'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <div class="modal fade deleteImageModal" id="deleteImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $this->lang->line('modal_close'); ?></span></button>
                    <h4 class="modal-title" id="myModalLabel"><span class="fui-info"></span> <?php echo $this->lang->line('modal_areyousure'); ?></h4>
                </div>

                <div class="modal-body">

                    <p>
                        <?php echo $this->lang->line('modal_deleteimage_message'); ?>
                    </p>

                </div><!-- /.modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fui-cross"></span> <?php echo $this->lang->line('modal_deleteimage_button_cancel'); ?></button>
                    <button type="button" class="btn btn-primary" id="deleteImageButton"><span class="fui-check"></span> <?php echo $this->lang->line('modal_deleteimage_button_delete'); ?></button>
                </div>

            </div><!-- /.modal-content -->

        </div><!-- /.modal-dialog -->

    </div><!-- /.modal -->

    <?php $this->load->view("shared/modal_account"); ?>

    <!-- /modals -->

    <!-- Load JS here for greater good =============================-->
    <?php if( ENVIRONMENT == 'production' ):?>
    <script src="<?php echo base_url('build/images.bundle.js') . '?' . filemtime( FCPATH . 'build/images.bundle.js');?>"></script>
    <?php elseif( ENVIRONMENT == 'development' ):?>
    <script src="<?php echo $this->config->item('webpack_dev_url');?>build/images.bundle.js"></script>
    <?php endif;?>

    <!--[if lt IE 10]>
    <script>
    alert('')
    $(function(){
    	var msnry1 = new Masonry( '#myImages', {
	    	// options
	    	itemSelector: '.image',
	    	"gutter": 20
	    });
	    $('#ie_admintab').on('shown.bs.tab', function(e){

	    	var msnry2 = new Masonry( '#adminImages', {
	    	// options
	    	itemSelector: '.image',
	    	"gutter": 20
	    });

	    })

    })
    </script>
    <![endif]-->
</body>
</html>
