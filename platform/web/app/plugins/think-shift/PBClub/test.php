<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/15/2017
 * Time: 9:55 AM
 */

$now = time();
echo 'Timestamp now = '.$now.chr(10);
$dtz=new DateTime('America/Los_Angeles');
$dtzstr=$dtz->format('Y-m-d H:i:s');
echo 'Date String = '.$dtzstr.chr(10);
echo 'Timezone = '.$dtz->getTimezone()->getName().chr(10);
$d = new DateTime('2017-05-14T10:40:12');
$dstr=$d->format('Y-m-d H:i:s');
$then = $d->getTimestamp();
echo 'Timestamp then = '.$then.chr(10);
echo 'Date String = '.$dstr.chr(10);
echo 'Timezone = '.$d->getTimezone()->getName().chr(10);
$diff=$now-$then;
echo 'Differential = '.$diff.chr(10);
