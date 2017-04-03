<?php
require dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';
$file = 'jobs.xlsx';

$importer = \ThinkShift\Plugin\Importer::get_instance();


$numberPostsImported = $importer::importCareersIntoCpt( dirname(__FILE__) . '/' . $file );

# echos out header in array format
#echo $importer::getHeaders( dirname(__FILE__) . '/' . $file, true );