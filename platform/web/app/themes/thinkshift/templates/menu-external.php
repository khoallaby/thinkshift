<?php
use ThinkShift\Theme\Menu;
?>


<nav id="mainNav" class="navbar navbar-default navbar-toggleable-md fixed-top navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand page-scroll" href="/">PIVOT TO OPPORTUNITY</a>
            <button class="navbar-toggle navbar-toggler navbar-toggler-right collapsed" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="sr-only ">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->

            <?php
            $args = [
                'menu_class'     => 'nav navbar-nav navbar-right',
                'container_class' => 'collapse navbar-collapse',
                'container_id' => 'navbarCollapse',
                'walker'         => new Menu\menuLoggedOut()
            ];

            if ( has_nav_menu( 'logged_out_navigation' ) ) :
                $args['theme_location'] = 'logged_out_navigation';
                wp_nav_menu( $args );
            endif;

            ?>
    </div>
    <!-- /.container-fluid -->
</nav>
