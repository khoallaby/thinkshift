<?php
use ThinkShift\Plugin\Assessments;

global $wp_query, $assesments, $statuses;


if ( ! isset( $assesments ) ) {
    $assesments = Assessments::getCompletedStatus();
}

if ( ! isset( $statuses ) ) {
    $statuses = Assessments::canAccess();
}
?>

<div class="row mt-4 mb-4" id="assessment-position">

    <?php
    if ( is_post_type_archive( 'assessment' ) ) {
        $posts = $wp_query->get_posts();
    } else {
        $posts = \ThinkShift\Plugin\Base::getPosts( 'assessment', [
            'orderby' => 'menu_order',
            'order'   => 'ASC'
        ] );
    }
    if ( ! empty( $posts ) ) :
        $i = 0;
        foreach ( $posts as $post ) {
            $status    = $statuses[ $i ];
            $completed = $assesments[ $i ];

            if ( $status ) {
                $active = 'inactive';
                $link   = '';
            } else {
                $active = 'bg-themec-yellow';
                $link   = get_the_permalink( $post->ID );
            }
            # checks if User has the in/complete Tag for the following assessment
            ?>

            <div class="col-lg-4">
                <div class="card mb-4 <?php echo $active; ?>">
                    <div class="card-body">
                        <?php if ( ! $completed ) { ?>
                            <i class="fa fa-paper-plane-o lg" aria-hidden="true"></i>
                        <?php } else { ?>
                            <i class="fa fa-check lg" aria-hidden="true"></i>
                        <?php } ?>
                        <h5><?php echo get_the_title( $post->ID ); ?></h5>
                        <?php
                        $descriptionCss = 'description ';

                        if ( $completed ) {
                            $description = 'You Have Completed!';
                            $descriptionCss .= 'completed';
                        } else {
                            # if this is the current one
                            if( $status ) {
                                $description = 'Not Yet Started';
                                $descriptionCss .= 'incomplete';
                            } else {
                                $description = 'Next up';
                                $descriptionCss .= 'current';
                            }

                        }

                        echo sprintf( '<div class="%s">%s</div>', $descriptionCss, $description );
                        ?>

                        <?php if ( !$completed && !$status ) : ?>
                            <a<?php echo $link ? sprintf( ' href="%s"', $link ) : ''; ?>>Take The Assessment</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php
            $i ++;

        }

        the_posts_navigation();

    endif;
    wp_reset_postdata();
    ?>

</div>
