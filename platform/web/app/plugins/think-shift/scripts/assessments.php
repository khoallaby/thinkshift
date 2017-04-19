<?php
require(__DIR__ . '/../../../../wp/wp-load.php');

use ThinkShift\Plugin\Users;


$file = 'assessments.log';
$debug = true;



# @todo: allow access only if have a password/token
# test by replacing the $_POST variables
/*
$_POST = [
    'user_email' => 'email@ddress.com',
    'status' => 'completed-dream-declaration'
    #'status' => 'completed-motivated-abilities-assessment'
];
*/




$user = get_user_by( 'email', $_POST['user_email'] );
$txt = '[' . date('m/d/Y h:i:s a', time()) . "]\n";
$txt .= print_r($_POST, true) ."\n";


if( $user ) {
    if( $_POST['status'] ) {
        $tags = [ $_POST['status'] ];

        # check for strengths and add them
        if( isset($_POST['strengths']) &&
            !empty($_POST['strengths']) &&
            $_POST['status'] == 'completed-motivated-abilities-assessment' ) {

            $strengths = explode( ',', urldecode($_POST['strengths']) );
            $tags = array_merge( $tags, $strengths );

            $txt .= 'Adding strengths: ' . urldecode($_POST['strengths']) . "\n";
        }
        Users::setUserId( $user->ID );
        Users::addUserTags( $tags, true );
    }
} else {
    $txt .= 'User not found: ' . $_POST['user_email'] . "\n";
}




# write to our log file
if( $debug ) {
    # make log file writeable if not
    if( !is_writable($file) )
        chmod( $file, 0777 );
    file_put_contents( $file, $txt, FILE_APPEND );
}
