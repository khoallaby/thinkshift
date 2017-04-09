<?php

namespace ThinkShift\Plugin;

use WP_Query, WP_User_Query;


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
		require_once dirname(__FILE__) . '/../../vendor/autoload.php';
        #add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 2 );

	}



	public static function get( $var ) {
		return static::$$var;
	}



    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/

    /**
     * Ability to use post_title__in, in WP_Query. Search for post titles from an array
     * @param $where
     * @param $wp_query
     *
     * @return string
     */
    public function posts_where( $where, &$wp_query ) {
        global $wpdb;
        if ( $post_title__in = $wp_query->get( 'post_title__in' ) ) {
            $post_title__in = array_map( 'sanitize_title_for_query', $post_title__in );
            $post_title__in = "'" . implode( "','", $post_title__in ) . "'";
			$where .= " AND {$wpdb->posts}.post_name IN ($post_title__in)";
        }
        return $where;
    }


    /**
     * Returns an array to use for $wp_query[tax_query]
     * @param $values
     *
     * @return array
     */
    public static function getTaxQuery( $values ) {
        return self::getTaxQueryBy( 'id', $values );
    }


    /**
     * Returns an array to use for $wp_query[tax_query]
     * @param $values
     *
     * @return array
     */
    public static function getTaxQueryBy( $field = 'id', $values = [] ) {
        $less = 3;
        $relation = count($values) < $less ? 'OR' : 'AND';

        $taxQuery = [
            'relation' => $relation,
            [
                'taxonomy' => 'tag-category',
                'field' => 'id',
                'terms'   => array_map( 'intval', $values ),
            ]
        ];

        return $taxQuery;
    }

    #add_action( 'wp', array( $this, 'force_404' ) );
    public function force_404() {
        #global $wp_query; //$posts (if required)
        #if(is_page()){ // your condition
        status_header( 404 );
        nocache_headers();
        include( get_query_template( '404' ) );
        die();
        #}
    }



    /******************************************************************************************
     * Querying Functions
     ******************************************************************************************/

	public static function getQuery( $post_type = 'post', $args = array() ) {
	    if( $post_type == 'user' ) {

            $defaults = array (
                'order'          => 'DESC',
                'orderby'        => 'display_name',
                #'role'           => '',
                #'search'         => '*'.esc_attr( $search_term ).'*',
                #'count_total'    => true
            );

            $args = wp_parse_args( $args, $defaults );
            $query = new WP_User_Query( $args );

        } else {

            $defaults = array(
                'post_type'      => array( $post_type ),
                'post_status'    => array( 'publish' ),
                'posts_per_page' => -1,
                'order'          => 'DESC',
                'orderby'        => 'post_date'
            );

            $args  = wp_parse_args( $args, $defaults );
            $query = new WP_Query( $args );
        }

		return $query;
	}


    public static function getOne( $post_type = 'post', $args = array() ) {
	    $args['posts_per_page'] = 1;

        $query = self::getQuery( $post_type, $args );
        if( $query->have_posts() ) {
            if( $post_type == 'user' )
                $posts = $query->get_results();
            else
                $posts = $query->get_posts();
            return $posts[0];

        } else {
            return false;
        }
    }


	public static function getPosts( $post_type = 'post', $args = array() ) {
		$query = self::getQuery( $post_type, $args );
		if( $post_type == 'user' )
		    return $query->get_results();
		else
		    return $query->get_posts();
	}




}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Base::get_instance(), 'init' ));