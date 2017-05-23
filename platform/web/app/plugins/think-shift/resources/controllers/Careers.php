<?php

namespace ThinkShift\Plugin;



class Careers extends CustomPostTypes {
    public static $postType = 'career';

    public function init() {
        #add_action( 'wp_footer', array( $this, 'wpFooter' ) );
        add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ), 100 );
    }




    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/


    /**
     * Orderby/Order metakeys
     * @param $query
     *
     * @return mixed
     */
    public static function filterQueryOrder( $query ) {
        global $wpdb;

        if( isset($_GET['order']) && $_GET['order'] == 'desc' )
            $query->set( 'order', 'DESC' );
        else
            $query->set( 'order', 'ASC' );

        $query->set( 'orderby', 'title' );


        if( !empty($_GET['orderby']) ) {

            /*
            # order by a metakey logic

            if( in_array($_GET['orderby'], array_keys($keys)) ) {
                $careerKeys = Careers::careerKeys();

                $metaKey = sanitize_key($keys[ $_GET['orderby'] ]);
                $metaValue = $careerKeys[ $_GET['orderby'] ];

                $query->set( 'orderby', 'meta_value_num' );
                $query->set( 'meta_key', $metaKey );

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

            /*
            $query->set( 'orderby', 'meta_value_num' );
            if( in_array($_GET['orderby'], array_keys($keys)) )
                $query->set( 'meta_key', sanitize_key($keys[ $_GET['orderby'] ]) );
            */

        }


        $metaQuery = [];

        // education_min
        if( isset($_GET['education']) && !empty($_GET['education']) ) {
            $educationKeys = static::getEducationKeys();
            $educationValues = array_intersect_key( $educationKeys, array_flip($_GET['education']) );
            $metaQuery[] = [
                'key' => 'education_min',
                'value' => $educationValues,
                'compare' => 'IN'
            ];
        }

        // career fields
        if( isset($_GET['field']) && !empty($_GET['field']) ) {
            $careerKeys = static::getCareerFieldKeys();
            $careerValues = array_intersect_key( $careerKeys, array_flip($_GET['field']) );
            $metaQuery[] = [
                'key' => 'high_opp_job_family',
                'value' => $careerValues,
                'compare' => 'IN'
            ];
        }

        // self employment
        if( isset($_GET['self']) && !empty($_GET['self']) ) {
            $selfKeys = static::getSelfEmploymentKeys();
            $selfValues = array_intersect_key( $selfKeys, array_flip($_GET['self']) );
            $metaQuery[] = [
                'key' => 'pct_self_emp_cat',
                'value' => $selfValues,
                'compare' => 'IN'
            ];
        }


        if( !empty( $metaQuery ) ) {
            $query->set( 'meta_query', [$metaQuery] );
        }



        return $query;
    }



    # Alters the main queries on selected pages
    public static function alterPageQueries( $query ) {

        if( $query->is_main_query()  && !is_admin() ) {
            if( $query->is_post_type_archive( self::$postType ) )
                $query = static::filterQueryOrder( $query );
        }
        return $query;

    }



    public static function getMetaKeys( $key = null ) {
        global $wpdb;
        if( $key ) {
            $sql = 'SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s';
            $query = $wpdb->get_results( $wpdb->prepare( $sql, $key ) );
            return $query;
        } else {
            # return all;
        }

        return false;
    }



    /**
     * @todo: deprecate prob
     */
    public static function careerKeys() {

        $array = [
            'salary' => 'Average Salary',
            /*
            'education_no_degree' => 'No Degree',
            'education_high_school' => 'High school Graduate',
            'education_post_secondary' => 'Post Secondary',
            'education_masters_degree' => 'Masters Degree',
            'education_post_secondary_certificate' => 'Certification or Some College',
            'education_2_year_college' => '2-year College Degree',
            'education_bachelors_degree' => 'Bachelors Degree',
            'education_masters_degree_or' => 'Masters Degree or other Post-graduate study'
            */
        ];
        return $array;
    }


    public static function getCareerFieldKeys() {
        # use metakey - high_opp_job_family
        $keys = [
            'No',
            'Yes-Computer and Mathematical',
            'Yes-Business and Financial Operations',
            'Yes-Engineering and Architecture',
            'Yes-Healthcare Practitioners & Technical'
        ];

        return $keys;
    }


    /**
     * Returns keys used for education_min. min and max can be set to return a slice of the education keys. 
     * Useful for returning all the elements that match $min and higher
     * @param int|string $min   Min education level to return
     * @param int|string $max   Max education level to return
     *
     * @return array
     */
    public static function getEducationKeys( $min = 0, $max = 7 ) {
        $keys = [
            0 => 'No Degree',
            1 => 'High School Graduate',
            #2 => 'Post Secondary',
            #3 => 'Masters Degree',
            4 => 'Certification or Some College',
            5 => '2-year College Degree',
            6 => 'Bachelors Degree',
            7 => 'Masters Degree or other Post-graduate study',
        ];


        
        # this logic returns the min/max keys to use to slice the array. Uses int or string of the education_min value
        if( is_numeric($min) )
            $min = (int) $min;
        else
            $min = array_search( $min, $keys );

        if( is_numeric($max) )
            $max = (int) $max;
        else
            $max = array_search( $max, $keys );

        $length = ($max - $min) + 1;
        $array = array_slice( $keys, $min, $length );

        return $array;
    }




    public static function getSelfEmploymentKeys() {
        # use metakey - pct_self_emp_cat
        $keys = [
            'Average or Below',
            'Higher than Average',
            'Much Higher than Average'
        ];

        return $keys;

    }


}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Careers::get_instance(), 'init' ));

