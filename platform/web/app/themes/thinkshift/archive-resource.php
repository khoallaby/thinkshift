<?php
global $wp_query;



include( locate_template( 'templates/shared/header-filter-strengths.php' ) );

if ( have_posts() ) :
?>
    <section class="container-fluid pt-4">
        <div class="row">
        <?php
        while ( have_posts() ) : the_post();
            ?>
            <div class="col-md-4">
                <h2><?php the_title(); ?></h2>
                <div><?php the_content(); ?></div>
                <figure><a href="<?php the_permalink(); ?>"><img src="//lorempixel.com/640/360" class="img-fluid" /></a></figure>
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
