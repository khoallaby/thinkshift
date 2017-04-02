<?php

namespace ThinkShift\Plugin;

use WP_Query;


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


	}



	public static function get( $var ) {
		return self::$$var;
	}



	public static function get_query( $post_type = 'post', $args = array() ) {
		$defaults = array (
			'post_type'      => array( $post_type ),
			'post_status'    => array( 'publish' ),
			'posts_per_page' => - 1,
			'order'          => 'DESC',
			'orderby'        => 'post_date'
		);

		$args = wp_parse_args( $args, $defaults );
		$query = new WP_Query( $args );

		return $query;
	}


	public static function get_posts( $post_type = 'post', $args = array() ) {
		$query = self::get_query( $post_type, $args );
		return $query->get_posts();
	}





}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Base::get_instance(), 'init' ));