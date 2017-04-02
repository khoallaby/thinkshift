<?php
namespace ThinkShift\Plugin;


class CustomPostTypes extends Base {

	protected function __construct() {
		parent::__construct();
	}

	public function init() {
		$this->registerCpt('career', 'careers' );
		//$this->register_tax( 'career-category', 'career-categories', 'careers' );

	}


	public function registerTaxonomy( $taxName, $taxNamePlural, $postType, $args = array() ) {
		register_taxonomy(
			$taxName,
			$postType,
			array(
				'label' => __( $this::cleanName($taxNamePlural) ),
				#'public' => false,
				'rewrite' => false,
				'hierarchical' => true,
			)
		);
	}


	public function registerCpt( $cptName, $cptNamePlural, $args = array() ) {

		$labels = array(
			'name'                => _x( ucwords($cptNamePlural), 'Post Type General Name' ),
			'singular_name'       => _x( ucwords($cptName) . '', 'Post Type Singular Name' ),
			'menu_name'           => __( ucwords($cptNamePlural) ),
			'parent_item_colon'   => __( 'Parent ' . ucwords($cptName) ),
			'all_items'           => __( 'All ' . ucwords($cptNamePlural) ),
			'view_item'           => __( 'View ' . ucwords($cptName) ),
			'add_new_item'        => __( 'Add New ' . ucwords($cptName) ),
			'add_new'             => __( 'Add New' ),
			'edit_item'           => __( 'Edit ' . ucwords($cptName) ),
			'update_item'         => __( 'Update ' . ucwords($cptName) ),
			'search_items'        => __( 'Search ' . ucwords($cptName) ),
			'not_found'           => __( 'Not found' ),
			'not_found_in_trash'  => __( 'Not found in Trash' ),
		);
		$defaults = array(
			'label'               => __( $cptNamePlural ),
			'description'         => __( ucwords($cptName) . ' Description' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'taxonomies'          => array( /*'category', 'post_tag'*/ ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-post',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		$args = wp_parse_args( $args, $defaults );
		register_post_type( ucwords( str_replace(' ', '-', $cptNamePlural) ), $args );
		die();

	}


	public static function cleanName( $str ) {
		return ucwords(str_replace( '-', ' ', $str ));
	}

}

add_action( 'init', array( \ThinkShift\Plugin\CustomPostTypes::get_instance(), 'init' ));