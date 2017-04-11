<?php
$args = [
    'redirect'       => get_bloginfo( 'url' ),
    'form_id'        => 'loginform-custom',
    'label_username' => __( 'Email', 'thinkshift' ),
    'label_password' => __( 'Password', 'thinkshift' ),
    'label_remember' => __( 'Remember Me', 'thinkshift' ),
    'label_log_in'   => __( 'Log In', 'thinkshift' ),
    'remember'       => true
];
wp_login_form( $args );

while ( have_posts() ) : the_post();
    get_template_part( 'templates/content', 'page' );
endwhile;