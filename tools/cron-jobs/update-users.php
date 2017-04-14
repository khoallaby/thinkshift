<?php
error_reporting(-1);
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

use ThinkShift\Plugin\Cron,
    ThinkShift\Plugin\Base,
    ThinkShift\Plugin\Users;
update_user_meta( 2, 'update_priority', 'high' );
$args = [];

# if argument set
if( isset($argv[1]) ) {
    $args['meta_query'] = [
        [
            'key' => 'update_priority',
            'value' => 'high'
        ]
    ];
}

$users = Base::getPosts( 'user', $args );
$priority = isset($argv[1]) ? '[ ' . $argv[1] . ' priority ]' : '';


echo '[' . DateTime::ATOM . '] Running... ' . $priority . ' update users... on ' . count($users) . " users... \n\n\n";


# todo:
# low priority - update all user's tags
# high  priority - update select users only
# - check for users that need update (via metakey)
# - also check/update if not activated yet


# update IS with user's personal info
foreach( $users as $user ) {

    Users::setUserId( $user->ID );
    Users::updateUserTags();

    # reset their update priority
    if( isset($argv[1]) )
        update_user_meta( $user->ID, 'update_priority', '' );

    # @todo: update IS with WP's names/email/metadata
    # \iSDK::dsUpdate($tName, $id, $iMap)

}

