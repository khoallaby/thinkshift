<?php

require_once 'TSDBObj.php';
require_once 'awsconfig.php';
require_once 'refresh.php';
//$con=mysqli_connect($host, $awsuser, $password, $database, $port);

$ts=new TSDBObj();

$data=$ts->ts_contact_exists('jjjjjj@bbbbbbb.com');

echo $data.chr(10);