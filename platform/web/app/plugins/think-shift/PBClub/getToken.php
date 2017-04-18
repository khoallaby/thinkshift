<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/17/2017
 * Time: 7:54 PM
 */

// initialize access vars
$host="localhost";
$user="thinkshift";
$password=mysqlPW();  // get pw by function read to obfuscate
$database="thinkshift";

// be sure to close .env file
fclose($file);

// make the db connection
$con = mysqli_connect($host,$user,$password,$database);

// Check connection
if (mysqli_connect_errno())
{
    echo mysqli_connect_errno();
};

// Infusionsoft dependencies
require_once 'src/isdk.php';
$app=new iSDK();
if ($debug){
    $app->cfgCon( 'yo243', 'c959c2b28a10eb74a2d5af154ba1986d' );
} else {
    $app->cfgCon( 'fd341', '9122d201f6892d5b3397f675849baafa' );
}

$query="SELECT * FROM tokens;";
$result=mysqli_query($con,$query);
$rows=mysqli_fetch_all($result,MYSQLI_ASSOC);

echo $rows[0]['Access'];