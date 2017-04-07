<?php
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';

/**
 * Imports all Tags and Tag Categories into CPT
 */
$tags = \ThinkShift\Plugin\Cron::saveAllTagsFromInfusionsoft();
