<?php
namespace ThinkShift\Plugin;


class UserAuthentication extends Users {
    public function init() {

        #add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );


        # After registration, we update the first/lastname fields
        add_action( 'user_register', [ $this, 'user_register' ], 9 );

        # redirect normal users to homepage (on login)
        add_filter( 'login_redirect', [ $this, 'login_redirect' ], 10, 3 );

        # checks for user role and redirects accordingly
        add_action( 'wp', array( $this, 'userCanAccess' ) );
    }





    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/

    /**
     * Redirect normal users to homepage
     *
     * @param string $redirect_to URL to redirect to.
     * @param string $request URL the user is coming from.
     * @param object $user Logged user's data.
     * @return string
     */
    function login_redirect( $redirect_to, $request, $user ) {
        //is there a user to check?
        if ( isset( $user->roles ) && is_array( $user->roles ) ) {
            //check for admins
            #vard($user->roles); die();
            if ( in_array( 'administrator', $user->roles ) ) {
                // redirect them to the default place
                return admin_url();
            } else {
                return $redirect_to;
                #return home_url();
            }
        } else {
            return $redirect_to;
        }
    }



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
        # these pages are always visible
        if( is_front_page() || is_home() || is_page( 'login' ) || is_page( 'register' ) || is_page_template( 'template-external.php')) :
            return true;
        elseif( is_user_logged_in()) :
            return true;
        else :
            return false;
        endif;

    }
}

add_action( 'init', array( \ThinkShift\Plugin\UserAuthentication::get_instance(), 'init' ));
