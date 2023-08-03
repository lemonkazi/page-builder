<ul class="nav nav-tabs nav-append-content">
    <li class="active"><a href="/asset/images#myImagesTab" id="anchorMyImagesTab">
        <?php
            $size_bytes = get_dir_size('./images/uploads/' . $this->session->userdata('user_id'));
            $size_mb = round(($size_bytes/1000)/1000, 2);
            $package = $this->MPackages->get_by_id($this->session->userdata('package_id'));
            if ($this->session->userdata('user_type') != 'Admin')
            {
                $disk_space = $package['disk_space'];
            }
            else
            {
                $disk_space = '&infin;';
            }
        ?>
        <?php echo sprintf($this->lang->line('modal_imagelibrary_tab_myimages'), $size_mb, $disk_space); ?></a>
    </li>
    <?php if ((isset($adminImages) && $page == 'site_builder') || $this->session->userdata('user_type') == 'Admin') : ?><li><a href="/asset/images#adminImagesTab"><?php echo $this->lang->line('modal_imagelibrary_tab_otherimages'); ?></a></li><?php endif; ?>
    <li><a href="/asset/images#searchImagesTab" id="anchorSearchImagesTab"><?php echo $this->lang->line('modal_imagelibrary_tab_search');?></a></li>
</ul> <!-- /tabs -->

<div class="tab-content forImageLib" id="divImageLibrary">

    <div class="tab-pane active" id="myImagesTab">

        <?php if (isset($userImages)) : ?>

            <?php $this->load->view("shared/myimages", array('userImages' => $userImages)); ?>

        <?php else : ?>

            <!-- Alert Info -->
            <div class="alert alert-info">
                <button type="button" class="close fui-cross" data-dismiss="alert"></button>
                <?php echo $this->lang->line('modal_imagelibrary_message_noimages'); ?>
            </div>

        <?php endif; ?>

    </div><!-- /.tab-pane -->

    <div class="tab-pane" id="adminImagesTab">

        <div class="images clearfix" id="adminImages">

            <?php if (isset($adminImages)) : ?>

                <?php foreach ($adminImages as $img) : ?>
                    <div class="image">

                        <?php
                            $imageUrl_ = "./" . $this->config->item('images_dir') . "/" . $img;
                        ?>
                        <div class="imageWrap" data-org-src="<?php echo $this->config->item('images_dir') . "/" . $img; ?>" data-admin="true" data-thumb="<?php echo thumb($imageUrl_, 250, 140); ?>" style="background-image: url('<?php echo thumb($imageUrl_, 250, 140); ?>')">

                            <div class="ribbon-wrapper-red"><div class="ribbon-red"><?php echo $this->lang->line('modal_imagelibrary_ribbon_admin'); ?></div></div>
                        </div>

                    </div><!-- /.image -->
                <?php endforeach; ?>

            <?php endif; ?>

        </div><!-- /.adminImages -->

    </div><!-- /.tab-pane -->

    <div class="tab-pane" id="searchImagesTab">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for images" id="inputSearchImages">
                        <span class="input-group-btn">
                            <button type="submit" class="btn" id="buttonSearchImages"><span class="fui-search"></span></button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="images clearfix" id="searchImages">
            </div>
        </div>
        <div class="row">
            <div id="divImagesLoading" style="text-align: center; display: none;">
                <img src="<?php echo base_url('img/loading.gif'); ?>">
            </div>
        </div>
        <div class="row load-more-search-images" style="display:none;">
            <button class="btn btn-info btn-wide"><?php echo $this->lang->line('modal_imagelibrary_load_more');?></button>
        </div>
    </div><!-- /.tab-pane -->

</div> <!-- /tab-content -->