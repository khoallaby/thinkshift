<?php
require("OAuth2-iSDK/src/isdk.php");

include_once 'LogFileObj.php';
include_once 'keepTokensConfig.php';

$app = new iSDK();
$log=new LogFileObj('keepTokens.log');

if (!isset($_GET['code'])) {
    $app->setClientId(CLIENT_ID);
    $app->setSecret(CLIENT_SECRET);
    $app->setRedirectURL(REDIRECT_URL . REDIRECT_FILENAME);
    $log->lfWriteLn('Regenerating tokens.  tokenStart.php executed.');
    echo "<p>Click the link below to allow access to your Infusionsoft application.</p>";
    echo '<a href="' . $app->getAuthorizationURL() . '">Authorize My Application</a>';
} else {
    $app->setClientId(CLIENT_ID);
    $app->setSecret(CLIENT_SECRET);
    $app->setRedirectURL(REDIRECT_URL . REDIRECT_FILENAME);
    $auth=null;
    $auth=$app->authorize($_GET['code']);
    $log->lfWriteLn('Retrieved tokens : access_token='.$auth->access_token.', refresh_token='.$auth->refresh_token);
    // authorize() returns a php object rather than a json string so request with refreshAccessToken()
    $refresh=$app->refreshAccessToken();
    $log->lfWriteLn('Access and refresh tokens retrieved :');
    $log->lfWriteLn('     ='.$refresh);
    $file=fopen(SAVE_FILENAME,'w+');
    fwrite($file,$refresh);
    fclose($file);
    echo '<b><h1>Your tokens have been generated.  <br>See keepTokens.tok and keepTokens.log for details.</h1></b>';
}