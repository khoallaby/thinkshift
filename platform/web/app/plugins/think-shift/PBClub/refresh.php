<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/17/2017
 * Time: 9:51 PM
 */

require_once 'src/isdk.php';
require_once 'LogFileObj.php';
require_once 'awsconfig.php';
$app=new iSDK();
$log=new LogFileObj('OAuth2.log');

function doRefresh()
{
global $app, $log, $host, $awsuser, $password, $database, $port;

    // make the db connection
    $con = mysqli_connect($host, $awsuser, $password, $database, $port);

    // Check connection
    if (mysqli_connect_errno()) {
        echo mysqli_connect_errno();
    };

    $query = "SELECT * FROM OAuth2;";
    $result = mysqli_query($con, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $log->lfWriteLn('*************************************************************************************');
    $log->lfWriteLn('Result of MySQL query = ' . count($rows) . ' entries :');

    foreach ($rows as $row) {

        $id = $row['Id'];
        $log->lfWriteLn('     Id = ' . $id);
        $DT = $row['GeneratedDate'];
        $log->lfWriteLn('     GeneratedDate = ' . $DT);
        $AppName = $row['AppName'];
        $log->lfWriteLn('     AppName = ' . $AppName);
        $Access = $row['AccessToken'];
        $log->lfWriteLn('     AccessToken = ' . $Access);
        $Refresh = $row['RefreshToken'];
        $log->lfWriteLn('     RefreshToken = ' . $Refresh);
        $now = time();
        $log->lfWriteLn('Calculating date/time differential :');
        $d = new DateTime($DT, DateTimeZone::AMERICA['America/Los_Angeles']);

        $then = $d->getTimestamp();
        $log->lfWriteLn('     Tokens creation date = ' . $then);
        $log->lfWriteLn('     Date/Time now = ' . $now);

        $diff = 86400 - ($now - $then);
        $log->lfWriteLn('     Differential = ' . $diff);

        if ($diff <= 14400) { // 14400 seconds = 4 hours

            $log->lfWriteLn('Refreshing tokens :');
            $app->setClientId('9sbtkn2vfjrr7cp93yaswgpq');
            $app->setSecret('St9WnkKkk8');
            $app->setTokenEndpoint('https://api.infusionsoft.com/token');
            $app->setToken($Access);
            $app->setRefreshToken($Refresh);

            $new = $app->refreshAccessToken();

            $dat = json_decode($new);

            $now = date("Y-m-d H:i:s", time());
            $log->lfWriteLn('     New AccessToken = ' . $dat->access_token);
            $log->lfWriteLn('     New RefreshToken = ' . $dat->refresh_token);
            $query = "UPDATE OAuth2 SET AccessToken='$dat->access_token', RefreshToken='$dat->refresh_token', GeneratedDate='$now' WHERE AppName='$AppName';";
            $log->lfWriteLn('MySQL Query :');
            $log->lfWriteLn('     ' . $query);
            $result = mysqli_query($con, $query);
            $log->lfWriteLn('MySQL DB update results = ' . $result);
            echo date('m-d-Y H:i:s    Tokens renewed.');
        } else {
            $log->lfWriteLn('Token refresh not attempted.');
            echo date('m-d-Y H:i:s    Checked but not processed.');
        }
    }
}

doRefresh();