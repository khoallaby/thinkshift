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
    if( is_page_template( 'template-external.php' ) ) {
        get_template_part( 'templates/menu-external' );
        get_template_part( 'base', 'external' );
    } else {
    ?>
    <div class="wrap app app-default" role="document">
        <?php if( is_page('login') || is_page('register') ) { ?>
        <div class="app-container app-login">
            <div class="flex-center">
                <div class="app-header"></div>
                <div class="app-body">
                    <div class="loader-container text-center">
                        <div class="icon">
                            <div class="sk-folding-cube">
                                <div class="sk-cube1 sk-cube"></div>
                                <div class="sk-cube2 sk-cube"></div>
                                <div class="sk-cube4 sk-cube"></div>
                                <div class="sk-cube3 sk-cube"></div>
                            </div>
                        </div>
                        <div class="title">Logging in...</div>
                    </div>
                    <div class="app-block">
                        <div class="app-form">
                            <?php
                            if ( is_page( 'login' ) )
                                get_template_part( 'templates/content-page', 'login' );
                            elseif ( is_page( 'register' ) )
                                get_template_part( 'buddypress/members/index-register' );
                            #get_template_part( 'templates/content-page', 'register' );
                            ?>
                        </div>
                    </div>
                </div>
                <div class="app-footer">
                </div>
            </div>
        </div>
        <?php
        } else {
            get_template_part( 'templates/menu' );
            ?>
        <div class="app-container">
            <?php
            do_action( 'get_header' );
            get_template_part( 'templates/header' );
            get_template_part( 'templates/page', 'header' );
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <?php include Wrapper\template_path(); ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div><!-- /.wrap -->
    <?php
    }

    do_action( 'get_footer' );
    get_template_part( 'templates/footer' );
    wp_footer();
    ?>
</body>
</html>
