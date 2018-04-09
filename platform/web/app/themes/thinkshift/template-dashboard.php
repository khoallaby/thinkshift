<?php
/* Template Name: Dashboard */



if ( is_user_logged_in() ) {
?>

<section class="container-fluid pt-4">
    <div class="row">
        <div class="col-lg-4">
            <?php
            get_template_part( 'templates/dashboard/card', 'profile' );
            get_template_part( 'templates/dashboard/card', 'pivot-video' );
            get_template_part( 'templates/dashboard/card', 'video' );
            get_template_part( 'templates/dashboard/card', 'your-circle' );
            ?>
        </div>

        <div class="col-lg-8">
            <?php
            //get_template_part( 'templates/dashboard/card', 'career' );
            get_template_part( 'templates/dashboard/card', 'my-strengths' );
            get_template_part( 'templates/dashboard/card', 'congratulations' );
            ?>
            <!--
            <div class="card mb-4">
                <?php/*
                while ( have_posts() ) : the_post();
                    get_template_part( 'templates/content', 'page' );
                endwhile;
                */
                ?>
            </div>
            -->
        </div>
    </div>
</section>

<?php

} else {
    get_template_part( 'templates/form', 'login' );
}
