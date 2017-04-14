<?php
/* Template Name: Logged Out Pages */

get_template_part( 'templates/header', 'external' );


while ( have_posts() ) : the_post();
?>
        <?php


        //the_content();

        $pages = [
            'benefits-of-membership',
            'join-a-circle',
            'pivot-power-assessment',
            'partner-with-us',
            'home'
        ];

        if( in_array( $post->post_name, $pages ) )
            get_template_part( 'templates/external/' . $post->post_name );

        #@marty, look in the templates/external folder to place your html in


        ?>



<?php

endwhile;
