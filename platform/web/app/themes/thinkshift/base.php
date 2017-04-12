<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<?php get_template_part( 'templates/head' ); ?>
<body <?php body_class( 'with-top-navbar' ); ?>>
    <!--[if IE]>
    <div class="alert alert-warning">
        <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your
        browser</a> to improve your experience.', 'sage'); ?>
    </div>
    <![endif]-->
    <?php
    do_action( 'get_header' );
    #get_template_part( 'templates/header' );
    ?>
    <div class="wrap container-fluid app app-default" role="document">
        <?php #get_template_part( 'templates/menu' ); ?>
        <aside class="app-sidebar" id="sidebar">
            <div class="sidebar-header">
                <a class="sidebar-brand" href="#"><span class="highlight">Flat v3</span> Admin</a>
                <button type="button" class="sidebar-toggle">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="sidebar-menu">
                <ul class="sidebar-nav">
                    <li class="active">
                        <a href="./index.html">
                            <div class="icon">
                                <i class="fa fa-tasks" aria-hidden="true"></i>
                            </div>
                            <div class="title">Dashboard</div>
                        </a>
                    </li>
                    <li class="@@menu.messaging">
                        <a href="./messaging.html">
                            <div class="icon">
                                <i class="fa fa-comments" aria-hidden="true"></i>
                            </div>
                            <div class="title">Messaging</div>
                        </a>
                    </li>
                    <li class="dropdown ">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="icon">
                                <i class="fa fa-cube" aria-hidden="true"></i>
                            </div>
                            <div class="title">UI Kits</div>
                        </a>
                        <div class="dropdown-menu">
                            <ul>
                                <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> UI Kits</li>
                                <li><a href="./uikits/customize.html">Customize</a></li>
                                <li><a href="./uikits/components.html">Components</a></li>
                                <li><a href="./uikits/card.html">Card</a></li>
                                <li><a href="./uikits/form.html">Form</a></li>
                                <li><a href="./uikits/table.html">Table</a></li>
                                <li><a href="./uikits/icons.html">Icons</a></li>
                                <li class="line"></li>
                                <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Advanced Components</li>
                                <li><a href="./uikits/pricing-table.html">Pricing Table</a></li>
                                <!-- <li><a href="./uikits/timeline.html">Timeline</a></li> -->
                                <li><a href="./uikits/chart.html">Chart</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="icon">
                                <i class="fa fa-file-o" aria-hidden="true"></i>
                            </div>
                            <div class="title">Pages</div>
                        </a>
                        <div class="dropdown-menu">
                            <ul>
                                <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Admin</li>
                                <li><a href="./pages/form.html">Form</a></li>
                                <li><a href="./pages/profile.html">Profile</a></li>
                                <li><a href="./pages/search.html">Search</a></li>
                                <li class="line"></li>
                                <li class="section"><i class="fa fa-file-o" aria-hidden="true"></i> Landing</li>
                                <!-- <li><a href="./pages/landing.html">Landing</a></li> -->
                                <li><a href="./pages/login.html">Login</a></li>
                                <li><a href="./pages/register.html">Register</a></li>
                                <!-- <li><a href="./pages/404.html">404</a></li> -->
                            </ul>
                        </div>
                    </li>
                </ul>
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


        <div class="app-container">
            <?php
            do_action( 'get_header' );
            if( is_user_logged_in() )
                get_template_part( 'templates/header' );
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body app-heading">
                            <div class="app-title">
                                <?php get_template_part( 'templates/page', 'header' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">

                    <main class="card main">
                        <?php include Wrapper\template_path(); ?>
                    </main><!-- /.main -->

                    <div class="card card-tab">
                        <div class="card-body no-padding tab-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content row">
            <main class="main">
                <?php #include Wrapper\template_path(); ?>
            </main><!-- /.main -->
            <?php if ( Setup\display_sidebar() ) : ?>
                <aside class="sidebar">
                    <?php include Wrapper\sidebar_path(); ?>
                </aside><!-- /.sidebar -->
            <?php endif; ?>
        </div><!-- /.content -->
    </div><!-- /.wrap -->
    <?php
    do_action( 'get_footer' );
    get_template_part( 'templates/footer' );
    wp_footer();
    ?>
</body>
</html>
