<?php

use ThinkShift\Plugin\Users;

$keys = ['ValueType1', 'ValueType2','ValueType3','occcode', 'occup', 'occdesc', 'alttitle1', 'alttitle2', 'alttitle3',
    'alttitle4', 'WorkActivity1', 'WorkActivity2','WorkActivity3', 'WorkActivity4',
    'WorkActivity5', 'WorkActivity6','WorkActivity7', 'EducationNorm', 'EducationMin', 'techskillkn1', 'techskillkn2',
    'techskillkn3', 'techskillkn4', 'MedWage', 'PrEarnGrowth','PrLongUnemp',
    'openingscount', 'openingsrate', 'openingsratecat', 'pctselfemp',
    'pctselfempcat', 'highoppjobfamily', 'jobzone'];


$strengths = Users::getObjStrengths( get_the_ID() );
$strengthIds = array_keys($strengths);


echo sprintf('
<a class="job-title"
    href="' . get_the_permalink() . '"
    data-largesrc="images/1.jpg"
    data-strengths="' . implode( ',', $strengthIds ) . '"
    data-title="' . get_the_permalink() .'"
    data-description="' . the_content() . '">
    <h1>' . get_the_title() . '</h1>
</a>
');
?>
