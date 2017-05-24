<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/21/2017
 * Time: 12:24 PM
 */

echo 'Infusionsoft webhook eraser.'.chr(10);
if ($argc!=2){
    echo '     Wrong number of parameters.'.chr(10);
    echo '     Use = php clearHooks.php [appname]'.chr(10);
    die();
}

$con = mysqli_connect("thinkshiftdataserver.czlkyoy9ghkh.us-east-1.rds.amazonaws.com",
    "awsthinkshift", "?Th1nksh1ft?", "thinkshiftdb", "3306");

$query="SELECT AccessToken FROM OAuth2 WHERE AppName='$argv[1]';";
$return=mysqli_query($con,$query);
$rows=mysqli_fetch_array($return);

$con->close();
$curl = curl_init();
$url="https://api.infusionsoft.com/crm/rest/v1/hooks/?access_token=".$rows['AccessToken'];

//$endpoint=$dir;

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "$url",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json"
    ),
));

$response = curl_exec($curl);

$list=json_decode($response);

foreach ($list as $key=>$l){
    echo 'Requesting delete for hook id '.$l->key.chr(10);
    $curl = curl_init();
    $send="https://api.infusionsoft.com/crm/rest/v1/hooks/".$l->key.'/?access_token='.$rows['AccessToken'];

    curl_setopt_array($curl, array(
        CURLOPT_URL => $send,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));

    $response = curl_exec($curl);
}
