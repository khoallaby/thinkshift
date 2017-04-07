<?php
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

use ThinkShift\Plugin\Cron as Cron,
    ThinkShift\Plugin\Base as Base;


$users = Base::getPosts( 'user' );



# delete strength metadata for testing
# $query = $wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key IN ('strength_1', 'strength_2', 'strength_3');" );


foreach( $users as $user ) {

    Cron::setUserId( $user->ID );
    Cron::updateUserStrengths();

    # @todo: update IS with WP's names/email/metadata
    # \iSDK::dsUpdate($tName, $id, $iMap)

}


