<?php
use ThinkShift\Plugin\Videos;
use ThinkShift\Plugin\Users;
?>
<div class="card mb-4 hidden-md-down">
    <div class="card-header bg-themec-yellow">
      <i class="icon fa fa-user-circle-o fa-1x"></i> <span><h6 class="mb-3">Pivot Stories</h6></span>
    </div>
    <div class="card-block">
        <?php
        $strengths = Users::get_instance()->getUserStrengths();
        $query = Videos::getVideosByTags( $strengths, 1 );

        if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
            get_template_part( 'templates/videos/single', 'video' );
        endwhile; endif;

        wp_reset_postdata();

        ?>

        <button class="btn btn-outline-primary btn-sm">See more stories</button>
    </div>
</div>
