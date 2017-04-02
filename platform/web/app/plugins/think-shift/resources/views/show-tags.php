<?php
$users = \ThinkShift\Plugin\Users::get_instance();
$tags = $users::getUserStrengths();
if( $tags ) {
    ?>
    <ul class="list-unstyled list-spaced user-tags">
    <?php foreach( $tags as $tag ) { ?>
        <li><span class="text-muted icon mr-3"></span> <?= $tag; ?></li>
    <?php } ?>
    </ul>
    <?php
}
