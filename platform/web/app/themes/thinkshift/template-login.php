<?php
/* Template Name: Login */

$dashboardUrl = get_bloginfo( 'url' ) . '/dashboard';




if ( is_user_logged_in() ) {
    #wp_redirect( $dashboardUrl ); # todo: redirect needs to go into add_action
    wp_loginout( home_url() ); # Log Out link
    echo " | ";
    wp_register('', ''); # Site Admin link
} else {
    get_template_part( 'templates/form', 'login' );
    while ( have_posts() ) : the_post();
        get_template_part( 'templates/content', 'page' );
    endwhile;
}
?>

