<?php

namespace ThinkShift\Plugin;



class Resources extends CustomPostTypes {
    public static $postType = 'resource';

    public function init() {
        #add_action( 'wp_footer', array( $this, 'wpFooter' ) );
        #add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ) );
    }




    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/



    # Alters the main queries on selected pages
    public static function alterPageQueries( $query ) {

        if( $query->is_main_query()  && !is_admin() ) {
            #if( $query->is_post_type_archive( self::$postType ) )
                #$query = static::filterQueryOrder( $query );
        }
        return $query;

    }



}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Careers::get_instance(), 'init' ));

