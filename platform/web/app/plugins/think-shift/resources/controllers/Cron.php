<?php
namespace ThinkShift\Plugin;

#use ThinkShift\Plugin\Infusionsoft;

class Cron extends Users {
    #object for storing key/value relations, so it can be referenced and not have multiple query calls
    public static $taxonomyName;
    public static $obj = [];

    public function init() {
        static::$taxonomyName = \ThinkShift\Plugin\Tags::getTaxName();
        die( static::$taxonomyName);
    }




    public static function destroyObj() {
        static::$obj = [];
    }




    /******************************************************************************************
     * Functions for running cron jobs
     ******************************************************************************************/



    public static function updateUserStrengths() {

        $tags = static::getUserTagsByCategory( static::$strengthMetaKey ); // or 41


        $i = 1;
        if( $tags ) {
            $allStrengths = Tags::getAllStrengths();

            # add all user's strengths into usermeta, have to convert from IS title to WP tag ID#
            foreach ( $tags as $tag ) {
                foreach( $allStrengths as $strengthId => $strengthTitle ) {
                    if( $strengthTitle == $tag['GroupName'] ) {
                        update_user_meta( static::$userId, 'strength_' . $i ++, $strengthId );
                        break;
                    }
                }
            }

            # blank the un-used/deleted strengths

            for( $i2 = $i; ( $i2 <= 3 || $i2 <= count($tags) ) ; $i2++ )
                update_user_meta( static::$userId, 'strength_' . $i2, '' );
        }

    }





    /**
     * Saves all Infusionsoft Tags and Categorys, into the Tag CPT
     * Ideally used in a cron job
     */
    public static function saveAllTagsFromInfusionsoft() {
        $categories = Infusionsoft::get_instance()->getAllTagCategories();


        # add all the categories as terms into
        foreach( $categories as $category ) {
            $term = wp_insert_term( $category['CategoryName'], 'tag-category' );

            # term already exists, update that record
            if( is_wp_error($term) && $term->get_error_code() == 'term_exists' ) {
                $termId = $term->get_error_data();
            # tag was inserted successfully, update the metadata
            } else {
                $termId = $term['term_id'];
            }

            update_term_meta( $termId, 'infusionsoft__category_id', $category['Id'] );
            if( isset( $category['CategoryDescription'] ) )
                update_term_meta( $termId, 'category_description', $category['CategoryDescription'] );

            #save the term ID, so we can reference it while saving the children tags
            self::$obj[ $category['Id'] ] = $termId;
        }




        $tags = Infusionsoft::get_instance()->getAllTags();


        # Adds all the Tags as posts. Adds any categories the Tag belongs to
        foreach( $tags as $tag ) {
            #if( !isset(self::$obj[ $tag['GroupCategoryId'] ])  )
            #    break;

            $term = wp_insert_term( $tag['GroupName'], 'tag-category', [
                'parent' => self::$obj[ $tag['GroupCategoryId'] ]
            ]);


            # term already exists, update that record
            if( is_wp_error($term) && $term->get_error_code() == 'term_exists' ) {
                $termId = $term->get_error_data();
            # tag was inserted successfully, update the metadata
            } else {
                $termId = $term['term_id'];
            }

            #update meta
            update_term_meta( $termId, 'infusionsoft__tag_id', $tag['Id'] );
            if( isset( $category['CategoryDescription'] ) )
                update_term_meta( $termId, 'tag_description', $tag['CategoryDescription'] );


        }




    }




}

add_action( 'init', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
