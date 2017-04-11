<?php
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

use ThinkShift\Plugin\Cron,
    ThinkShift\Plugin\Base,
    ThinkShift\Plugin\Users;


$users = Base::getPosts( 'user' );



# delete strength metadata for testing
# $query = $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key IN ('strength_1', 'strength_2', 'strength_3');" );


# todo:

# low priority - update all user's tags

# high  priority - update select users only
# - check for users that need update (via metakey)
# - also check/update if not activated yet


# update IS with user's personal info
foreach( $users as $user ) {

    Users::setUserId( $user->ID );
    Users::updateUserTags();

    # @todo: update IS with WP's names/email/metadata
    # \iSDK::dsUpdate($tName, $id, $iMap)

}

