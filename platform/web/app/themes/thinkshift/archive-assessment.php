<?php #get_template_part('templates/page', 'header'); ?>



<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
    </div>
    <?php get_search_form(); ?>
<?php else : ?>
    <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <header>
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </header>
            <div class="row">
              <div class="col-lg-12">
                <?php
                    get_template_part('templates/' . get_post_type() . '/index', 'occupation' );
                ?>
              </div>
            </div>
        </article>
    <?php

    endwhile;

    the_posts_navigation();

endif;