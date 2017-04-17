<?php
use ThinkShift\Theme\Menu;
?>


<nav id="mainNav" class="navbar navbar-toggleable-md navbar-inverse fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button class="navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">Marketplace</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->

            <?php
            $args = [
                'menu_class'     => 'nav navbar-nav navbar-right',
                'container_class' => 'collapse navbar-collapse',
                'container_id' => 'navbarCollapse'
                //'walker'         => new Menu()
            ];

            if ( has_nav_menu( 'logged_out_navigation' ) ) :
                $args['theme_location'] = 'logged_out_navigation';
                wp_nav_menu( $args );
            endif;

            ?>
    </div>
    <!-- /.container-fluid -->
</nav>
