<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 4/18/2017
 * Time: 4:35 PM
 * @param $dt = (string) Y-m-d H:i:s
 * @return int|mixed|string
 */
function tagsSinceDateCount($dt)
{
    require_once 'src/isdk.php';

    $date=new DateTime($dt);

    $app=new iSDK();
    $app->cfgCon('fd341','9122d201f6892d5b3397f675849baafa');

    // convert date query
    $table='ContactGroupAssign';
    $query = array('DateCreated' => '~>~' . $date->format('Y-m-d H:i:s'));
    // get query count to return as part of struct
    return $app->dsCount($table, $query);

}

/**
 * @param $dt
 * @return array
 */
function tagsSinceDate($dt)
{
    $arr=[];
    $x=0;
    $total=tagsSinceDateCount($dt);
    $count=$total;
    if ($total>999){$count=1000;}

    require_once 'src/isdk.php';

    $app = new iSDK();
    $app->cfgCon('fd341', '9122d201f6892d5b3397f675849baafa');
    $date=new DateTime($dt);
    $query = array('DateCreated' => '~>~' . $date->format('Y-m-d H:i:s'));
    while ($total>0) {
        $data = $app->dsQuery('ContactGroupAssign', 1000, 0,$query,
            array('ContactId', 'DateCreated', 'Contact.Groups'));
        foreach ($data as $dat){
            $arr[$x]=$dat;
            $x++;
            $count--;
        }
        $total-=1000;
        $count=$total;
        if ($total>999){$count=1000;}
    }
    return $arr;
}

/** Example uses:

$test=tagsSinceDate('2017-04-18 19:00:00');

echo tagsSinceDateCount('2017-04-18 19:00:00').chr(10);
echo count($test).chr(10);
var_dump($test);echo chr(10);

*
*/
