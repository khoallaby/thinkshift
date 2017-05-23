<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 5/21/2017
 * Time: 6:19 AM
 */

require_once 'src/isdk.php';
require_once 'LogFileObj.php';

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

$con=null;
$log=new LogFileObj('hooks.log');
$app=new iSDK();
$app->cfgCon('gv368','52eab26f63005bf9d631eef5d18687cf');

$in=file_get_contents('PHP://input');
$json=json_decode($in);

function dbconnect(){
    global $con;
    $con = mysqli_connect("thinkshiftdataserver.czlkyoy9ghkh.us-east-1.rds.amazonaws.com",
        "awsthinkshift", "?Th1nksh1ft?", "thinkshiftdb", "3306");

    // Check connection
    if (mysqli_connect_errno()) {
        echo mysqli_connect_errno();
    };
}

function dbclose(){
    global $con;
    $con->close();
}

function foutput($fname){
    $fn=$fname.'.payload.log';
    $st=file_get_contents('PHP://input').chr(10);
    $file=fopen($fn,'a+');
    fputs($file,$st);
    fclose($file);
    //file_put_contents($fn, file_get_contents('PHP://input'));
}

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

function createEmail($Address, $status=0){
    global $con;
    $query="INSERT INTO Email (Status, Address) VALUES (".$status.", '".$Address."');";
    dbconnect();
    $return=mysqli_query($con, $query);
    dbclose();
    return getEmailId($Address);
}

// add contact (working)
function conAdd($j)
{
    global $con, $app;
    dbconnect();
    $test=json_decode($j);
    foutput('contact.add');
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

// delete contact
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
    foutput('contact.delete');
}

// add group
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
    foutput('contactGroup.add');
    return $return;
}

// edit group
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
    foutput('contactGroup.edit');
    return $return;
}

// delete group
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
    foutput('contactGroup.delete');
}

// applied group
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
    foutput('contactGroup.applied');
}

// removed group
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
    foutput('contactGroup.removed');
}

// doHook
function doHook($j=''){
    global $log;
    if ($j=='') {
        $pl = file_get_contents('PHP://input');
    } else {
        $pl=$j;
    }
    $json=json_decode($pl);
    switch ($json->event_key) {
        case 'contact.add':
            $log->lfWriteLn('conAdd()');
            conAdd($json);break;
        case 'contact.delete':
            $log->lfWriteLn('conDelete()');
            conDelete($json);break;
        case 'contactGroup.add':
            $log->lfWriteLn('groupAdd()');
            groupAdd($json);break;
        case 'contactGroup.delete':
            $log->lfWriteLn('groupDelete()');
            groupDelete($json);break;
        case 'contactGroup.edit':
            $log->lfWriteLn('groupEdit()');
            groupEdit($json);break;
        case 'contactGroup.applied':
            $log->lfWriteLn('groupApplied()');
            groupApplied($json);break;
        case 'contactGroup.removed':
            $log->lfWriteLn('groupRemoved()');
            groupRemoved($json);break;
        default:
            $log->lfWriteLn('Un-monitored event key');
            foutput('unknown.log');
            echo 'Un-monitored event key';break;
    }
    $log->lfWriteLn('End *********************************************************');
}