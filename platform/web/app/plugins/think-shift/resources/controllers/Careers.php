<?php

namespace ThinkShift\Plugin;



class Careers extends CustomPostTypes {
    public static $postType = 'career';

    public function init() {
        #add_action( 'wp_footer', array( $this, 'wpFooter' ) );
        add_action( 'pre_get_posts', array( $this, 'alterPageQueries' ) );
    }




    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/



    # Alters the main queries on selected pages
    public static function alterPageQueries( $query ) {

        if( $query->is_main_query()  && !is_admin() ) {
            if( $query->is_post_type_archive( self::$postType ) )
                $query = static::filterQueryOrder( $query );
        }
        return $query;

    }


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


    public static function getEducationKeys( $min = 0, $max = 7 ) {
        $keys = [
            0 => 'No Degree',
            1 => 'High school Graduate',
            2 => 'Post Secondary',
            3 => 'Masters Degree',
            4 => 'Certification or Some College',
            5 => '2-year College Degree',
            6 => 'Bachelors Degree',
            7 => 'Masters Degree or other Post-graduate study'
        ];

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



}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Careers::get_instance(), 'init' ));

