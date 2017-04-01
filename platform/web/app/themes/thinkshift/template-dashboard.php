<?php
/* Template Name: Dashboard */

get_template_part( 'templates/page', 'header' );

?>

<section class="container pt-4">
    <div class="row">
        <div class="col-lg-3">
            <?php
            get_template_part( 'templates/dashboard/card', 'profile' );
            get_template_part( 'templates/dashboard/card', 'my-strengths' );
            ?>
        </div>

        <div class="col-lg-6">
            <?php
            get_template_part( 'templates/dashboard/card', 'career' );
            get_template_part( 'templates/dashboard/card', 'congratulations' );
            ?>
            <div class="card mb-4 hidden-md-down">
                <?php
                while ( have_posts() ) : the_post();
                    get_template_part( 'templates/content', 'page' );
                endwhile;
                ?>
            </div>
        </div>
        <div class="col-lg-3">
            <?php
            get_template_part( 'templates/dashboard/card', 'congratulations-alert' );
            get_template_part( 'templates/dashboard/card', 'video' );
            get_template_part( 'templates/dashboard/card', 'your-circle' );
            get_template_part( 'templates/dashboard/card', 'links' );
            ?>
        </div>
    </div>
</section>

