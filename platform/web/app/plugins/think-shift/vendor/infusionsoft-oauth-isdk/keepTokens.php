<?php
/**
 * keepTokens should be run on a schedule to keep token alive
 * August 17th, 2016
 * Author : John Borelli
 * Company : The Plan B Club, LLC
 * Client : AZ Naturals
 */

include_once('keepTokensConfig.php');
date_default_timezone_set(TIME_ZONE);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
include_once('LogFileObj.php');

require_once 'OAuth2-iSDK/src/isdk.php';
$app=new iSDK();

$log=new LogFileObj('keepTokens.log');

if (!file_exists(SAVE_FILENAME)){
    // if the save file does not exist then it is assumed that the token does not either
    $log->lfWriteLn('Token file missing.  Manual authorization required.  Use tokenStart.php in a browser.');
} else {
    $app->setClientId(CLIENT_ID);
    $app->setSecret(CLIENT_SECRET);
    $app->setRedirectURL(REDIRECT_URL.REDIRECT_FILENAME);

    $file=fopen(SAVE_FILENAME,'r');
    $data=fgets($file);
    fclose($file);
    $dat=json_decode($data);
    $return=$app->refreshAccessToken($dat->refresh_token);
    $log->lfWriteLn('keepTokens.php generated new access/refresh token data.');
    $log->lfWriteLn('     ='.$return);
    $file=fopen(SAVE_FILENAME,'w');
    fwrite($file,$return);
    fclose($file);
}
