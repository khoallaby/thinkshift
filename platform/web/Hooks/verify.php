<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/21/2017
 * Time: 6:50 AM
 */

/**
 * Paremeter check
 */

echo 'Infusionsoft webhook verifier.'.chr(10);
if ($argc!=2){
    echo '     Wrong number of parameters.'.chr(10);
    echo '     Use = php verify.php [appname]'.chr(10);
    die('Terminating process.  Endpoint NOT verified.'.chr(10));
}

// straight passthrough so just open connection
$con = mysqli_connect("thinkshiftdataserver.czlkyoy9ghkh.us-east-1.rds.amazonaws.com",
    "awsthinkshift", "?Th1nksh1ft?", "thinkshiftdb", "3306");

// Get access token for the app specified by param 1
$query="SELECT AccessToken FROM OAuth2 WHERE AppName='$argv[1]';";
$return=mysqli_query($con,$query);
$rows=mysqli_fetch_array($return);
$dir='https://api.infusionsoft.com/crm/rest/v1/hooks/?access_token='.$rows['AccessToken'];
$con->close();
$curl = curl_init();

/**
 * Currently code the endpoint
 */
// TODO: add the endpoint url as an optional parameter
$endpoint="http://john.tsdevserver.com/Hooks/";

// Send cURL validation request
function validate($name)
{
    global $endpoint, $curl, $dir;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $dir,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\r\n\"hookUrl\":\"$endpoint\",\r\n\"eventKey\": \"$name\"\r\n}\r\n",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    var_dump($response);echo chr(10);
    $json = json_decode($response);
    if ($json->status) {
        echo '     Verified' . chr(10);
        echo '     Hook Id = '.$json->key.chr(10);
    } else {
        echo '     NOT verified' . chr(10).chr(10);
    }
}

// Display endpoint url
echo 'Requesting to validate '.$endpoint.chr(10);

// contact add
$hook="contact.add";
validate($hook);

// contact delete
$hook="contact.delete";
validate($hook);

// group add
$hook="contactGroup.add";
validate($hook);

// group delete
$hook="contactGroup.delete";
validate($hook);

// group edit
$hook="contactGroup.edit";
validate($hook);

// group applied
$hook="contactGroup.applied";
validate($hook);

// group removed
$hook="contactGroup.removed";
validate($hook);


// cleanup
$err = curl_error($curl);
curl_close($curl);