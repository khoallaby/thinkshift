<?php

use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Videos;


# gets the category tags for eahc post
#vard( wp_get_object_terms( 415, 'tag-category'));


$strengths = Tags::getAllStrengths( false );
get_template_part( 'templates/shared/header', 'strengths-filter' );


if ( have_posts() ) :
    ?>
    <section class="container-fluid pt-4">
        <div class="row">
        <?php
        while ( have_posts() ) : the_post();

            $videos = get_post_meta( get_the_ID(), 'videos', true );
            $videos = [];


            $videoUrl = get_post_meta( get_the_ID(), 'video_url', true );
            $videoSource = get_post_meta( get_the_ID(), 'video_source', true );
            $tags = wp_get_object_terms( get_the_ID(), 'tag-category');
            $url = Videos::getVideoLink( $videoUrl, $videoSource );
            $link = Videos::getVideoThummbnailLink( $videoUrl, $videoSource, true );

            ?>
            <div class="col-md-4">

                <a href="#modal-video-<?php the_ID(); ?>" data-toggle="modal"><?php echo $link; ?></a>

                <div id="modal-video-<?php the_ID(); ?>" class="modal fade modal-video">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!--
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            -->
                            <div class="modal-body">
                                <iframe src="<?php echo esc_attr( $url ); ?>" data-src="<?php echo esc_attr( $url ); ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php
        endwhile;

        the_posts_navigation();
        ?>
        </div>
    </section>
    <?php

else :
    get_template_part( 'templates/content' , 'no-results' );
endif;

