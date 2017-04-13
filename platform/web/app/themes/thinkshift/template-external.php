<?php
/* Template Name: Logged Out Pages */

$dashboardUrl = get_bloginfo( 'url' ) . '/dashboard';


?>


<div class="section">
    <div class="section-body">
        <div>
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Dropdown <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
            </ul>
        </div>
    </div>
</div>

<?php

#get_template_part( 'templates/header-logged-out' );
the_content();


#if ( is_user_logged_in() ) {
    #wp_redirect( $dashboardUrl ); # todo: redirect needs to go into add_action
    #wp_loginout( home_url() ); # Log Out link
    #echo " | ";
    #wp_register('', ''); # Site Admin link
#} else {
    #get_template_part( 'templates/form', 'login' );
    the_content();
#}
?>

