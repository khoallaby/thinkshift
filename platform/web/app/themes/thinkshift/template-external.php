<?php
/* Template Name: Logged Out Pages */

get_template_part( 'templates/header', 'external' );


while ( have_posts() ) : the_post();
?>
    <section class="header-content"><?php echo get_post_meta( $post->ID, 'header_content', true ); ?></section>
    <section class="content">
        <?php the_content(); ?>
        <?php # @marty: add core here to test with, but eventually move to WP's wysiwyg  ?>

        This is a test datra

    </section>


<?php

endwhile;
