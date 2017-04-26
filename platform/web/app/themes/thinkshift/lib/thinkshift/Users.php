<?php

namespace ThinkShift\Theme;



class Users extends Base {
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
        # changes url of login
        add_filter( 'login_url', [ $this, 'changeLoginUrl' ], 10, 3 );



    }



    /**********************************************
     * Actions/Filters
     **********************************************/


    # changes url of login
    function changeLoginUrl( $login_url, $redirect, $force_reauth ) {
        $login_url = get_bloginfo( 'url' ) . '/login';
        # @todo: fix weird bug that adds ?redirect_to to the end of query
        # @todo: also redirects weird on admin modal login
        if ( !empty($redirect) )
            $login_url = add_query_arg('redirect_to', urlencode($redirect), $login_url);
        if ( $force_reauth )
            $login_url = add_query_arg('reauth', '1', $login_url);

        return $login_url;
    }

    # adds nav-link class to all menu anchor tags
    function addClassMenuLink( $atts, $item, $args ) {
        // check if the item is in the primary menu
        if( $args->theme_location == 'primary_navigation' ) {
            // add the desired attributes:
            $atts['class'] = 'nav-link';
        }
        return $atts;
    }










}


add_action( 'plugins_loaded', array( \ThinkShift\Theme\Users::get_instance(), 'init' ));