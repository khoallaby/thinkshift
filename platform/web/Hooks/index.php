<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/21/2017
 * Time: 6:19 AM
 */

/**
 * IMPORTANT!  The following code MUST be at the top and at code root level
 * before namespacing or any other code.  While it is not necessary to keep
 * this code it is necessary that it is present whenever endpoint validation
 * is registered as this is how Infusionsoft confirms the endpoint as valid.
 */

$headers = array();
foreach ($_SERVER as $key => $value) {
    if (substr($key, 0, 5) <> 'HTTP_') {
        continue;
    }
    $header           = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
    $headers[$header] = $value;
}
$notification = json_decode(file_get_contents('php://input'));
header('X-Hook-Secret: ' . $headers['X-Hook-Secret'] . '');

/**
 * End required code root
 */

error_reporting(E_ALL);

require('src/isdk.php');

$con=null;
$app=new iSDK();

/**
 * We are using api key here since this basically replaces the enqueueing process and will
 * split the load between the two api pools
 */
// $app->cfgCon('gv368','52eab26f63005bf9d631eef5d18687cf'); // Thinkshift sandbox
$app->cfgCon('fd341','9122d201f6892d5b3397f675849baafa'); // Thinkshift live

// open connection to RDS server
function dbconnect(){
    global $con;
    $con = mysqli_connect("thinkshiftdataserver.czlkyoy9ghkh.us-east-1.rds.amazonaws.com",
        "awsthinkshift", "?Th1nksh1ft?", "thinkshiftdb", "3306");

    // Check connection
    if (mysqli_connect_errno()) {
        echo mysqli_connect_errno();
    };
}

// Close RDS server connectoin
function dbclose(){
    global $con;
    $con->close();
}

// Get the database email id
function getEmailId($email){
    global $con;
    $id=0;
    dbconnect();
    $query="SELECT Id FROM Email WHERE Address='".$email."';";
    $return=mysqli_query($con,$query);
    $data=mysqli_fetch_array($return);
    dbclose();
    if ($query==null){return 0;} else {return $data['Id'];}
}

// Get the database contact id
function getContactId($email){
    global $con;
    $eid=getEmailId($email);
    if ($eid==0){
        return 0;
    } else {
        dbconnect();
        $query="SELECT Id FROM Contact WHERE Email1='".$eid."';";
        $return=mysqli_query($con,$query);
        $data=mysqli_fetch_array($return);
        dbclose();
        if ($data==null){
            return 0;
        } else {
            return $data['Id'];
        }
    }
}

// Create a row in the Email table
function createEmail($Address, $status=0){
    global $con;
    $query="INSERT INTO Email (Status, Address) VALUES (".$status.", '".$Address."');";
    dbconnect();
    $return=mysqli_query($con, $query);
    dbclose();
    return getEmailId($Address);
}

// Add a row to the contact table
function conAdd($j)
{
    global $con, $app;
    dbconnect();
    $test=json_decode($j);
    $data=$app->dsLoad('Contact',$test->object_key,array('Id','FirstName','LastName','Email','Phone1'));
    $fname=$data['FirstName'];
    $lname=$data['LastName'];
    $email=$data['Email'];
    $query="CALL ts_contact_create('$fname', '$lname', '$email');";
    $return=mysqli_query($con,$query);
    $data=$return->fetch_array();
    dbclose();
    return $data['@cId'];
}

// Delete a row from the contact table
function conDelete($j)
{
    global $con, $app;
    $test=json_decode($j);
    $data=$app->dsLoad('Contact',$test->object_key,array('Id','FirstName','LastName','Email','Phone1'));
    $fname=$data['FirstName'];
    $lname=$data['LastName'];
    $email=$data['Email'];
    $id=getContactId($email);
    $query="DELETE FROM Contact WHERE Id=$id;";
    dbconnect();
    $return=mysqli_query($con,$query);
    dbclose();
}

