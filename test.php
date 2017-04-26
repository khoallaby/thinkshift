<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/15/2017
 * Time: 9:55 AM
 */

require_once 'Enqueue.php';

/**$query=array('FirstName'=>'TestFirst1','LastName'=>'TestLast1','Email'=>'TestEmail1@test.com');
sendTable(NON_CRITICAL,CONTACT, $query);

$query=array('FirstName'=>'TestFirst2','LastName'=>'TestLast2','Email'=>'TestEmail2@test.com');
sendTable(CRITICAL,CONTACT, $query);

$query=array('FirstName'=>'TestFirst3','LastName'=>'TestLast3','Email'=>'TestEmail3@test.com');
sendTable(CRITICAL,CONTACT, $query);

$query=array('FirstName'=>'TestFirst4','LastName'=>'TestLast4','Email'=>'TestEmail4@test.com');
sendTable(NON_CRITICAL,CONTACT, $query);**/


$query=array('ContactId'=>93079,'GroupId'=>11109);
sendTable(CRITICAL,CONTACT_GROUP_ASSIGN, $query);

$query=array('ContactId'=>93081,'GroupId'=>11109);
sendTable(NON_CRITICAL,CONTACT_GROUP_ASSIGN, $query);

$query=array('ContactId'=>93083,'GroupId'=>11109);
sendTable(NON_CRITICAL,CONTACT_GROUP_ASSIGN, $query);

$query=array('ContactId'=>93085,'GroupId'=>11109);
sendTable(CRITICAL,CONTACT_GROUP_ASSIGN, $query);
