<?php

namespace ThinkShift\Plugin;

#use WP_Query, WP_User_Query;



class AdminUi extends Base {

	public function init() {



        add_filter('piklist_taxonomies', 'piklistTaxonomies');

	    /**
         * stuff for adding user taxonomy
         */
        #add_action( 'edit_category_form_fields', array( $this, 'addTagMetaBoxes' ) );

        # http://wordpress.stackexchange.com/questions/218523/custom-taxonomy-on-users-with-ui
        #add_action( 'admin_menu', array( $this, 'add_user_taxonomy_admin_page' ) );
        #add_filter( 'parent_file', array( $this, 'filter_user_taxonomy_admin_page_parent_file' ) );


    }



    /******************************************************************************************
     * Actions/filters 
     ******************************************************************************************/


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

