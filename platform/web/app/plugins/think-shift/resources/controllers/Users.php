<?php
namespace ThinkShift\Plugin;

use ThinkShift\Plugin\Infusionsoft;

class Users extends Base {
    private static $infusionsoft, $user;
    public static  $contactId, $userId;


    public function init() {
        parent::init();

        self::$contactId = self::getContactId();
        add_action('init', array( __CLASS__, 'setUserId' ) );

        add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );
        add_action( 'user_register', array( $this, 'user_register' ), 50 );

    }









    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/

    /**
     * Runs on (successful?) user Log in
     * @param $user_login
     * @param $user
     */
    public function wp_login( $user_login, $user ) {
        #if ( current_user_can( 'subscriber' ) ) {
        #$fields = $this->parseFields( $user->ID );
        #$this->addInfusionsoftContact( $user->ID, $fields );
        self::setUserId( $user->ID );

        // update user's "strengths" metadata
        self::updateUserStrengths();

        #}
    }


    /**
     * Runs on successful user registration
     */
    public function user_register( $userId ) {
        $fields = $this->parseFields( $userId );
        $this->addInfusionsoftContact( $userId, $fields );
    }






    /******************************************************************************************
     * Misc helper functions
     ******************************************************************************************/

    /**
     * Sets up our user/userID variables. If user is logged in, use current user.
     * @param null $userId
     */
    public static function setUserId( $userId = null ) {
        if( is_int($userId) ) {
            self::$userId = $userId;
            if( $user = get_user_by( 'id', $userId ) )
                self::$user = $user;
        } elseif( function_exists( 'is_user_logged_in') && is_user_logged_in() ) {
            self::$user = wp_get_current_user();
            self::$userId = self::$user->ID;
        } else {
            self::$user = self::$userId = null;
        }

        self::$contactId = get_user_meta( self::$userId, 'infusionsoft_id', true );
    }


    /**
     * Retrieves Infusionsoft's contact ID, stored in usermeta
     * @return int Infusionsoft's contact ID
     */
    public static function getContactId( $userId = null) {
        if( !$userId )
            $userId = self::$userId;

        self::$contactId = $contactId = get_user_meta( $userId, 'infusionsoft_id', true );

        # @todo: update to return null if no user
        return (int) $contactId;

    }


    /**
     * Parses data from our user and compiles it to the right field names. Then sends it to infusionsoft
     * @return array Fields with [infusionsoft-key] => [usermeta name]
     */
    public function parseFields( $userId = null ) {
        if( !$userId )
            $userId = self::$userId;

        $userMeta = get_userdata( $userId );
        $fields = array(
            'FirstName' => $userMeta->first_name,
            'LastName' => $userMeta->last_name,
            'Email' => $userMeta->user_email
        );
        #}
        # todo: add parse for social media logins

        return $fields;

    }

    public static function getInfusionsoft() {
        $is = self::$infusionsoft = Infusionsoft::get_instance();
        return $is;
    }



    public static function updateUserLogin( $userId, $userLogin ) {
        global $wpdb;
        return $wpdb->update( $wpdb->users,
            [ 'user_login' => $userLogin ],
            [ 'ID' => $userId ],
            [ '%s' ],
            [ '%d' ]
        );
    }







    /******************************************************************************************
     * Functions for handling Contacts
     ******************************************************************************************/




    /**
     * Adds a contact to Infusionsoft
     * @param $fields Fields of user meta to change. In form of [infusionsoft-key] => [usermeta name]
     */
    public function addInfusionsoftContact( $userId = null, $fields = array() ) {
        if( !$userId )
            $userId = self::$userId;

        $contactId = self::getInfusionsoft()->addContact( $fields );

        if( $contactId ) {
            update_user_meta( $userId, 'infusionsoft_id', $contactId );
            self::$contactId = $contactId;
        }

    }


    public static function updateUserStrengths() {

        $tags = self::getUserTagsByCategory( 'MA Value Creation Strengths' ); // or 41
        $i = 1;
        if( $tags ) {
            foreach ( $tags as $k => $tag )
                update_user_meta( self::$userId, 'strength_' . $i ++, $tag['GroupName'] );
        }

    }








    /******************************************************************************************
     * Tag handling functions
     ******************************************************************************************/


    /**
     * Returns the user's strengths (3) from usermeta
     * @return array
     */
    public static function getUserStrengths() {
        $metaKey = 'strength_';
        $strengths = [];
        for( $i = 1; $i <= 3; $i++ )
            $strengths[ $metaKey . $i] = get_user_meta( self::$userId, $metaKey . $i, true );

        return $strengths;
    }


    /**
     * Gets all the tags and categories for a user
     *
     * @param null $contactId Optional, defaults to current user's contactID
     * @return array|bool Array of matching tag/categories
     */
    public static function getUserTags( $userId = null ) {
        if( !$userId )
            $userId = self::$userId;

        $contactId = self::getContactId( $userId );

        # todo: save tags somewhere later
        return self::getUserTagsByContactId( $contactId );

    }


    /**
     * Gets all the tags and categories for a user (by contactID)
     *
     * @param null $contactId Optional, defaults to current user's contactID
     * @return array|bool Array of matching tag/categories
     */
    public static function getUserTagsByContactId( $contactId = null ) {

        if( !$contactId )
            $contactId = self::getContactId();

        if( $contactId )
            return self::getInfusionsoft()->getTagsByContactId( $contactId );
        else
            return false;

    }


    /**
     * Gets user's tags by Category Name or ID
     *
     * @param null $category
     * @param null $contactId
     *
     * @return array
     */
    public static function getUserTagsByCategory( $category = null, $contactId = null ) {
        if( !$contactId )
            $contactId = self::getContactId();

        return self::getInfusionsoft()->getUserTagsByCategory( $category, $contactId );

    }





    public static function getUserMatchingCareers( $limit = 10 ) {
        $strengths = self::getUserStrengths();
        $meta_query = [];

        for( $i = 1; $i <= 3; $i++ ) {
            $meta_query[] = [
                'key'     => 'ValueType' . $i,
                'value'   => $strengths,
                'compare' => 'IN'
            ];
        }

        $careers = self::getPosts( 'career', [
            'limit' => $limit,
            'meta_query' => $meta_query
        ]);

        return $careers;

    }


    /**
     * Saves all Infusionsoft Tags and Categorys, into the Tag CPT
     */
    public static function saveAllTagsFromInfusionsoft() {

        $categories = Infusionsoft::get_instance()->getAllTagCategories();
        $tags = Infusionsoft::get_instance()->getAllTags();

        foreach( $categories as $category ) {
            $term = wp_insert_term( $category['CategoryName'], 'tag-category' );

            # term already exists, update that record
            # @todo: potential bug if users have more than 1 category with the same exact name, as they'll get overwritten
            if( is_wp_error($term) && $term->get_error_code() == 'term_exists' ) {
                $termId = $term->get_error_data();
                update_term_meta( $termId, 'infusionsoft_id', $category['Id'] );
            # tag was inserted successfully, update the metadata
            } else {
                update_term_meta( $term['term_id'], 'infusionsoft_id', $category['Id'] );
            }

        }

    }

}

add_action( 'init', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
