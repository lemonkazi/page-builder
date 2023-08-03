<div class="alert alert-warning">
    <h6><?php echo $this->lang->line('modal_deleteblockscategory_heading'); ?></h6>
    <p>
        <?php echo $this->lang->line('modal_deleteblockscategory_text'); ?>
    </p>
    <select class="form-control select select-inverse select-block select-sm mbl">
        <?php foreach ($blockCategories as $blockCategory) : ?>
        <option value="<?php echo $blockCategory['blocks_categories_id']; ?>"><?php echo $blockCategory['category_name']; ?></option>
        <?php endforeach; ?>
    </select>
    <div class="buttons clearfix">
        <button type="button" class="btn btn-danger btn-sm btn-wide buttonConfirmBlockCatDel" data-catid="<?php echo $catID; ?>"><?php echo $this->lang->line('modal_deleteblockscategory_button_remove'); ?></button>
        <button type="button" class="btn btn-default btn-sm btn-wide buttonCancelBlockCatDel"><?php echo $this->lang->line('modal_deleteblockscategory_button_cancel'); ?></button>
    </div>
</div>