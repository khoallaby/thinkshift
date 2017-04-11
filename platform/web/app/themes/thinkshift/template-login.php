<?php
/* Template Name: Login */

$dashboardUrl = get_bloginfo( 'url' ) . '/dashboard';

get_template_part( 'templates/page', 'header' );



if ( is_user_logged_in() ) {
    #wp_redirect( $dashboardUrl ); # todo: redirect needs to go into add_action
    wp_loginout( home_url() ); # Log Out link
    echo " | ";
    wp_register('', ''); # Site Admin link
} else {
    get_template_part( 'templates/form', 'login' );
    the_content();
}
?>

