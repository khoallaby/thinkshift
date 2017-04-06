<?php
namespace ThinkShift\Plugin;


class Tags extends Users {
    public function init() {

    }





    /******************************************************************************************
     * Functions for interacting with WP's Tags and Categories
     ******************************************************************************************/


    public static function getAllStrengths( $return = '' ) {
        $strengths = static::getPosts( 'tag', [
            'tax_query' => array(
                array(
                    'taxonomy' => 'tag-category',
                    'field'    => 'slug',
                    'terms'    => [ static::$strengthMetaKey ],
                    'operator' => 'IN',
                ),
            ),
        ] );

        return $strengths;

    }








}

add_action( 'init', array( \ThinkShift\Plugin\Tags::get_instance(), 'init' ));
