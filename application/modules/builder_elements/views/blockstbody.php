<tbody>
	<?php foreach ($blockCategories as $blockCategory) : ?>
	<?php if ($blockCategory['blocks_categories_id'] != 1) : ?>
	<tr>
		<td class="tdCatName"><?php echo $blockCategory['category_name']; ?></td>
		<td class="actions">
			<a href="" class="text-primary linkBlockcatEdit" data-cat-id="<?php echo $blockCategory['blocks_categories_id']; ?>"><span class="fui-new"></span></a>
			<a href="" class="text-danger linkBlockcatDel" data-cat-id="<?php echo $blockCategory['blocks_categories_id']; ?>"><span class="fui-cross-circle"></span></a>
		</td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</tbody>