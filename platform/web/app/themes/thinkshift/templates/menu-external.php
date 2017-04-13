<?php
use ThinkShift\Theme\Menu;
?>


<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">Start Bootstrap</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <?php
            $args = [
                'menu_class'     => 'nav navbar-nav navbar-right',
                'container'      => '',
                'walker'         => new Menu()
            ];

            if ( has_nav_menu( 'logged_out_navigation' ) && !is_user_logged_in() ) :
                $args['theme_location'] = 'logged_out_navigation';
                wp_nav_menu( $args );
            endif;

            ?>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>