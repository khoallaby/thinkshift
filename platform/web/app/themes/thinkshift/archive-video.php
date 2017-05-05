<?php

include( locate_template( 'templates/shared/header-filter-strengths.php' ) );


if ( have_posts() ) :
    ?>
    <section class="container-fluid pt-4">
        <div class="row">
        <?php while ( have_posts() ) : the_post(); ?>
            <article class="col-md-4 video">
                <?php get_template_part( 'templates/videos/single', 'video' );?>
            </article>
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

