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

        # checks for user role and redirects accordingly
        add_action( 'wp', array( $this, 'userCanAccess' ) );



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

            # can't save email as user_login before activating
            #self::updateUserLogin( $userId, $user->user_email );


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
            # assessments are always available to loggered in users
            if( is_post_type_archive( 'assessment' ) || is_singular( 'assessment' ) )
                return true;
            # everything else needs marketplace access
            else
                return current_user_can( self::$marketplaceAccess );
        } else {
            return false;
        }

    }



    # blocks access to wp-admin
    # https://wordpress.stackexchange.com/questions/66093/how-to-prevent-access-to-wp-admin-for-certain-user-roles
    public function blockAdminAccess() {
        $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );

        if( !static::userHasRole( 'administrator' ) )
            exit( wp_redirect( $redirect ) );

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
