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
    $args = array(
        'redirect'       => $dashboardUrl,
        'form_id'        => 'loginform-custom',
        'label_username' => __( 'Email' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in'   => __( 'Log In' ),
        'remember'       => true
    );
    wp_login_form( $args );
}
?>

