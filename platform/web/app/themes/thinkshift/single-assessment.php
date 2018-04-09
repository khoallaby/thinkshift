<?php
use ThinkShift\Plugin\Assessments;


$statuses = Assessments::canAccess();

include( locate_template( 'templates/assessments/steps.php' ) );
include( locate_template( 'templates/content-single-' . get_post_type() . '.php' ) );


?>
