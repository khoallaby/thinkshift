<?php
$assesments = [];
foreach( $wp_query->get_posts() as $post ) {
    $complete = \ThinkShift\Plugin\Users::userHasTag(
        get_post_meta( get_the_ID(), 'tag-complete', true )
    );
    $incomplete = \ThinkShift\Plugin\Users::userHasTag(
        get_post_meta( get_the_ID(), 'tag-incomplete', true )
    );

    $assesments[] = $complete;
}

# if none are completed,  show the first one
if( array_sum($assesments) == 0 )
    $assesments[0] = true;



if( have_posts() ) :
    $i = 0;
    while (have_posts()) : the_post();
        $show = $assesments[ $i ];
        $i++;

        # checks if User has the in/complete Tag for the following assessment


        if( $show ) {
            echo sprintf( '<h2 class="entry-title"><a href="%s" class="btn btn-secondary">%s</a></h2>', get_permalink(), get_the_title() );
        } else {
            ?>
            <article <?php post_class(); ?>>
                <header>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" class="btn btn-primary"><?php the_title(); ?></a></h2>
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
