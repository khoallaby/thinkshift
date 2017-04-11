<?php
use ThinkShift\Plugin\Users;

$tags = Users::getUserStrengths();
?>
<div class="card visible-md-block visible-lg-block mb-4">
    <div class="card-block">
        <h6 class="mb-3">My Strength's</h6>

        <?php
        if( $tags ) {
            ?>
            <ul class="list-unstyled list-spaced user-tags">
                <?php foreach( $tags as $tagId => $tag ) { ?>
                    <li><span class="text-muted icon mr-3"></span> <?= $tag; ?></li>
                <?php } ?>
            </ul>
            <?php
        }
        ?>
    </div>
</div>