<?php
namespace ThinkShift\Plugin;

use BP_Signup,
    WP_User;

class BuddyPress extends Users {
    public function init() {

        #add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );


        # remove buddypress admin bar
        add_action( 'wp', [ $this, 'removeBpAdminBar' ] );


        /**
         * registration hooks
         */


        # removes the WP social login plugin before the fields show
        remove_action( 'bp_before_account_details_fields', 'wsl_render_auth_widget_in_wp_login_form' );

        # On successful registration
        # adds random hash to user_name
        add_action( 'bp_signup_pre_validate', [ $this, 'bp_signup_pre_validate' ] );
        # redirects to home
        add_filter( 'bp_core_signup_user', [ $this, 'bp_core_signup_user' ], 1000, 5 );

        # after user activation
        #add_action( 'bp_signup_validate', [ $this, 'bp_signup_validate' ] );
        add_filter( 'bp_core_validate_user_signup', [ $this, 'bp_core_validate_user_signup' ] );
        #add_action( 'bp_core_activated_user', [ $this, 'bp_core_activated_user' ], 20, 3 );



        # do_action( 'bp_complete_signup' ); # after complete


        # #47 - disable activation
        #add_filter( 'authenticate', [ $this, 'authenticateLogin' ], 100, 3 );
        #add_filter( 'bp_registration_needs_activation', '__return_false' );
        add_filter( 'bp_registration_needs_activation', [$this, 'false'], 20 );
        add_filter( 'bp_core_signup_send_activation_key', '__return_false', 20 );


    }
    
    public function false( $status ) {
        #die($status);
        return false;
    }





    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/


    function bp_core_signup_user( $user_id, $user_login, $user_password, $user_email, $usermeta ) {
        global $wpdb;
        #$sql = "SELECT activation_key, user_login FROM {$wpdb->prefix}signups WHERE active = '0' and user_login = %s";
        #$signup = $wpdb->get_row( $wpdb->prepare( $sql, $user_email ) );

        # actual activation
        #bp_core_activate_signup($signup->activation_key);
        #BP_Signup::validate($signup->activation_key);


        #wp_set_current_user( $user_id, $user->user_login );
        #wp_set_auth_cookie( $user_id, true, is_ssl() );


        bp_core_redirect(home_url() );
        #wp_redirect( home_url() );
    }


    # Authenticates a user on login. Tries logging in unactivated users
    public function authenticateLogin( $user, $username, $password ) {
        # try logging in an unactivated account
        if( is_wp_error($user) && $user->get_error_code() == 'bp_account_not_activated' ) {
            $user = wp_authenticate_email_password(null, $username, $password);
        }

        return $user;
    }

    # Runs after all the default BP validation is done. Validates first/last name
    public function bp_signup_validate() {
        global $bp;
        if( !isset($_POST['first_name']) || empty($_POST['first_name']) )
            $bp->signup->errors['signup_first_name'] = __( 'Please enter a first name', 'buddypress' );
        if( !isset($_POST['last_name']) || empty($_POST['last_name']) )
            $bp->signup->errors['signup_last_name'] = __( 'Please enter a last name', 'buddypress' );
    }


    # adds something to the $_POST[user_name] so we can bypass the BP validation methods
    public function bp_signup_pre_validate() {
        $_POST['signup_username'] = isset($_POST['signup_email']) ? md5($_POST['signup_email']) : '';
    }


    # Sets the username as the email, after BP validation methods have ran
    public function bp_core_validate_user_signup( $results ) {
        $results['user_name'] = $results['user_email'];
        return $results;
    }


    # sets username after activation
     public function bp_core_activated_user(  $userId, $key, $user ) {
         global $wpdb;

         $user = get_user_by( 'id', $userId );
         $update = $wpdb->update( $wpdb->users,
             [ 'user_login' => $user->user_email ],
             [ 'ID' => $userId],
             [ '%s' ],
             [ '%d' ]
         );

         #echo $userId;

         #vard($update);
         #die($update);
         return $update;
     }



    # @todo: also remove the admin-bar body class.
    # remove buddypress admin bar
    function removeBpAdminBar() {
        if( !is_super_admin() )
            add_filter( 'show_admin_bar', '__return_false' );
    }


}

add_action( 'init', array( \ThinkShift\Plugin\BuddyPress::get_instance(), 'init' ));
