<?php
use ThinkShift\Plugin\Videos;
use ThinkShift\Plugin\Users;
?>
<div class="card mb-4 hidden-md-down">
    <div class="card-header bg-themec-yellow">
      <i class="icon fa fa-file-video-o fa-1x"></i> <span><h6 class="mb-3">Career Exploration Featured Video</h6></span>
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
        <!-- <div data-grid="images" data-target-height="150">
            <img class="media-object" data-width="640" data-height="640" data-action="zoom"
                 src="assets/img/instagram_2.jpg">
        </div>
        <p><strong>CEO Jacob Johnson says</strong> "everyday I get to make decisions people for I don't even
            know. Imagine the power."</p>
        <button class="btn btn-outline-primary btn-sm">Watch the video</button> -->
    </div>
</div>
