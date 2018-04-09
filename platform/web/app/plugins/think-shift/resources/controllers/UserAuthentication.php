<?php
namespace ThinkShift\Plugin;


class UserAuthentication extends Users {
    # name of user capability that allows access to the marketplace
    public static $marketplaceAccess = 'marketplace_access';

    public function init() {

        #add_action( 'wp_login', array( $this, 'wp_login' ), 20, 2 );


        # After registration, we update the first/lastname fields
        add_action( 'user_register', [ $this, 'user_register' ], 9 );

        # redirect normal users to homepage (on login)
        add_filter( 'login_redirect', [ $this, 'login_redirect' ], 10, 3 );


        # redirect overrides on certain pages
        add_action( 'wp', [ $this, 'userRouteHome' ], 50 );
        add_action( 'wp', [ $this, 'userRouteRegister' ], 60 );

        # checks for user role and redirects accordingly
        add_action( 'wp', [ $this, 'userCanAccess' ], 70 );



        # user login form actions
        add_action( 'login_form_login', [ $this, 'redirect_to_custom_login' ] );
        add_filter( 'authenticate', [ $this, 'maybe_redirect_at_authenticate' ], 101, 3 );


        # blocks access to wp-admin
        add_action( 'admin_init', [ $this, 'blockAdminAccess' ], 100 );


        register_activation_hook( thinkshift_plugin, [ $this, 'addUserRoles' ] );
        register_deactivation_hook( thinkshift_plugin, [ $this, 'removeUserRoles' ] );


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
            if ( in_array( 'administrator', $user->roles ) ) {
                // redirect them to the default place
                return admin_url();
            } elseif ( in_array( 'subscriber', $user->roles ) ) {
                // redirect them to assessments
                return get_post_type_archive_link( 'assessment' );
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
                'role' => 'regular_user'
                #'user_login' => $user->user_email # updating user_login not allowed
            ] );

            # @todo: try querying
            # can't save email as user_login before activating
            #self::updateUserLogin( $userId, $user->user_email );


