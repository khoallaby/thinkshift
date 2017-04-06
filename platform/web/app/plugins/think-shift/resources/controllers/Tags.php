<?php
namespace ThinkShift\Plugin;


class Tags extends Users {
    public function init() {

    }





    /******************************************************************************************
     * Functions for interacting with WP's Tags and Categories
     ******************************************************************************************/


    public static function getAllStrengths() {
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

        $return = [];
        foreach( $strengths as $strength )
            $return[ $strength->ID ] = $strength->post_title;

        return $return;

    }








}

add_action( 'init', array( \ThinkShift\Plugin\Tags::get_instance(), 'init' ));
