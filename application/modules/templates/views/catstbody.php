<tbody>
	<?php foreach ($categories as $category) : ?>
	<tr>
		<td class="tdCatName"><?php echo $category['category_name']; ?></td>
		<td class="actions">
			<a href="" class="text-primary linkCatEdit" data-cat-id="<?php echo $category['templates_categories_id']; ?>"><span class="fui-new"></span></a>
			<a href="" class="text-danger linkCatDel" data-cat-id="<?php echo $category['templates_categories_id']; ?>"><span class="fui-cross-circle"></span></a>
		</td>
	</tr>
	<?php endforeach; ?>
</tbody>