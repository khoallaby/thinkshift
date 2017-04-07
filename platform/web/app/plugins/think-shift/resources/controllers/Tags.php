<?php
namespace ThinkShift\Plugin;


class Tags extends Users {
    public static $taxonomyName;


    public function init() {

    }



    public static function getTaxName() {
        return self::$taxonomyName;
    }


    /******************************************************************************************
     * Functions for interacting with WP's Tags and Categories
     ******************************************************************************************/


    /**
     * Get all the strengths
     * @param bool $wpOjbect Returns a WP Object or not
     *
     * @return array|bool|int|\WP_Error
     */
    public static function getAllStrengths( $wpOjbect = true ) {

        $strengths = static::getTagsFromCategory( static::$strengthMetaKey );

        if( $wpOjbect ) {
            return $strengths;
        } else {
            $return = [];
            foreach ( $strengths as $strength ) {
                $return[ $strength->term_id ] = $strength->name;
            }

            return $return;
        }

    }



    public static function getTagsFromCategory( $category ) {
        $taxonomy = 'tag-category';

        # find the term_id from $category
        if( is_numeric($category) ) {
            $categoryId = $category;
        } elseif( is_string($category) ) {
            # if its a slug
            if( sanitize_title($category) == $category ) {
                if( $term = get_term_by( 'slug', $category, $taxonomy ) )
                    $categoryId = $term->term_id;
            } else {
                if( $term = get_term_by( 'name', $category, $taxonomy ) )
                    $categoryId = $term->term_id;
            }
        }


        if( isset($categoryId) ) {
            return get_terms( [
                'taxonomy'   => $taxonomy,
                'hide_empty' => false,
                'child_of'   => $categoryId
            ] );
        } else {
            return false;
        }
    }


    public static function getAllTags() {
        return get_terms( 'tag-category' );

    }








}

add_action( 'init', array( \ThinkShift\Plugin\Tags::get_instance(), 'init' ));
