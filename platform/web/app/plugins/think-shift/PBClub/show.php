<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/18/2017
 * Time: 9:46 AM
 */

require_once 'src/isdk.php';
$app=new iSDK();

$app->setClientId('9dmnsy787g6h9gzx5rbp69k6');
$app->setSecret('Fu9mSPXBuB');
$app->setTokenEndpoint('https://api.infusionsoft.com/token');
$app->setToken('hk3ztwfac3rjxp6dsxk96zzt');
$app->setRefreshToken('srm8ja5brj8km3w9r3ketvg8');

$new=$app->refreshAccessToken();

var_dump($new);