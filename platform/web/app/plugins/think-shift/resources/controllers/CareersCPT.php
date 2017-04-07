<?php

namespace ThinkShift\Plugin;



class CareersCPT extends CustomPostTypes {

    public function init() {
        add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ) );
        #add_action( 'wp_footer', array( $this, 'wpFooter' ) );
    }




    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/



    # Alters the main queries on selected pages
    public static function alterPageQueries ( $query ) {
        /**
         * Filters/searches all the posts on /career archive page.
         */
        if( $query->is_post_type_archive( 'career' ) ) {

            if( isset($_GET['limit']) && is_numeric($_GET['limit']) )
                $limit = $_GET['limit'];
            else
                $limit = 100;
            $query->set( 'posts_per_page', intval($limit) );


            $strengths = isset($_GET['strengths']) ? $_GET['strengths'] : \ThinkShift\Plugin\Users::getUserStrengths();
            $relation = \ThinkShift\Plugin\Users::searchCareerRelation( $strengths );


            # set the meta query
            $metaQuery = [
                'relation' => $relation
            ];
            for( $i = 1; $i <= 3; $i++ ) {
                $metaQuery[] = [
                    'key'     => 'ValueType' . $i,
                    'value'   => (array) $strengths,
                    'compare' => 'IN'
                ];
            }
            $query->set('meta_query',$metaQuery);

        }
        return $query;

    }



}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\CareersCPT::get_instance(), 'init' ));

