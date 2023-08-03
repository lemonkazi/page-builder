<select class="form-control select select-default select-block select-sm mbl" name="blockCategory" data-with-search>
    <?php foreach ($blockCategories as $blockCategory) : ?>
    <option value="<?php echo $blockCategory['blocks_categories_id']; ?>"><?php echo $blockCategory['category_name']; ?></option>
    <?php endforeach; ?>
</select>