<?php
$debug=true;
/**
 * Author : John W. Borelli (The Plan B Club, LLC)
 * Date: 4/15/2017
 * Time: 8:00 AM
 */

// priority flags
const NON_CRITICAL = 00000000;
const CRITICAL = 00000001;

// table flags
const CONTACT = 'Contact';
const CONTACT_GROUP = 'ContactGroup';
const CONTACT_GROUP_ASSIGN = 'ContactGroupAssign';

require_once 'LogFileObj.php';
$log=new LogFileObj('runEnqueued.log');
$log->lfWriteLn('***********************************************************');
$log->lfWriteLn('runEnqueued Process BEGIN :');

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

// Infusionsoft dependencies
require_once 'src/isdk.php';
$app=new iSDK();
if ($debug){
	$app->cfgCon( 'yo243', 'c959c2b28a10eb74a2d5af154ba1986d' );
} else {
	$app->cfgCon( 'fd341', '9122d201f6892d5b3397f675849baafa' );
}

$query="SELECT * FROM transfers;";
$result=mysqli_query($con,$query);
$rows=mysqli_fetch_all($result,MYSQLI_ASSOC);

foreach ($rows as $array) {
	$arr=[];
	foreach ($array as $key=>$row){
		$arr[$key]=$row;
	}
	$data=(array) json_decode($arr['JSON'],true);

	$log->lfWriteLn('JSON from DB = '.$data);

	if ($arr['TName']==CONTACT) {
		$return=$app->addWithDupCheck($data,'Email');
		$log->lfWriteLn('Writing contact from DB result = '.$return);
		if ($return) {
			$id=$arr['Id'];
			$query="DELETE FROM transfers WHERE Id='$id';";
			$result=mysqli_query($con,$query);
		}
	} elseif ($arr['TName']==CONTACT_GROUP_ASSIGN) {
		$return=$app->grpAssign($data['ContactId'],$data['GroupId']);
		$log->lfWriteLn('Applying tag from DB result = '.$return);
		if ($return) {
			$id=$arr['Id'];
			$query="DELETE FROM transfers WHERE Id='$id';";
			$result=mysqli_query($con,$query);
		}
	}
}