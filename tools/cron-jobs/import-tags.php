<?php
error_reporting(-1);
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

use ThinkShift\Plugin\Cron;

/**
 * Imports all Tags and Tag Categories into tag-category taxonomy
 */

echo '[' . DateTime::ATOM . "] Running... tag importer... \n\n\n";


$tags = Cron::saveAllTagsFromInfusionsoft();
