<?php
namespace ThinkShift\Plugin;

#use ThinkShift\Plugin\Infusionsoft;

class Cron extends Users {


    public function init() {
    }


    public function runAll() {
        static::updateUserStrengths();
        static::saveAllTagsFromInfusionsoft();
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
        $tags = Infusionsoft::get_instance()->getAllTags();
        $relationalArray = [];


        # add all the categories as terms into
        foreach( $categories as $category ) {
            $term = wp_insert_term( $category['CategoryName'], 'tag-category' );

            # term already exists, update that record
            # @todo: potential bug if users have more than 1 category with the same exact name, as they'll get overwritten
            if( is_wp_error($term) && $term->get_error_code() == 'term_exists' ) {
                $termId = $term->get_error_data();
                # tag was inserted successfully, update the metadata
            } else {
                $termId = $term['term_id'];
            }

            update_term_meta( $termId, 'infusionsoft__category_id', $category['Id'] );
            if( isset( $category['CategoryDescription'] ) )
                update_term_meta( $termId, 'category_description', $category['CategoryDescription'] );

            $relationalArray[ $category['Id'] ] = $termId;
        }



        # Adds all the Tags as posts. Adds any categories the Tag belongs to
        foreach( $tags as $tag ) {
            $tagExists = static::getOne( 'tag', [
                'title' => $tag['GroupName'],
                'posts_per_page' => 1
            ] );

            # create new Tag if not exists
            if( $tagExists ) {
                $tagId = $tagExists->ID;
            } else {
                $tagId = wp_insert_post( [
                    'post_content' => '',
                    'post_title' => $tag['GroupName'],
                    'post_status' => 'publish',
                    'post_type' => 'tag',
                ]);
            }

            #update meta
            update_term_meta( $tagId, 'infusionsoft__tag_id', $tag['Id'] );
            if( isset( $category['GroupDescription'] ) )
                update_term_meta( $tagId, 'tag_description', $tag['GroupDescription'] );

            # add the matching term (category) to our Tag
            if( $tag['GroupCategoryId'] ) {
                $termId = $relationalArray[ $tag['GroupCategoryId'] ];
                wp_set_post_terms( $tagId, $termId, 'tag-category' );
            }

        }



    }




}

add_action( 'init', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
