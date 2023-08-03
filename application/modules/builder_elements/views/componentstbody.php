<tbody>
	<?php foreach ($componentsCategories as $componentsCategory) : ?>
	<?php if ($componentsCategory['components_categories_id'] != 1) : ?>
	<tr>
		<td class="tdCatName"><?php echo $componentsCategory['category_name']; ?></td>
		<td class="actions">
			<a href="" class="text-primary linkComponentcatEdit" data-cat-id="<?php echo $componentsCategory['components_categories_id']; ?>"><span class="fui-new"></span></a>
			<a href="" class="text-danger linkComponentcatDel" data-cat-id="<?php echo $componentsCategory['components_categories_id']; ?>"><span class="fui-cross-circle"></span></a>
		</td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</tbody>