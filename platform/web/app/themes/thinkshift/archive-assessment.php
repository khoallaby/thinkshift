<?php
use ThinkShift\Plugin\Assessments;

get_template_part( 'templates/page', 'header' );
$statuses = Assessments::canAccess();


if( have_posts() ) :
    $i = 0;
    while (have_posts()) : the_post();
        $completed = $statuses[ $i ];
        $i++;

        # checks if User has the in/complete Tag for the following assessment


        if( $completed ) {
            echo sprintf( '<h2 class="entry-title"><a href="%s" class="btn btn-secondary disabled">%s</a></h2>', get_permalink(), get_the_title() );
        } else {
            ?>
            <article <?php post_class(); ?>>
                <header>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" class="btn btn-primary "><?php the_title(); ?></a></h2>
                </header>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        get_template_part( 'templates/' . get_post_type() . '/index', 'occupation' );
                        ?>
                    </div>
                </div>
            </article>
            <?php

        }
    endwhile;

    the_posts_navigation();

endif;