            return $updateUserId;
        }
    }


    /**
     * The action to check for user permissions and redirect to login if not.
     */
    public function userCanAccess() {
        if( !$this->userRoutes() )
            wp_redirect( home_url( '/' ) );
    }



    /**
     * Determines what the user can see depending on page and logged in status
     * @return bool
     */
    public function userRoutes() {
        # these pages are always visible
        if( is_front_page() || is_home() || is_page( 'login' ) || is_page( 'register' ) ||
            is_page_template( 'template-external.php') ||
            is_post_type_archive( 'video' ) || is_singular( 'video' ) ||
            is_post_type_archive( 'resource' ) || is_singular( 'resource' )
        ) {
            return true;
        } elseif( is_user_logged_in()) {
            # assessments are always available to logged in users
            if( is_post_type_archive( 'assessment' ) || is_singular( 'assessment' ) )
                return true;
            # everything else needs marketplace access
            else
                return current_user_can( self::$marketplaceAccess );
        } else {
            return false;
        }

    }



    /******************************************************************************************
     * Routing/redirects for certain pages
     ******************************************************************************************/


    /**
     * Determines what should happen on the home page, '/'
     * If not marketplace user, will be redirected to assessments
     */
    public function userRouteHome() {
        if( is_user_logged_in() ) {
            if( is_front_page() || is_page( 'register' ) || is_page( 'login' )) {
                # redirect regular users to /assessments
                if ( ! current_user_can( self::$marketplaceAccess ) )
                    wp_redirect( home_url( '/assessments/' ) );
            }
        }
    }


    /**
     * Fixes #108 - redirects register page to itself with trailing slash
     */
    public function userRouteRegister() {
        if( $_SERVER['REQUEST_URI'] == '/register' ) {
            if( function_exists( 'bp_core_redirect' ) )
                bp_core_redirect( home_url( '/register/' ) );
            else
                # wp_redirect backup as this issue is prob due to BP anyways
                wp_redirect( home_url( '/register/' ) );
        }
    }


    # blocks access to wp-admin
    # https://wordpress.stackexchange.com/questions/66093/how-to-prevent-access-to-wp-admin-for-certain-user-roles
    public function blockAdminAccess() {
        $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );

        if( !static::userHasRole( 'administrator' ) )
            exit( wp_redirect( $redirect ) );

    }






    /******************************************************************************************
     * Login form actions
     * https://code.tutsplus.com/tutorials/build-a-custom-wordpress-user-flow-part-1-replace-the-login-page--cms-23627
     ******************************************************************************************/

    /**
     * Redirect the user to the custom login page instead of wp-login.php.
     */
    function redirect_to_custom_login() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;

            if ( is_user_logged_in() ) {
                $this->redirect_logged_in_user( $redirect_to );
                exit;
            }

            // The rest are redirected to the login page
            $login_url = home_url( 'login' );
            if ( ! empty( $redirect_to ) ) {
                $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
            }

            wp_redirect( $login_url );
            exit;
        }
    }

    /**
     * Redirects the user to the correct page depending on whether he / she
     * is an admin or not.
     *
     * @param string $redirect_to   An optional redirect_to URL for admin users
     */
    private function redirect_logged_in_user( $redirect_to = null ) {
        $user = wp_get_current_user();
        if ( user_can( $user, 'manage_options' ) ) {
            if ( $redirect_to ) {
                wp_safe_redirect( $redirect_to );
            } else {
                wp_redirect( admin_url() );
            }
        } else {
            wp_redirect( home_url( 'login' ) );
        }
    }



    /**
     * Redirect the user after authentication if there were any errors.
     *
     * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
     * @param string            $username   The user name used to log in.
     * @param string            $password   The password used to log in.
     *
     * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
     */
    function maybe_redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter (most likely,
        // the default WordPress authentication) functions have found errors
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );

                $login_url = home_url( 'login' );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );

                wp_redirect( $login_url );
                exit;
            }
        }

        return $user;
    }

    /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $error_code    The error code to look up.
     *
     * @return string               An error message.
     */
    public function get_error_message( $error_code ) {
        switch ( $error_code ) {
            case 'empty_username':
                return __( 'Email address required', 'thinkshift' );

            case 'empty_password':
                return __( 'Password required', 'thinkshift' );

            case 'invalid_username':
                return __( 'Invalid email address entered', 'thinkshift' );

            case 'incorrect_password':
                $err = __( 'Invalid password entered', 'thinkshift' );
                return $err;
                #return sprintf( $err, wp_lostpassword_url() );

            default:
                break;
        }

        return __( 'Sorry, incorrect username or password', 'thinkshift' );
    }














    # https://www.sitepoint.com/mastering-wordpress-roles-and-capabilities/
    public function addUserRoles() {
        $customCaps = [
            'read' => true,
            self::$marketplaceAccess => true
        ];

        add_role( 'marketplace_user', __( 'Marketplace User', 'thinkshift'), $customCaps );
        #add_role( 'regular_user', __( 'Regular User', 'thinkshift'), [ self::$marketplaceAccess => false ] );

        // Add custom capabilities to Admin and Editor Roles
        $roles = array( 'administrator', 'editor' );
        foreach ( $roles as $roleName ) {
            // Get role
            $role = get_role( $roleName );

            // Check role exists
            if ( is_null( $role) )
                continue;

            // Iterate through our custom capabilities, adding them
            // to this role if they are enabled
            foreach ( $customCaps as $capability => $enabled ) {
                if ( $enabled )
                    $role->add_cap( $capability );
            }
        }


        #$role = add_role('pre_marketplace', 'Pre Marketplace', array());
        #$role->add_cap('install_plugins');
    }



    public function removeUserRoles() {
        remove_role( 'marketplace_user' );
        #remove_role( 'regular_user' );
    }
}

add_action( 'init', array( \ThinkShift\Plugin\UserAuthentication::get_instance(), 'init' ));
