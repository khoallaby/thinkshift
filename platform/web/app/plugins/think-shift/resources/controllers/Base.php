<?php

namespace ThinkShift\Plugin;

use WP_Query;


class Base{
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

		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		#add_action( 'init', array( $this, 'add_excerpts' ) );

	}



	public function theme_setup() {
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


	public function get_responsive_image( $post_id, $size = 'medium', $attr ) {
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