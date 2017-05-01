<?php
use ThinkShift\Theme\Menu;
use ThinkShift\Plugin\UserAuthentication;

global $current_user;
?>

<aside class="app-sidebar bg-themec-blue" id="sidebar">
    <div class="profile-thumb">
        <div class="profile-info">
            <span class="username">Welcome<br><?php echo $current_user->first_name . ' ' . $current_user->last_name; ?></span>
        </div>

        <button type="button" class="sidebar-toggle hidden-md-up">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="sidebar-menu">
        <?php
        $args = [
            'menu_class'     => 'sidebar-nav',
            'container'      => '',
            'walker'         => new Menu\menuLoggedIn()
        ];
        if ( has_nav_menu( 'primary_navigation' ) && is_user_logged_in() ) :
            if( current_user_can( UserAuthentication::$marketplaceAccess ) )
                $args['theme_location'] = 'primary_navigation';
            else
                $args['theme_location'] = 'subscriber_navigation';
            wp_nav_menu( $args );
        endif;

        if ( has_nav_menu( 'logged_out_navigation' ) && !is_user_logged_in() ) :
            $args['theme_location'] = 'logged_out_navigation';
            wp_nav_menu( $args );
        endif;
        ?>
    </div>
</aside>
