<?php
$users = \ThinkShift\Plugin\Users::get_instance();
$tags = $users::getUserTagsByCategory( 'MA Value Creation Strengths' ); // or 41

if( $tags ) {
    ?>
    <ul class="list-unstyled list-spaced user-tags">
    <?php foreach( $tags as $tag ) { ?>
        <li><span class="text-muted icon mr-3"></span> <?= $tag['GroupName']; ?></li>
    <?php } ?>
    </ul>
    <?php
}
