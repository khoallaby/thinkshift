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
    #do_action( 'get_header' );
    #get_template_part( 'templates/header' );
    ?>
    <div class="wrap container-fluid app app-default" role="document">
        <?php get_template_part( 'templates/menu' ); ?>


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
