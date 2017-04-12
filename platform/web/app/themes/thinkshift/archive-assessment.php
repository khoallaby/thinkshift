<?php
use ThinkShift\Plugin\Assessments;

$statuses = Assessments::canAccess();
vard($statuses);

?>
<div class="card">
  <div class="card-body">
        <div class="step">
            <ul class="nav nav-justified">
            <?php
            if ( have_posts() ) :
                $i = 0;
                while ( have_posts() ) : the_post();
                    $completed = $statuses[ $i ];
                    $i ++;

                    # checks if User has the in/complete Tag for the following assessment
                    ?>
                    <li class="<?php if ( ! $completed ) echo 'active'; ?>">
                        <a href="<?php the_permalink(); ?>">
                            <div class="icon fa fa-shopping-cart"></div>
                            <div class="heading">
                                <div class="title"><?php echo the_title(); ?></div>
                                <div class="description"><?php get_template_part( 'templates/' . get_post_type() . '/index', 'occupation' ); ?></div>
                            </div>
                        </a>
                    </li>
                    <?php
                endwhile;

                the_posts_navigation();

            endif; ?>

          </ul>
        </div>
  </div>
</div>
