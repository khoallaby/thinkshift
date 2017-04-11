<?php
namespace ThinkShift\Plugin;


class UserAuthentication extends Users {
    public function init() {

        #add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );


        # After registration, we update the first/lastname fields
        add_action( 'user_register', [ $this, 'user_register' ], 9 );
    }





    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/




    /**
     * After successful registration, adds our custom registration meta, (first/last name)
     */
    public function user_register( $userId ) {
        if( $user = get_user_by('ID', $userId ) ) {

            $nickname = $_POST['first_name'] . ' ' . $_POST['last_name'];

            # update usermeta
            $updateUserId = wp_update_user( [
                'ID' => $userId,
                'display_name' => $nickname,
                'nickname' => $nickname,
                'user_nicename' => $nickname,
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                #'user_login' => $user->user_email # updating user_login not allowed
            ] );

            # can't save email as user_login before activating
            #self::updateUserLogin( $userId, $user->user_email );


        }
    }


}

add_action( 'init', array( \ThinkShift\Plugin\UserAuthentication::get_instance(), 'init' ));
