<?php
require dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';


$users = \ThinkShift\Plugin\Base::getPosts( 'user' );



# delete strength metadata for testing
# $query = $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key IN ('strength_1', 'strength_2', 'strength_3');" );


foreach( $users as $user ) {

    \ThinkShift\Plugin\Users::setUserId( $user->ID );
    \ThinkShift\Plugin\Users::updateUserStrengths();

    # @todo: update IS with names/email/metadata
    # \iSDK::dsUpdate($tName, $id, $iMap)

}


