<?php
use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Videos;
?>
<div class="row">
<?php include( locate_template( 'templates/career/header-filter.php' ) ); ?>

<?php if (!have_posts()) : ?>
    <div class="career-card no-results"><?php _e('Sorry, no results were found.', 'thinkshift'); ?></div>
<?php else : ?>
      <div class="col-lg-8 mt-4">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/' . get_post_type() . '/index', 'occupation' ); ?>
        <?php endwhile; ?>
      </div>
    <?php
    the_posts_navigation();

endif; ?>
</div>
