<?php
use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Videos;


get_template_part( 'templates/page', 'header' );
$strengths = Tags::getAllStrengths( false );
get_template_part( 'templates/shared/header', 'strengths-filter' );
?>


    <ul id="og-grid" class="og-grid">
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
          <h1>Job title</h1>
        </a>
      </li>
      <li>
        <a href="http://cargocollective.com/jaimemartinez/" data-largesrc="images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
          <h1>Job title</h1>
        </a>
      </li>
    </ul>

<?php if (!have_posts()) : ?>
    <div class="alert alert-warning">
        <?php _e('Sorry, no results were found.', 'sage'); ?>
    </div>
    <?php get_search_form(); ?>
<?php else : ?>
    <?php while (have_posts()) : the_post(); ?>
        <!--<article <?php //post_class(); ?>>-->
            <!-- <header>
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            </header> -->
                <?php
                    //get_template_part('templates/' . get_post_type() . '/index', 'occupation' );
                ?>



        <!-- </article> -->
    <?php

    endwhile;

    the_posts_navigation();

endif;
