<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/17/2017
 * Time: 1:30 AM
 */

include_once 'LogFileObj.php';
include_once 'keepTokensConfig.php';
require_once 'src/isdk.php';

$app = new iSDK();
$log=new LogFileObj('keepTokens.log');

$app->setPostURL("https://api.infusionsoft.com/crm/xmlrpc/v1");
$app->setToken("taendzys9af5z3tzfwnxuxkc");


$data=$app->dsQuery('Contact',1000,0,array('Id'=>'%'),array('Id','FirstName','LastName','Email'));

var_dump($data);