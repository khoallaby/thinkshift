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
$password='tH1nk~_sh1ft@20141188sdfF';  // get pw by function read to obfuscate
$database="thinkshift";

// make the db connection
$con = mysqli_connect($host,$user,$password,$database);

// Check connection
if (mysqli_connect_errno())

{
    echo mysqli_connect_errno();
};

$query="SELECT * FROM tokens;";
$result=mysqli_query($con,$query);
$rows=mysqli_fetch_all($result,MYSQLI_ASSOC);

function getToken(){
global $rows;

return $rows[0]['Access'];

}