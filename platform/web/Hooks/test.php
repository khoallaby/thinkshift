<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/24/2017
 * Time: 12:00 AM
 */

function getHooksUrl()
{
    $file = fopen('../../.env', 'r');

    while (!feof($file)) {
        $line = fgets($file);
        $p = strpos($line, 'WP_HOME');
        if ((is_int($p)) && ($p == 0)) break;
    }
    $url = substr($line, 8, -1).'/Hooks/';
    return $url;
}

echo getHooksUrl().chr(10);