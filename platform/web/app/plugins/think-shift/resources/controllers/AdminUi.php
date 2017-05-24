<?php

namespace ThinkShift\Plugin;

#use WP_Query, WP_User_Query;



class AdminUi extends Base {
    static $capability = 'edit_theme_options';
    static $menuSlug = 'thinkshift-menu';


	public function init() {



        add_filter('piklist_taxonomies', [ $this, 'piklistTaxonomies' ] );

	    /**
         * stuff for adding user taxonomy
         */
        #add_action( 'edit_category_form_fields', array( $this, 'addTagMetaBoxes' ) );

        # http://wordpress.stackexchange.com/questions/218523/custom-taxonomy-on-users-with-ui
        #add_action( 'admin_menu', array( $this, 'add_user_taxonomy_admin_page' ) );
        #add_filter( 'parent_file', array( $this, 'filter_user_taxonomy_admin_page_parent_file' ) );


        # shows the strength Tags on admin CPTs like videos/careers
        add_filter( 'wp_terms_checklist_args', array( $this, 'showStrengthsOnly' ), 20, 2 );
        add_action( 'admin_menu', [ $this, 'adminMenu' ] );
    }






    /******************************************************************************************
     * Actions/filters 
     ******************************************************************************************/



    public function adminMenu() {
        add_menu_page( 'ThinkShift Dashboard', 'ThinkShift', static::$capability, static::$menuSlug, [ $this, 'viewIndex' ], 'dashicons-chart-pie', 3 );
        # submenus
        add_submenu_page( static::$menuSlug, 'Importer', 'Importer', static::$capability, 'thinkshift-importer', [ $this, 'viewImporter' ] );
    }



    /**
     * Shows only the strengths on admin edit screens that use the tag-category taxonomy (sidebar)
     */
    public function showStrengthsOnly( $args, $post_id ) {

        if( is_admin() ) {
            $screen = get_current_screen();
            if ( is_object( $screen ) && ( $screen->post_type == 'career' || $screen->post_type == 'video'  ) )
                $args['descendants_and_self'] = Users::getStrengthMetaId();
        }

        return $args;
    }



    /**
     * Re-register our tag-category taxonomy, so it shows up on admin / user profile
     */
    public function piklistTaxonomies( $taxonomies ) {

        $taxonomies[] = array(
            'object_type'   => 'user',
            'name'          => 'tag-category',
            'configuration' => array(
                'hierarchical' => true,
                'labels'       => piklist( 'taxonomy_labels', 'Tag Category' ),
                'show_ui'      => true,
                'query_var'    => true,
                'rewrite'      => array(
                    'slug' => 'tag-category'
                ),
                'show_admin_column' => true,
                'list_table_filter' => true
            )
        );

        return $taxonomies;
    }










    /**
     * Generic getView() function that runs common functions like checking for capability access
     * @param string $view  File name of the view w/o .php
     */
    public function getMenuView( $view ) {
        if ( !current_user_can( static::$capability ) )
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        static::getView( $view );
    }

    public function viewIndex() {
        self::getMenuView( 'admin/index' );
    }

    public function viewImporter() {
        self::getMenuView( 'admin/importer' );
    }





}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\AdminUi::get_instance(), 'init' ));

