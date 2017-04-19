<?php
namespace ThinkShift\Plugin;



class CustomPostTypes extends Base {
    public static $postType;

	public function init() {
        add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ) );
    }




    /******************************************************************************************
     * Actions/filters
     ******************************************************************************************/



    # Alters the main queries on selected pages
    public static function alterPageQueries( $query ) {
        /**
         * Filters/searches all the posts on /career archive page.
         */

        if( $query->is_main_query()  && !is_admin() ) {
            if( $query->is_post_type_archive( 'career' ) ||
                $query->is_post_type_archive( 'video' ) ||
                $query->is_post_type_archive( 'resource' ) ) {

                $query = self::filterQuery( $query );
                # do filtering
            }


        }
        return $query;

    }


    /**
     * Checks the $_GET variables and does filtering
     * @param $query
     *
     * @return mixed
     */
    public static function filterQuery( $query ) {
        if( isset($_GET['limit']) && is_numeric($_GET['limit']) )
            $limit = $_GET['limit'];
        else
            $limit = 100;
        $query->set( 'posts_per_page', intval($limit) );


        if( isset($_GET['strengths']) )
            $strengths = array_map( 'intval', $_GET['strengths'] );
        else
            $strengths = is_user_logged_in() ? array_keys( Users::getUserStrengths(2) ) : [];

        if( $query->is_post_type_archive( 'career' ) ||
            $query->is_post_type_archive( 'video' )  ||
            $query->is_post_type_archive( 'resource' ) ) {

            $taxQuery = static::getTaxQuery( $strengths );
            $query->set( 'tax_query', $taxQuery );
        }

        return $query;
    }




    /**
     * Orderby/Order metakeys
     * @param $query
     *
     * @return mixed
     */
    public static function filterQueryOrder( $query ) {
        global $wpdb;
        $keys = [
            'salary' => 'med_wage',
            'education_no_degree' => 'education_min',
            'education_high_school' => 'education_min',
            'education_post_secondary' => 'education_min',
            'education_masters_degree' => 'education_min',
            'education_post_secondary_certificate' => 'education_min',
            'education_2_year_college' => 'education_min',
            'education_bachelors_degree' => 'education_min',
            'education_masters_degree_or' => 'education_min'
        ];
        #vard(sanitize_key($keys[ $_GET['orderby'] ]));


        if( !empty($_GET['orderby']) ) {

            /*
            if( in_array($_GET['orderby'], array_keys($keys)) ) {
                $careerKeys = Careers::careerKeys();

                $metaKey = sanitize_key($keys[ $_GET['orderby'] ]);
                $metaValue = $careerKeys[ $_GET['orderby'] ];

                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'meta_key', $metaKey );
                #var_dump($metaKey);
                #var_dump($metaValue);

                if( $metaKey == 'education_min' ) {
                    $metaQuery = [
                        [
                            'key' => $metaKey,
                            'value' => $metaValue,
                            'compare' => '='
                        ]
                    ];
                    $query->set( 'orderby', 'meta_value' );
                    $query->set( 'meta_query', $metaQuery );
                }
            }
            */

            $query->set( 'orderby', 'meta_value_num' );
            if( in_array($_GET['orderby'], array_keys($keys)) )
                $query->set( 'meta_key', sanitize_key($keys[ $_GET['orderby'] ]) );


            if( $_GET['order'] == 'desc' )
                $query->set( 'order', 'desc' );
            elseif( $_GET['order'] == 'asc' )
                $query->set( 'order', 'asc' );

        }
        #var_dump($query);

        return $query;
    }






    /******************************************************************************************
     * Register CPT functions
     ******************************************************************************************/



    #todo maybe move to their own class
    public function registerAll() {

        $this->registerCpt('assessment', 'assessments', array(
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-chart-line',
            'supports' => array( 'title', 'editor', 'page-attributes', 'custom-fields' ),
        ) );


        $this->registerCpt('career', 'careers', array(
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-admin-customizer',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' )
        ) );

        $this->registerCpt('resource', 'resources', array(
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-archive',
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields' )
        ) );

        $this->registerCpt('video', 'videos', array(
            'exclude_from_search' => true,
            'menu_icon' => 'dashicons-video-alt3',
            'supports' => array( 'title', 'editor', 'thumbnail' )
        ) );


        $this->registerTaxonomy( 'tag-category', 'tag-categories', [ 'career', 'video', 'resource', 'user'], [
            /*
            'rewrite' => array(
                'with_front' => false,
                'slug' => 'author/tag' // Use 'author' (default WP user slug).
            )
            */
        ] );

        /*
        $this->registerTaxonomy( 'tag-category', 'tag-categories', 'user', [
            'rewrite' => array(
                'with_front' => true,
                'slug' => 'author/tag' // Use 'author' (default WP user slug).
            ),
            # @todo? update function http://justintadlock.com/archives/2011/10/20/custom-user-taxonomies-in-wordpress
            #'update_count_callback' => 'my_update_profession_count'
        ] );*/

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
                'query_var' => true
                #'sort' => true
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
			'not_found'           => __( 'No ' . ucwords($cptNamePlural) .' found' ),
			'not_found_in_trash'  => __( 'No ' . ucwords($cptNamePlural) .' in Trash' ),
		);
		$defaults = array(
			'label'               => __( $cptNamePlural ),
			'description'         => __( ucwords($cptName) . ' Description' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', /*'excerpt', 'custom-fields'*/ ),
			'taxonomies'          => array( /*'category', 'post_tag'*/ ),
			'hierarchical'        => true,
			'public'              => true,
            /*
            # Defaults for public = true
			'show_ui'             => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
            */
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-post',
			'can_export'          => true,
			'has_archive'         => $cptNamePlural,
			'capability_type'     => 'page',
		);
		$args = wp_parse_args( $args, $defaults );
		register_post_type( ucwords( str_replace(' ', '-', $cptName) ), $args );

	}


	public static function cleanName( $str ) {
		return ucwords(str_replace( '-', ' ', $str ));
	}

	
	
	
	
	public static function isPostType( $postType ) {
        if( is_post_type_archive( $postType ) || is_singular( $postType ) )
            return true;
        else
            return false;
    }
}

add_action( 'init', array( \ThinkShift\Plugin\CustomPostTypes::get_instance(), 'registerAll' ));