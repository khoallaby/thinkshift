<?php

use ThinkShift\Plugin\Videos;

$videoId = get_the_ID();

$videoUrl = get_post_meta( $videoId, 'video_url', true );
$videoSource = get_post_meta( $videoId, 'video_source', true );
$tags = wp_get_object_terms( $videoId, 'tag-category');
$url = Videos::getVideoLink( $videoUrl, $videoSource );
$link = Videos::getVideoThummbnailLink( $videoUrl, $videoSource, true );

?>

    <a href="#modal-video-<?php echo $videoId; ?>" data-toggle="modal"><?php echo $link; ?></a>

    <div id="modal-video-<?php echo $videoId; ?>" class="modal fade modal-video">
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

