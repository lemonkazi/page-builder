<div class="alert alert-warning">
    <h6><?php echo $this->lang->line('modal_deletetemplatecategory_heading'); ?></h6>
    <p>
        <?php echo $this->lang->line('modal_deletetemplatecategory_text'); ?>
    </p>
    <select class="form-control select select-inverse select-block select-sm mbl">
        <option value="0"><?php echo $this->lang->line('modal_deletetemplatecategory_nocat');?></option>
        <?php foreach ($categories as $category) : ?>
        <?php if ( !isset($catID) || $catID !== $category['templates_categories_id'] ):?>
        <option value="<?php echo $category['templates_categories_id']; ?>"><?php echo $category['category_name']; ?></option>
        <?php endif;?>
        <?php endforeach; ?>
    </select>
    <div class="buttons clearfix">
        <button type="button" class="btn btn-danger btn-sm btn-wide buttonConfirmCatDel" data-catid="<?php echo $catID; ?>"><?php echo $this->lang->line('modal_deletetemplatecategory_button_remove'); ?></button>
        <button type="button" class="btn btn-default btn-sm btn-wide buttonCancelCatDel"><?php echo $this->lang->line('modal_deletetemplatecategory_button_cancel'); ?></button>
    </div>
</div>