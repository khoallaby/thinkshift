<?php

namespace ThinkShift\Plugin;

#use WP_Query, WP_User_Query;



class AdminUi extends Base {

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
    }



    /******************************************************************************************
     * Actions/filters 
     ******************************************************************************************/





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

}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\AdminUi::get_instance(), 'init' ));

