<?php
require dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

$fileMap = 'jobs-map.json';
$file = 'jobs.xlsx';

$importer = \ThinkShift\Plugin\Importer::get_instance();


$importer::setKeys( dirname(__FILE__) . '/' . $fileMap );

$numberPostsImported = $importer::importCareersIntoCpt( dirname(__FILE__) . '/' . $file );


flush_rewrite_rules();

# echos out header in array format
#echo $importer::getHeaders( dirname(__FILE__) . '/' . $file, true );