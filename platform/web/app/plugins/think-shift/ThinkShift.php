<?php

/**
 * Plugin Name: Think Shift Plugin
 * Plugin URI: http://thinkshiftopportunity.org
 * Description: Does some random things for the site
 * Version: 0.3.1
 * Author: Think Shift
 * Author URI: http://wethinkshift.org
 * Text Domain: ts
 * Domain Path: /languages
 * Plugin Type: Piklist
 */


define( 'thinkshift_plugin', __FILE__  );
define( 'thinkshift_plugin_url',plugin_dir_url(__FILE__ ) );
define( 'thinkshift_plugin_path',plugin_dir_path(__FILE__ ) );

# todo: build importer/autoloader pls
require_once( dirname(__FILE__) . '/resources/controllers/Base.php');
require_once( dirname(__FILE__) . '/resources/controllers/CustomPostTypes.php');
require_once( dirname(__FILE__) . '/resources/controllers/Assessments.php');
require_once( dirname(__FILE__) . '/resources/controllers/Careers.php');
require_once( dirname(__FILE__) . '/resources/controllers/Resources.php');
require_once( dirname(__FILE__) . '/resources/controllers/Videos.php');
require_once( dirname(__FILE__) . '/resources/controllers/Infusionsoft.php');
require_once( dirname(__FILE__) . '/resources/controllers/Enqueue.php');
require_once( dirname(__FILE__) . '/resources/controllers/Users.php');
require_once( dirname(__FILE__) . '/resources/controllers/UserAuthentication.php');
require_once( dirname(__FILE__) . '/resources/controllers/Tags.php');
require_once( dirname(__FILE__) . '/resources/controllers/BuddyPress.php');
require_once( dirname(__FILE__) . '/resources/controllers/Shortcodes.php');
require_once( dirname(__FILE__) . '/resources/controllers/AdminUi.php');
require_once( dirname(__FILE__) . '/resources/controllers/Importer.php');
require_once( dirname(__FILE__) . '/resources/controllers/Cron.php');


function vard($s) {
    echo '<pre>';
    var_dump($s);
    echo '</pre>';
}