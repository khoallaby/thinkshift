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
    <div class="wrap container-fluid app app-default" role="document">
        <?php get_template_part( 'templates/menu' ); ?>
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
    </div><!-- /.wrap -->
    <?php
    }

    do_action( 'get_footer' );
    get_template_part( 'templates/footer' );
    wp_footer();
    ?>
</body>
</html>
