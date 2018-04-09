<?php

namespace ThinkShift\Theme;



class Base {
    private static $instance = array();

    protected function __construct() {
    }


    public static function get_instance() {
        $c = get_called_class();
        if ( !isset( self::$instance[$c] ) ) {
            self::$instance[$c] = new $c();
            self::$instance[$c]->init();
        }
        return self::$instance[$c];
    }

    public function init() {
        add_action( 'after_setup_theme', array( $this, 'themeSetup' ) );
        #add_action( 'wp_head', array( $this, 'head' ) );

        # adds nav-link class to all menu anchor tags
        add_filter( 'nav_menu_link_attributes', array( $this, 'addClassMenuLink'),  10, 3 );


        add_filter('sage/display_sidebar', array( $this, 'removeSidebar' ) );
        add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ) );



        add_action( 'wp_footer', array( $this, 'wpFooter' ), 111 );

        #add_filter('template_include', [ $this, 'templateInclude'], 200);
    }



    /******************************************************************************************
     * Actions/Filters
     ******************************************************************************************/


    # adds nav-link class to all menu anchor tags
    function addClassMenuLink( $atts, $item, $args ) {
        // check if the item is in the primary menu
        if( $args->theme_location == 'primary_navigation' || $args->theme_location == 'logged_out_navigation' ) {
            // add the desired attributes:
            $atts['class'] = 'nav-link';
        }
        return $atts;
    }


    # Alters the main queries on selected pages
    function alterPageQueries ( $query ) {
        if ( !is_admin() && $query->is_main_query() ) :

            if( $query->is_post_type_archive( 'assessment' ) ) {
                $query->set( 'orderby', 'menu_order' );
                $query->set( 'order', 'ASC' );
            }

        endif;

        return $query;

    }

    # removes the.. sidebar
    public function removeSidebar() {
        return false;
    }




    public function wpFooter() {
        global $current_user;


        # prints user email autofill script on assessment, by injecting the $current_user's email into the JS.
        if( is_singular('assessment') ) {
            $jsFile = file_get_contents( dirname(__FILE__) . '/../../assets/scripts/assessments.js' );
            $js = str_replace( '{{user_email}}', $current_user->user_email, $jsFile );
            echo "<script type='text/javascript'>$js</script>";
        }


    }


    # Override what base template to use
    public function templateInclude( $template ) {
        global $post;

        if( is_page('login') || is_page('register') )
            array_unshift( $template->templates, 'base-page-login.php' );
        return $template;

    }


    /******************************************************************************************
     * Tools
     ******************************************************************************************/


    public function themeSetup() {
        add_image_size( 'small', 480, 0 );

        // replace default image sizes
        if( get_option( 'medium_size_w' ) == 300 && get_option( 'medium_size_h' ) == 300 ) {
            update_option( 'medium_size_w', 768, true );
            update_option( 'medium_size_h', 0, true );
        }
        if( get_option( 'large_size_w' ) == 1024 && get_option( 'large_size_h' ) == 1024 ) {
            update_option( 'large_size_w', 1170, true );
            update_option( 'large_size_h', 0, true );
        }

    }





}


add_action( 'plugins_loaded', array( \ThinkShift\Theme\Base::get_instance(), 'init' ));