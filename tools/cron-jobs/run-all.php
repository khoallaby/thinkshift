<?php
require_once dirname(__FILE__) . '/../../platform/web/wp/wp-load.php';


# imports all the tags and category names
require 'import-tags.php';


# communicates with IS. pull/update user's metadata
require 'update-users.php';