// Add a row to the ContactGroup table
function groupAdd($j)
{
    global $con, $app;
    $test=json_decode($j);
    $data=$app->dsLoad('ContactGroup',$test->object_key,array('Id','GroupName','GroupDescription','GroupCategoryId'));
    $tid=$data['Id'];
    $tagName=$data['GroupName'];
    $tagDesc=$data['GroupDescription'];
    $tagCat=$data['GroupCategoryId'];
    $query="CALL ts_tag_create('$tagName','$tagDesc',$tagCat);";
    dbconnect();
    $return=mysqli_query($con,$query);
    dbclose();
    return $return;
}

// Edit an existing row in the ContactGroup table
function groupEdit($j)
{
    global $con, $app;
    $test=json_decode($j);
    $data=$app->dsLoad('ContactGroup',$test->object_key,array('Id','GroupName','GroupDescription','GroupCategoryId'));
    $id=$data['Id'];
    $tagName=$data['GroupName'];
    $tagDesc=$data['GroupDescription'];
    $tagCat=$data['GroupCategoryId'];
    $query="UPDATE ContactGroup SET GroupName='$tagName', GroupDesc='$tagDesc', GroupCatId=$tagCat WHERE GroupName='$tagName';";
    dbconnect();
    $return=$con->query($query);
    return $return;
}

// Delete a row from the ContactGroup table
function groupDelete($j)
{
    global $con, $app;
    $test=json_decode($j);
    $id=$test->object_key;
    $data=$app->dsLoad('ContactGroup',$test->object_key,array('Id','GroupName','GroupDescription','GroupCategoryId'));
    $tagName=$data['GroupName'];
    $query="DELETE FROM ContactGroup WHERE GroupName='$tagName';";
    dbconnect();
    $return=mysqli_query($con,$query);
    dbclose();
}

// Add a row to the ContactGroupAssign table
function groupApplied($j)
{
    global $con, $app;
    $test=json_decode($j);
    dbconnect();
    $dt=date('Y-m-d H:i:s');
    $hold=json_decode($test->object_key);
    foreach ($hold as $h) {
        $tid=$h->tagId;
        foreach ($h->applied as $cid){
            $query="INSERT INTO ContactGroupAssign (ContactId, DateCreated, GroupId) VALUES ($cid,'$dt',$tid);";
            $con->query($query);
        }
    }
    dbclose();
}

// Delete a row from the ContactGroupAssign table
function groupRemoved($j)
{
    global $con, $app;
    $test=json_decode($j);
    dbconnect();
    $dt=date('Y-m-d H:i:s');
    $hold=json_decode($test->object_key);
    foreach ($hold as $h) {
        $tid=$h->tagId;
        foreach ($h->applied as $cid){
            $query="DELETE FROM ContactGroupAssign WHERE GroupId=$tid AND ContactId=$cid;";
            $con->query($query);
        }
    }
    dbclose();
}

/**function showTrigger($name){
    $file=fopen('$name'.'.log','w+');
    fputs($file,$name.chr(10));
    fclose($file);
}**/

function addTransaction($j){
    global $con;
    dbconnect();
    $query="INSERT INTO Transactions (JSON) VALUE ('$j');";
    $con->query($query);
    dbclose();
}

// Process the incoming webhook and use the appropriate function
function doHook($j=''){
    //global $log;

    if ($j=='') {
        $pl = file_get_contents('PHP://input');
    } else {
        $pl=$j;
    }

    $json=json_decode($pl);
    switch ($json->event_key) {
        case 'contact.add':
            conAdd($json);break;
        case 'contact.delete':
            conDelete($json);break;
        case 'contactGroup.add':
            groupAdd($json);break;
        case 'contactGroup.delete':
            groupDelete($json);break;
        case 'contactGroup.edit':
            groupEdit($json);break;
        case 'contactGroup.applied':
            groupApplied($json);break;
        case 'contactGroup.removed':
            groupRemoved($json);break;
        default:
            echo 'Un-monitored event key';break;
    }
}

// Get data block sent from IS and convert to JSON object then call doHook($json)
$in=file_get_contents('PHP://input');
addTransaction($in);
$json=json_decode($in);
doHook($json);