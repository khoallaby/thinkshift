<?php
namespace ThinkShift\Plugin;


class BuddyPress extends Users {
    public function init() {

        #add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );

        #buddypress on user register
        add_action( 'user_register', array( $this, 'user_register' ), 20 );
    }





    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/


    /**
     * Hook into BP, after registration process is done
     */
    public function user_register( $user_id ) {
        if( $user = get_user_by('ID', $user_id ) ) {

            # update usermeta
            $updateUserId = wp_update_user( [
                'ID' => $user_id,
                'display_name' => $_POST['first_name'] . ' ' . $_POST['last_name'],
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                #'user_login' => $user_email # updating user_login not allowed
            ] );

            $updateEmail = static::updateUserLogin( $user_id, $user->user_email );







        }
    }






}

add_action( 'init', array( \ThinkShift\Plugin\BuddyPress::get_instance(), 'init' ));
