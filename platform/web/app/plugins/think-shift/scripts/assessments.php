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
    #'status' => 'completed-dream-declaration',
    'status' => 'completed-motivated-abilities-assessment',
    'strength_1' => 'Generating Insight',
    'strength_2' => 'Serving',
    'strength_3' => 'Collaborating',
];
*/


$user = get_user_by( 'email', $_POST['user_email'] );
$txt = '[' . date('m/d/Y h:i:s a', time()) . "]\n";
$txt .= print_r($_POST, true) ."\n";


if( $user ) {
    # add the completed status tag
    $tags = [ $_POST['status'] ];

    # check to see if the 3 strengths were POSTed
    for( $i = 1; $i <= 3; $i++ ) :
        $key = 'strength_' . $i;
        # check for strengths and add them
        if( isset($_POST[ $key ]) &&
            !empty($_POST[ $key ]) &&
            $_POST['status'] == 'completed-motivated-abilities-assessment' ) {

            $strength = urldecode( $_POST[ $key ] );
            $tags[] = $strength;

            $term = get_term_by( 'name', $strength, 'tag-category' );
            update_user_meta( $user->ID, $key, $term->term_id );

            $txt .= 'Adding strength: ' . $strength . "\n";
        }
    endfor;

    Users::setUserId( $user->ID );
    Users::addUserTags( $tags, true );

    if( $_POST['status'] == 'completed-pivot-power-tool' )
        Users::updateUserRole();


} else {
    $txt .= 'User not found: ' . $_POST['user_email'] . "\n";
}




# write to our log file
if( $debug ) {
    # make log file writeable if not
    #if( !is_writable($file) )
    #    chmod( $file, 0777 );
    file_put_contents( $file, $txt, FILE_APPEND );
}
