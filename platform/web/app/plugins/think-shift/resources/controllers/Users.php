<?php
namespace ThinkShift\Plugin;

use ThinkShift\Plugin\Infusionsoft;

class Users extends Base {
    private static $infusionsoft, $user;
    public static  $contactId, $userId;
    public static $strengthMetaKey = 'MA Value Creation Strengths';


    public function init() {
        parent::init();

        self::$contactId = self::getContactId();
        add_action('init', array( __CLASS__, 'setUserId' ) );

        add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );
        add_action( 'user_register', array( $this, 'user_register' ), 50 );

        add_action( 'wp', array( $this, 'userCanAccess' ) );

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

        // update user's "strengths" metadata
        self::setUserId( $user->ID );
        \ThinkShift\Plugin\Cron::updateUserStrengths();

        #}
    }


    /**
     * Runs on successful user registration
     */
    public function user_register( $userId ) {
        $fields = $this->parseFields( $userId );
        $this->addInfusionsoftContact( $userId, $fields );
    }


    /**
     * The action to check for user permissions and redirect to login if not.
     */
    public function userCanAccess() {
        if( !$this->userRoutes() )
            wp_redirect( '/login' );
    }


    /**
     * Determines what the user can see depending on page and logged in status
     * @return bool
     */
    public function userRoutes() {
        if( is_front_page() || is_home() || is_page( 'login' ) || is_page( 'register' ) ) :
            return true;
        elseif( is_user_logged_in()) :
            return true;
        else :
            return false;
        endif;

    }






    /******************************************************************************************
     * Misc helper functions
     ******************************************************************************************/



    public static function getStrengthMetaId() {
        $strengths = get_term_by( 'name' , self::$strengthMetaKey, 'tag-category' );
        if( $strengths )
            return $strengths->term_id;
        else
            return $strengths;
    }


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






    /******************************************************************************************
     * Tag handling functions
     ******************************************************************************************/


    /**
     * General function for pulling User Tags from WP
     * @param $category
     *
     * @return array|\WP_Error
     */
    public static function getUserTags( $category = null ) {
        if( $category ) {
            if( is_int($category) ) {
                $args = [ 'parent' => $category ];
            } else {
                $cat = get_term_by( 'name', $category, 'tag-category' );
                $args = [ 'parent' => $cat->term_id ];
            }
            return wp_get_object_terms( self::$userId, 'tag-category', $args );
        } else {
            return wp_get_object_terms( self::$userId, 'tag-category' );
        }

    }

    /**
     * Returns the user's strengths (3) from usermeta
     * @return array
     */
    public static function getUserStrengths() {
        $strengths = self::getUserTags( self::$strengthMetaKey );


        $return = [];
        foreach( $strengths as $strength )
            $return[ $strength->term_id ] = $strength->name;

        return $return;
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



    public static function searchCareerRelation( $count, $less = 3 ) {
        return count($count) < $less ? 'OR' : 'AND';
    }


    /**
     * Function responsible for 3 career cards on dashboard
     * @param int $limit
     *
     * @return array    Array of careers that match all 3 strengths
     */
    public static function getUserMatchingCareers( $limit = 5 ) {
        $strengths = self::getUserStrengths();

        $meta_query = [ 'relation' => static::searchCareerRelation( $strengths ) ];

        for( $i = 1; $i <= 3; $i++ ) {
            $meta_query[] = [
                'key'     => 'ValueType' . $i,
                'value'   => $strengths,
                'compare' => 'IN'
            ];
        }

        $careers = self::getPosts( 'career', [
            'posts_per_page' => $limit,
            'meta_query' => $meta_query
        ]);

        return $careers;

    }


    /**
     * Check if user has a certain Tag
     * @param null|int|string|array $terms*
     * @return bool|\WP_Error
     */
    public static function userHasTag( $terms = null ) {
        if( is_string($terms) ) {
            $terms = sanitize_title( $terms );
            if( $terms == '' )
                $terms = false;
        }

        return is_object_in_term( static::$userId, 'tag-category', $terms );
    }

}

add_action( 'init', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
