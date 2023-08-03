<?php foreach ($templates as $template) : ?>

    <div class="site" id="template_<?php echo $template->pages_id; ?>" data-cat="<?php echo $template->category_id?>">

        <div class="window">

            <div class="top">

                <div class="buttons clearfix">
                    <span class="left red"></span>
                    <span class="left yellow"></span>
                    <span class="left green"></span>
                </div>

            </div><!-- /.top -->

            <div class="viewport">

                <?php if ($template->pagethumb != '') : ?>
                <a href="<?php echo site_url('templates/' . $template->pages_id);?>" class="placeHolder">
                    <img data-original="<?php echo base_url() . $template->pagethumb;?>">
                </a>
                <?php else : ?>
                <a href="<?php echo site_url('templates/' . $template->pages_id);?>" class="placeHolder">
                    <img src="<?php echo base_url() . "img/nothumb.png";?>">
                </a>
                <?php endif; ?>

            </div><!-- /.viewport -->

            <div class="bottom"></div><!-- /.bottom -->

        </div><!-- /.window -->

        <div class="siteDetails">

            <hr class="dashed light">

            <div class="clearfix">

                <select class="form-control select select-default select-block select-sm jsCatSelect">
                    <option value="0"><?php echo $this->lang->line('templates_label_choosecat');?></option>
                    <?php foreach( $categories as $category ):?>
                    <option <?php if ( $template->templates_categories_id === $category['templates_categories_id'] ) {echo "selected";}?> value="<?php echo $category['templates_categories_id'];?>"><?php echo $category['category_name'];?></option>
                    <?php endforeach?>
                </select>
                
                <a href="<?php echo site_url('templates/' . $template->pages_id);?>" title="<?php echo $this->lang->line('templates_details_tooltip_edit');?>" data-toggle="tooltip" data-delay='{"show": 2000, "hide": 0}' class="btn btn-primary btn-embossed btn-half pull-left btn-sm first" data-siteid="">
                    <span class="fui-new"></span>
                </a>

                <a class="btn btn-danger btn-embossed btn-half pull-left deleteSiteButton btn-sm second linkDelTemplate" data-toggle="confirmation" data-title="<?php echo $this->lang->line('templates_delete_confirmation_header');?>" data-content="<?php echo $this->lang->line('templates_delete_confirmation_content');?>" data-popout="true" data-singleton="true" data-btn-ok-label="<?php echo $this->lang->line('templates_delete_confirmation_yes');?>" data-btn-cancel-label="<?php echo $this->lang->line('templates_delete_confirmation_cancel');?>" data-template-id="<?php echo $template->pages_id;?>">
                    <span class="fui-trash"></span>
                </a>

            </div>

        </div><!-- /.siteDetails -->

    </div><!-- /.site -->

<?php endforeach; ?>

<div class="site empty"></div>
<div class="site empty"></div>
<div class="site empty"></div>
<div class="site empty"></div>