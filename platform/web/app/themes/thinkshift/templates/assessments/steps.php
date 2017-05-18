<?php
use ThinkShift\Plugin\Assessments;

global $wp_query, $assesments, $statuses;


if( !isset($assesments) )
    $assesments = Assessments::getCompletedStatus();

if( !isset($statuses) )
    $statuses = Assessments::canAccess();
?>
  <div class="col-lg-12">
    <div class="card">
        <div class="card-header bg-themec-yellow">
          <h6>Positioning Journey Tracker</h6>
        </div>
        <div class="card-body">
            <div class="step">
                <ul class="nav">
                    <?php
                    if( is_post_type_archive( 'assessment' ) ) {
                        $posts = $wp_query->get_posts();
                    } else {
                        $posts = \ThinkShift\Plugin\Base::getPosts( 'assessment', [
                            'orderby' => 'menu_order',
                            'order' => 'ASC'
                        ] );
                    }
                    if ( !empty($posts) ) :
                        $i = 0;
                        foreach( $posts as $post ) {
                            $status = $statuses[ $i ];
                            $completed = $assesments[ $i ];
                            $i ++;

                            if ( $status ) {
                                $active = 'inactive';
                                $link   = '';
                            } else {
                                $active = 'active';
                                $link   = get_the_permalink( $post->ID );
                            }
                            # checks if User has the in/complete Tag for the following assessment
                            ?>
                            <li class="<?php echo $active; ?>">
                                <a<?php echo $link ? sprintf( ' href="%s"', $link ) : ''; ?>>
                                    <div class="icon fa fa-suitcase"></div>
                                    <div class="heading">
                                        <div class="title"><?php echo get_the_title( $post->ID ); ?></div>
                                        <?php
                                        $description = $completed ? 'Assessment Completed' : 'Start your assessment';
                                        $descriptionCss = $status ? 'description description-completed' : 'description';
                                        if( $status && !$completed )
                                            $description = '&nbsp;';
                                        echo sprintf( '<div class="%s">%s</div>', $descriptionCss, $description );
                                        ?>

                                        <!--<div class="description description-completed">Assessment Completed</div>-->
                                        <!-- <div class="description"><?php get_template_part( 'templates/' . get_post_type() . '/index', 'occupation' ); ?></div> -->
                                    </div>
                                </a>
                            </li>
                            <?php
                        }

                        the_posts_navigation();

                    endif;
                    wp_reset_postdata();
                    ?>

                </ul>
            </div>
        </div>
    </div>
  </div>
