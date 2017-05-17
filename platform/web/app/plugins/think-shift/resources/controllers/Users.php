<?php
/**
 *
 * Actions/Filters
 * Helper functions
 * Contacts
 * Tags
 *
 */

namespace ThinkShift\Plugin;

# todo: @tsdb include class
require_once dirname(__FILE__) . '/../../PBClub/TSDBObj.php';

class Users extends Base {
    # todo: @tsdb instantiate tsdb object from class
    private static $tsdb;
    private static $infusionsoft;
    public static  $contactId, $userId, $user;
    public static  $strengthMetaKey = 'MA Value Creation Strengths';
    public static  $userStrengths, $userStrengthsLimit;


    public function init() {
        // require_once '../../PBClub/awsconfig.php';
        # todo: @tsdb init tsdb __construct contstructor
        self::$tsdb=new \TSDBObj('thinkshiftdataserver.czlkyoy9ghkh.us-east-1.rds.amazonaws.com',
                                'awsthinkshift', '?Th1nksh1ft?', 'thinkshiftdb');
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

        # todo: update the user's entire profile
        // update user's "strengths" metadata
        self::setUserId( $user->ID );
        Cron::updateUserStrengths();

        #}
    }


    /**
     * Runs on successful user registration
     */
    public function user_register( $userId ) {
        $fields = $this->parseFields( $userId );
        $this->addInfusionsoftContact( $userId, $fields );

        # log in, redirect to homepage after registration
        #do_action( 'wp_login', $user_login );
        #wp_set_current_user( $user->ID, $user_login );
        wp_set_auth_cookie( $userId, true, is_ssl() );
        wp_safe_redirect( home_url() );
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
        # @todo: tsdb: set wp user id value by email in db
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

    # todo: tsdb update information in db
    public static function updateUserLogin( $userId, $userLogin ) {
        global $wpdb;
        return $wpdb->update( $wpdb->users,
            [ 'user_login' => $userLogin ],
            [ 'ID' => $userId ],
            [ '%s' ],
            [ '%d' ]
        );
    }


    /**
     * Check if user has role, $role
     * @param string $role  Name of the role
     * @return bool
     */
    public static function userHasRole( $role = 'administrator' ) {
        $user = Users::$user;
        if ( isset( $user->roles ) && is_array( $user->roles ) )
            return in_array( $role, $user->roles );
        else
            return false;
    }


    /******************************************************************************************
     * Functions for handling Contacts
     ******************************************************************************************/


    /**
     * Adds a contact to Infusionsoft
     * @param $fields Fields of user meta to change. In form of [infusionsoft-key] => [usermeta name]
     */
    public function addInfusionsoftContact( $userId = null, $fields = array(), $priority = true ) {
        if( !$userId )
            $userId = self::$userId;

        $contactId = self::getInfusionsoft()->addContact( $fields, $priority );
        # todo: @tsdb added ts_contact_create
        #\TSDBObj::class->ts_contact_create($fields['FirstName'], $fields['LastName'],$fields['Email']);
        if( $contactId ) {
            update_user_meta( $userId, 'infusionsoft_id', $contactId );
            self::$contactId = $contactId;
        }
    }


    /******************************************************************************************
     * Tag handling functions
     ******************************************************************************************/


    /**
     * Adds Tags to an object (like a user or custom post)
     * Sanitizes Tags before saving it
     * @param $objectId
     * @param array $tags
     * @param bool $append      Append tags to existing ones useful for adding additional single tags.
     *                          Vs replacing all of them, which is more useful for updating a bunch at a time.
     *
     * @return array|\WP_Error
     */
    public static function addObjTags( $objectId, $tags = [], $append = false ) {
        if( !is_array($tags) )
            $tags = [ $tags ];
        $taxonomy = 'tag-category';
        # sanitize the tags before saving
        $sanitizedTags = array_map( 'sanitize_title', $tags );

        $objs = wp_set_object_terms( $objectId, $sanitizedTags, $taxonomy, $append );
        // Save the data
        clean_object_term_cache( $objectId, $taxonomy );

        return $objs;
    }


    /**
     * Add tags to a certain user
     * @param array $tags
     *
     * @return array|\WP_Error
     */

    public static function addUserTags( $tags = [], $append = false ) {
        return static::addObjTags( static::$userId, $tags, $append );
        # todo: @tsdb
    }


    /**
     * General function for pulling an object's Tags - this does a lot of the magic
     * @param $category
     *
     * @return array|\WP_Error
     */
    public static function getObjTags( $objectId, $category = null, $limit = 0 ) {
        $args = [
            'order' => 'ASC',
            'orderby' => 'name'
        ];
        # todo maybe add $args parameter
        #$args = wp_parse_args( $args, $defaults );


        if( $category ) {
            if( is_int($category) ) {
                $args['parent'] = $category;
            } else {
                $cat = get_term_by( 'name', $category, 'tag-category' );
                $args['parent'] = $cat->term_id;
            }
        }

        $terms = wp_get_object_terms( $objectId, 'tag-category', $args );
        # todo: @tsdb
        if( $terms && $limit > 0 )
            return array_slice( $terms, 0, $limit );
        else
            return $terms;

    }


    /**
     * Returns the object's (video/career/etc) strengths (3) from Tags taxonomy
     * @param bool $returnAsIds     Returns as an array of IDs, else associative array
     * @return array                Returns all the strength Tags
     */
    public static function getObjStrengths( $objectId, $limit = 3 ) {
        $strengths = self::getObjTags( $objectId, self::$strengthMetaKey, $limit );
        $return = [];

        foreach( $strengths as $strength )
            $return[ $strength->term_id ] = $strength->name;

        return $return;
    }


    /**
     * General function for pulling User Tags from WP
     * @param $category
     *
     * @return array|\WP_Error
     */
    public static function getUserTags( $category = null, $limit = 0  ) {
        return self::getObjTags( self::$userId, $category, $limit );
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
        # todo: @tsdb
        return self::getInfusionsoft()->getUserTagsByCategory( $category, $contactId );

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


    /**
     * Returns the user's strengths from Tags taxonomy
     * @param int $limit        Number of strength Tags to return
     * @param bool $priority    Whether to sort by the strength's score/priority
     *
     * @return array            Returns all the strength Tags
     */
    public static function getUserStrengths( $limit = 3, $priority = true ) {

        # todo: make this more efficient possibly?

        if( isset(self::$userStrengths) && $limit == self::$userStrengthsLimit ) {
            return self::$userStrengths;
        } else {


            $strengths = self::getUserTags( self::$strengthMetaKey );
            $return = [];

            if( $priority ) {
                $strengthsIds = [];

                for( $i = 1; $i <= $limit; $i++ ) :
                    $key = 'strength_' . $i;
                    $strengthId = $strengthsIds[] = intval( get_user_meta( self::$userId, $key, true ) );

                    if( $strengthId && !empty($strengthId ) ) {
                        # create our $return array here
                        foreach ( $strengths as $strength ) {
                            # if the current strength matches the metakey's strength
                            if( $strengthId == $strength->term_id ) {
                                $return[ $strength->term_id ] = $strength->name;
                                break;
                            }
                        }
                    }
                endfor;
            }


            if( !$priority || ( $priority && empty($return) ) ) {
                $i = 0;
                foreach ( $strengths as $strength ) {
                    if( $i == $limit )
                        break;
                    $return[ $strength->term_id ] = $strength->name;
                    $i++;
                }
            }

            # cache it in a variable for multiple use on a page.
            if( !empty($return) )
                self::$userStrengths = $return;

            return $return;
        }
    }


    /**
     * Function responsible for career cards on dashboard
     * @param int $limit
     *
     * @return array    Array of careers that match all 3 strengths
     */
    public static function getUserMatchingCareers( $limit = 5 ) {
        $strengths = array_keys( self::getUserStrengths(2) );

        if( empty( $strengths ) )
            return null;

        $careers = static::getPosts( 'career', [
            'posts_per_page' => $limit,
            'tax_query'      => static::getTaxQuery( $strengths )
        ]);

        return $careers;

    }


    /**
     * Grab all of a single User's Tags from IS, and save to WP
     * @return array|\WP_Error
     */
    public static function updateUserTags() {
        $taxonomy = 'tag-category';


        # @todo: replace this with self::updateAllUserTags()
        $tags = Infusionsoft::get_instance()->getTagsByContactId( static::$contactId );
        # todo: @tsdb
        $tags2 = [];

        foreach( $tags as $tag )
            $tags2[] = $tag['GroupName'];

        $sanitizedTags = array_map( 'sanitize_title', $tags2 );


        $objs = wp_set_object_terms( static::$userId, $sanitizedTags, $taxonomy, false );
        // Save the data
        clean_object_term_cache( static::$userId, $taxonomy );

        self::updateUserRole();

        return $objs;

    }


    /**
     * Updates the user's role to marketplace_user, if they're still a regular user and completed all Assessments
     */
    public static function updateUserRole() {
        # if they're a regular user, check to see if they completed all the assessments
        if( static::userHasRole( 'subscriber') ) {
            #upgrade their role if completed all Assessments
            if( Assessments::hasUserCompletedAssessments() ) {
                wp_update_user( [
                    'ID' => self::$user->ID,
                    'role' => 'marketplace_user'
                ] );
            }
        }

    }
}

add_action( 'init', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
