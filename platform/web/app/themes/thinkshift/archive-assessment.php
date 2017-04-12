<?php
use ThinkShift\Plugin\Assessments;

$statuses = Assessments::canAccess();


?>

<div class="section">
  <div class="section-body">
    <div class="step">
      <ul class="nav nav-tabs nav-justified" role="tablist">

<?php if( have_posts() ) :
    $i = 0;
    while (have_posts()) : the_post();
        $completed = $statuses[ $i ];
        $i++;

        # checks if User has the in/complete Tag for the following assessment
        if( $completed ) { ?>
            <li role="step">
                  <a href="#step<?php echo $i; ?>" id="step<?php echo $i; ?>-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true">

                      <div class="icon fa fa-shopping-cart"></div>
                      <div class="heading">
                          <div class="title"><?php echo get_the_title(); ?></div>
                      </div>
                  </a>
              </li>

        <?php } else {
            ?>
            <li role="step">
                  <a href="#step<?php echo $i; ?>" id="step<?php echo $i; ?>-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true" clas="active">

                      <div class="icon fa fa-shopping-cart"></div>
                      <div class="heading">
                          <div class="title"><?php echo get_the_title(); ?></div>
                          <div class="description"><?php get_template_part( 'templates/' . get_post_type() . '/index', 'occupation' ); ?></div>
                      </div>
                  </a>
              </li>

        <?php

        }

    endwhile;

    the_posts_navigation();

endif; ?>

</ul>
    </div>
  </div>
</div>
