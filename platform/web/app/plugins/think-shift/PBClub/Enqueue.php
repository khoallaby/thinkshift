<?php
$debug=true;
/**
 * Author : John W. Borelli (The Plan B Club, LLC)
 * Date: 4/14/2017
 * Time: 4:05 PM
 */

// priority flags
const NON_CRITICAL = 0;
const CRITICAL = 1;

// table flags
const CONTACT = 'Contact';
const CONTACT_GROUP = 'ContactGroup';
const CONTACT_GROUP_ASSIGN = 'ContactGroupAssign';

require_once 'LogFileObj.php';

$log=new LogFileObj('Enqueue.log');
$log->lfWriteLn('***********************************************************');
$log->lfWriteLn('conEnqueue Process BEGIN :');

// back path to .env for db password
//$file=fopen('../../../../../.env','r');
$file=fopen('/home/bitnami/john-thinkshift/platform/.env','r');

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

/**
 * @param $priority = either CRITICAL (direct) or NON_CRITICAL (DB Queue) (see constants)
 * @param $table = binary assignment for the table (see constants)
 * @param $data = associative array of all correctly named fields for the table/action
 */
function sendTable($priority, $table, $data)
{

	global $con, $log, $debug, $app;

	if ($priority==CRITICAL)
	{

		if ($debug)
		{

			$log->lfWriteLn('CRITICAL flag submitted.  Directly writing to Infusionsoft');

		}

		if ($table==CONTACT)
		{

			$return=$app->addWithDupCheck($data, 'Email');
			if ($debug)
			{
				$log->lfWriteLn('addWithDupCheck return = '.$return);
			}

		} elseif ($table==CONTACT_GROUP_ASSIGN)
		{

			$return=$app->grpAssign($data['ContactId'],$data['GroupId']);
			if ($debug)
			{
				$log->lfWriteLn('grpAssign return = '.$return);
			}

		}

	} elseif ($priority==NON_CRITICAL)
	{

		if ($debug)
		{

			$log->lfWriteLn('NON_CRITICAL flag submitted.  Writing request to queue database');

		}

		if ($table==CONTACT)
		{

			$json   = json_encode( $data );
			$query  = "INSERT INTO transfers (TName,JSON) VALUES ('$table','$json')";
			$result = mysqli_query( $con, $query );
			$return = $con->insert_id;

		}
		elseif ($table==CONTACT_GROUP_ASSIGN)
		{

			$json   = json_encode( $data );
			$query  = "INSERT INTO transfers (TName,JSON) VALUES ('$table','$json')";
			$result = mysqli_query( $con, $query );
			$return = $con->insert_id;

		}

		if ( $debug )
		{

			$log->lfWriteLn( 'Query = '.$query);
			$log->lfWriteLn( 'Enqueue INSERT INTO transfers result = ' . $result );
			$log->lfWriteLn( 'Enqueue INSERT INTO transfers insert_id = ' . $return );

		}

	}

}