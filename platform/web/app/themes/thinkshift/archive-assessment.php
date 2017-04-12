<?php
use ThinkShift\Plugin\Assessments;

$statuses = Assessments::canAccess();


?>
<div class="card">
  <div class="card-body">
        <div class="step">
          <ul class="nav nav-tabs nav-justified" role="tablist">

            <?php if( have_posts() ) :
                $i = 0;
                while (have_posts()) : the_post();
                    $completed = $statuses[ $i ];
                    $i++;

            # checks if User has the in/complete Tag for the following assessment
            ?>
            <li role="step" class="<?php if($completed){ echo ''; }else{ echo 'active'; }?>">
                <a href="#" id="step1-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="true"
                class="">

                    <div class="icon fa fa-shopping-cart"></div>
                    <div class="heading">
                        <div class="title"><?php echo get_the_title(); ?></div>
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
