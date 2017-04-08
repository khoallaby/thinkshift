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

            if( $video['video_source'] == 'youtube' ) {
                $url = 'https://www.youtube.com/embed/' . $video['video_url'];
                $imgUrl = 'https://img.youtube.com/vi/' . $video['video_url'] . '/0.jpg';
            } elseif( $video['video_source'] == 'vimeo' ) {
                $url = 'https://player.vimeo.com/video/' . $video['video_url'];
                # todo: vimeo urls -- https://gist.github.com/tjFogarty/08cddb1854e2ae593bf0
                $imgUrl = '';
            } else {
                $url = $video['video_url'];
                $imgUrl = '';
            }


            if( isset($imgUrl) ) {
                $link = '<img src="' . esc_attr( $imgUrl ) . '" />';
            } else {
                $link = $video['video_url'];
            }

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
            <?php
            $i++;
            Template::echo_close_row( $i, $columns, count($videos) );
        endforeach;
    ?>
    </section>
<?php
endwhile;


