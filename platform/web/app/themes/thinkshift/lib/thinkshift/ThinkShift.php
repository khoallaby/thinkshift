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


    }



    /**********************************************
     * Actions/Filters
     **********************************************/


    # adds nav-link class to all menu anchor tags
    function addClassMenuLink( $atts, $item, $args ) {
        // check if the item is in the primary menu
        if( $args->theme_location == 'primary_navigation' ) {
            // add the desired attributes:
            $atts['class'] = 'nav-link';
        }
        return $atts;
    }





    /**********************************************
     * Tools
    **********************************************/


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


    public function getResponsiveImage( $post_id, $size = 'medium', $attr ) {
        $defaults = array (
            'col-xs' => 12,
            'col-sm' => 6,
            'col-md' => 4,
            'fluid' => false,
            'gutter' => '15px',
            'class' => 'img-responsive',
            'srcset' => '',
            'sizes' => ''
        );
        $attr = wp_parse_args( $attr, $defaults );

        # todo: set up srcset/sizes
        return get_the_post_thumbnail( $post_id, $size, $attr );

    }









}


add_action( 'plugins_loaded', array( \ThinkShift\Theme\Base::get_instance(), 'init' ));