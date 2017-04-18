<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/17/2017
 * Time: 7:54 PM
 */

// back path to .env for db password
$file=fopen('../../../../../.env','r');

// read db pw to obfuscate
/**
 * @return bool|string
 *      return string value of pw if found otherwise return false
 */
function mysqlPW() {
    global $file;

    while (!feof($file)){

        $st=fgets($file);

        if (strpos($st,'DB_PASSWORD')>-1){

            return substr($st,12,-1);

        }
    }
    return false;
}

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

$query="SELECT * FROM tokens;";
$result=mysqli_query($con,$query);
$rows=mysqli_fetch_all($result,MYSQLI_ASSOC);

function getToken(){
global $rows;

return $rows[0]['Access'];

}