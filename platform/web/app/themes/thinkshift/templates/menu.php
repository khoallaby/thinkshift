<?php
use ThinkShift\Theme\Menu;
global $current_user;
?>

<aside class="app-sidebar bg-themec-blue" id="sidebar">
  <ul class="nav navbar-nav navbar-right bg-themec-yellow profile-thumb">
    <li class="dropdown profile">
        <a href="<?php echo bp_loggedin_user_domain(); ?>l" class="dropdown-toggle"  data-toggle="dropdown">
            <img class="profile-img" src="<?php echo get_avatar_url( $current_user->ID ); ?>">
            <div class="title">Profile</div>
        </a>
        <div class="dropdown-menu">
            <div class="profile-info">
                <h4 class="username"><?php echo $current_user->first_name . ' ' . $current_user->last_name; ?></h4>
            </div>
            <ul class="action">
                <!--
                <li>
                    <a href="#">
                        <span class="badge badge-danger pull-right">5</span>
                        My Inbox
                    </a>
                </li>
                <li>
                    <a href="#">
                        Setting
                    </a>
                </li>
                -->
                <li><a href="<?php echo wp_logout_url() ?>">Logout</a></li>
            </ul>
        </div>
    </li>
  </ul>
    <!-- <div class="sidebar-header">
        <button type="button" class="sidebar-toggle">
            <i class="fa fa-times"></i>
        </button>
    </div> -->
    <div class="sidebar-menu">
        <?php
        $args = [
            'menu_class'     => 'sidebar-nav',
            'container'      => '',
            'walker'         => new Menu\menuLoggedIn()
        ];
        if ( has_nav_menu( 'primary_navigation' ) && is_user_logged_in() ) :
            $args['theme_location'] = 'primary_navigation';
            wp_nav_menu( $args );
        endif;

        if ( has_nav_menu( 'logged_out_navigation' ) && !is_user_logged_in() ) :
            $args['theme_location'] = 'logged_out_navigation';
            wp_nav_menu( $args );
        endif;
        ?>
    </div>
</aside>
