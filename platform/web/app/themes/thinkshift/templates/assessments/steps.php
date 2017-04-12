<?php
use ThinkShift\Plugin\Assessments;

$statuses = Assessments::canAccess();

?>
<div class="card">
    <div class="card-body">
        <div class="step">
            <ul class="nav">
                <?php
                if ( have_posts() ) :
                    $i = 0;
                    while ( have_posts() ) : the_post();
                        $completed = $statuses[ $i ];
                        $i ++;

                        if( $completed ) {
                            $active = 'inactive';
                            $link = '#';
                        } else {
                            $active = 'active';
                            $link = get_the_permalink();
                        }
                        # checks if User has the in/complete Tag for the following assessment
                        ?>
                        <li class="<?php echo $active; ?>">
                            <a href="<?php echo $link; ?>">
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
