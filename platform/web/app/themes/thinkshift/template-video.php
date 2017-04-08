<?php
/* Template Name: Video */

use ThinkShift\Theme\Template;
use ThinkShift\Plugin\Tags;

/* featured image */


$strengths = Tags::getAllStrengths( false );
get_template_part( 'templates/shared/header', 'strengths-filter' );
while (have_posts()) : the_post();
?>
    <section class="container pt-4">
    <?php
        the_content();

        $videos = get_post_meta( get_the_ID(), 'videos', true );

        $columns = 3;
        $i = 0;

        foreach( $videos as $k => $video ) :
            Template::echo_open_row( $i, $columns );

            if( $video['video_source'] == 'youtube' )
                $url = 'https://www.youtube.com/embed/' . $video['video_url'];
            elseif( $video['video_source'] == 'vimeo' )
                $url = 'https://player.vimeo.com/video/' . $video['video_url'];
            else
                $url = $video['video_url'];

            ?>
            <div class="col-md-4">

                <a href="#video-<?php echo esc_attr( $k ); ?>" class="btn btn-lg btn-primary" data-toggle="modal">Video link</a>

                <div id="video-<?php echo esc_attr( $k ); ?>" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <iframe width="100%" style="height: auto; min-height: 300px;" src="<?php echo esc_attr( $url ); ?>" data-src="<?php echo esc_attr( $url ); ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <?php
            $i++;
            Template::echo_close_row( $i, $columns, count($videos) );
        endforeach;
    ?>
    </section>
<?php
endwhile;


