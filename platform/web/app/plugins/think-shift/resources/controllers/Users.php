<?php
namespace ThinkShift\Plugin;


class Users extends Base {
    private static $infusionsoft, $contactId, $user, $userId;


    public function init() {
        parent::init();

        self::$infusionsoft = new \ThinkShift\Plugin\Infusionsoft();
        self::$contactId = self::getContactId();
        self::setUserId();

        add_action( 'wp_login', array( $this, 'wpLogin' ), 20, 2 );
        //add_action( 'wp_register', array( $this, 'wpRegister' ), 1 );



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
        } elseif( is_user_logged_in() ) {
            self::$userId = self::$user->ID;
            self::$user = get_current_user();
        }

        self::$contactId = get_user_meta( self::$userId, 'infusionsoft_id', true );
    }


    /**
     * Retrieves Infusionsoft's contact ID, stored in usermeta
     * @return int Infusionsoft's contact ID
     */
    public static function getContactId() {
        if( !self::$contactId ) {
            $contactId = get_user_meta( self::$userId, 'infusionsoft_id', true );
        } else {
            # todo: check on IS
            $contactId = null;
        }
        return (int) $contactId;

    }


    /**
     * Runs on (successful?) user Log in
     * @param $user_login
     * @param $user
     */
    public function wpLogin( $user_login, $user ) {
        #if ( current_user_can( 'subscriber' ) ) {
        $fields = $this->parseFields( $user->ID );
        $this->addInfusionsoftContact( $user->ID, $fields );
        #}
    }


    /**
     * Runs on successful user registration
     */
    public function wpRegister() {
        $fields = $this->parseFields( self::$userId );
        $this->addInfusionsoftContact( self::$userId, $fields );
    }


    /**
     * Parses data from our user and compiles it to the right field names. Then sends it to infusionsoft
     * @return array Fields with [infusionsoft-key] => [usermeta name]
     */
    public function parseFields() {
        # if regular login
        #if( true ) {
        $userMeta = get_userdata( self::$userId );
        $fields = array(
            'FirstName' => $userMeta->first_name,
            'LastName' => $userMeta->last_name,
            'Email' => $userMeta->user_email
        );
        #}
        # todo: add parse for social media logins

        return $fields;

    }


    /**
     * Adds a contact to Infusionsoft
     * @param $fields Fields of user meta to change. In form of [infusionsoft-key] => [usermeta name]
     */
    public function addInfusionsoftContact( $fields = [] ) {
        $contactId = self::$infusionsoft->addContact( $fields );

        if( $contactId ) {
            update_user_meta( self::$userId, 'infusionsoft_id', $contactId );
            self::$contactId = $contactId;
        }

    }


    /**
     * Gets all the tags and categories for a user
     * @return array|bool Array of matching tag/categories
     */
    public static function getUserTags( ) {
        $contactId = self::getContactId();

        # todo: save tags somewhere later
        if( $contactId )
            return self::$infusionsoft->getTagsByContactId( $contactId );
        else
            return false;

    }




}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
