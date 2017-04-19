<?php
use ThinkShift\Plugin\Users;

$tags = Users::getUserStrengths();
?>
<div class="card mb-4" id="dashboard-strengths">
    <div class="card-header bg-themec-yellow">
        <i class="icon fa fa-suitcase fa-1x"></i> <span><h6>My Strengths</h6></span>
    </div>
    <div class="card-body">
        <div class="section">
            <div class="section-body">
                <?php if ( $tags ) : ?>
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <?php
                        $i = 0;
                        foreach ( $tags as $tagId => $tag ) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $i ? '' : 'active'; ?>" href="#strength-<?php echo sanitize_title( $tag ); ?>" role="tab"
                               data-toggle="tab"><?php echo $tag; ?></a>
                        </li>
                        <?php $i++; } ?>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php
                        $i = 0;
                        foreach ( $tags as $tagId => $tag ) {
                            ?>
                        <div role="tabpanel" class="tab-pane <?php echo $i ? '' : 'active'; ?>" id="strength-<?php echo sanitize_title( $tag ); ?>">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php echo term_description( $tagId, 'tag-category' ); ?>
                                </div>
                            </div>
                        </div>
                        <?php $i++; } ?>
                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
