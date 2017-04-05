<?php

$keys = ['ValueType1', 'ValueType2','ValueType3','occcode', 'occup', 'occdesc', 'alttitle1', 'alttitle2', 'alttitle3',
    'alttitle4', 'WorkActivity1', 'WorkActivity2','WorkActivity3', 'WorkActivity4',
    'WorkActivity5', 'WorkActivity6','WorkActivity7', 'EducationNorm', 'EducationMin', 'techskillkn1', 'techskillkn2',
    'techskillkn3', 'techskillkn4', 'MedWage', 'PrEarnGrowth','PrLongUnemp',
    'openingscount', 'openingsrate', 'openingsratecat', 'pctselfemp',
    'pctselfempcat', 'highoppjobfamily', 'jobzone'];


foreach( $keys as $key )
    echo sprintf( '<div class="col-lg-12"><h5 style="display: inline-block">%s:</h5> %s</div>', $key, get_post_meta( get_the_ID(), $key, true ) );
