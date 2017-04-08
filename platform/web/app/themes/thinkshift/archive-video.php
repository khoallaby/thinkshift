<?php
/* Template Name: Video */

use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Videos;



get_template_part( 'templates/page', 'header' );
$strengths = Tags::getAllStrengths( false );
get_template_part( 'templates/shared/header', 'strengths-filter' );

if ( have_posts() ) : while( have_posts() ) : the_post();
?>
    <section class="container pt-4">
        <div class="row">
        <?php

        $videos = get_post_meta( get_the_ID(), 'videos', true );

        foreach( $videos as $k => $video ) :
            $url = Videos::getVideoLink( $video['video_url'], $video['video_source'] );
            $link = Videos::getVideoThummbnailLink( $video['video_url'], $video['video_source'], true );

            ?>
            <div class="col-md-4">

                <a href="#modal-video-<?php echo esc_attr( $k ); ?>" data-toggle="modal"><?php echo $link; ?></a>

                <div id="modal-video-<?php echo esc_attr( $k ); ?>" class="modal fade modal-video">
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
        <?php endforeach; ?>
        </div>
    </section>
<?php
endwhile;


else :
    get_template_part( 'templates/content' , 'no-results' );
endif;

