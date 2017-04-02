<?php

/**
 * Plugin Name: Think Shift Plugin
 * Plugin URI: http://www.domain.tld
 * Description: Communicates with Infusionsoft
 * Version: 1.0.0
 * Author: Think Shift
 * Author URI: http://tsdevserver.com/
 * Text Domain: ts
 * Domain Path: /languages
 */


define('thinkshift_plugin_url',plugin_dir_url(__FILE__ ));
define('thinkshift_plugin_path',plugin_dir_path(__FILE__ ));

# todo: build importer pls
require_once( dirname(__FILE__) . '/resources/controllers/Base.php');
require_once( dirname(__FILE__) . '/resources/controllers/CustomPostTypes.php');
require_once( dirname(__FILE__) . '/resources/controllers/Infusionsoft.php');
require_once( dirname(__FILE__) . '/resources/controllers/Users.php');
require_once( dirname(__FILE__) . '/resources/controllers/Shortcodes.php');
require_once( dirname(__FILE__) . '/resources/controllers/Importer.php');


function vard($s) {
    echo '<pre>';
    var_dump($s);
    echo '</pre>';
}