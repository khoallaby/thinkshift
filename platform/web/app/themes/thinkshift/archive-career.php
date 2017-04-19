<?php
use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Videos;
include( locate_template( 'templates/shared/header-filter-strengths.php' ) );
?>


    <!-- <ul id="og-grid" class="og-grid">
      <li>
        <a class="job-title" href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
          <h1>Job title</h1>
        </a>
      </li>
    </ul> -->

<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
    </div>
    <?php get_search_form(); ?>
<?php else : ?>
    <ul id="og-grid" class="og-grid">
      <?php while (have_posts()) : the_post(); ?>
        <li>
          <?php get_template_part('templates/' . get_post_type() . '/index', 'occupation' ); ?>
        </li>
      <?php endwhile; ?>
    </ul>
    <?php
    the_posts_navigation();

endif;
