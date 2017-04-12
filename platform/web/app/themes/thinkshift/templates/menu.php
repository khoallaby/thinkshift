<?php 
use ThinkShift\Theme\Menu;
global $current_user; 
?>


<aside class="app-sidebar" id="sidebar">
    <div class="sidebar-header">
        <a class="sidebar-brand" href="<?php echo esc_url( home_url() ); ?>"><span class="highlight"><?php bloginfo('title'); ?></span></a>
        <button type="button" class="sidebar-toggle">
            <i class="fa fa-times"></i>
        </button>
    </div>
    <div class="sidebar-menu">

        <?php
        $args = [
            'menu_class'     => 'sidebar-nav',
            'container'      => '',
            'walker'         => new Menu()
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
    <div class="sidebar-footer">
        <ul class="menu">
            <li>
                <a href="/" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-cogs" aria-hidden="true"></i>
                </a>
            </li>
            <li><a href="#"><span class="flag-icon flag-icon-th flag-icon-squared"></span></a></li>
        </ul>
    </div>
</aside>