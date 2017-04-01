<?php
$current_user = wp_get_current_user();
$users = \ThinkShift\Plugin\Users::get_instance();

if( $tags = $users::getUserTags( $current_user->ID ) ) {
?>
<table class="table table-stripeduser-tags">
	<thead>
		<tr>
			<th class="group"><h3>Group</h3></th>
			<th class="category"><h3>Category</h3></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach( $tags as $tag ) { ?>
	<tr class="category-id-<?= $tag['GroupCategoryId']; ?>">
		<td class="group"><?= $tag['GroupName']; ?></td>
		<td class="category"><?= $tag['CategoryName']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
</table>

<?php
}
