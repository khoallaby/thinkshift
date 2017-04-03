<?php

$keys = ['occcode', 'occup', 'occdesc', 'alttitle1', 'alttitle2', 'alttitle3',
    'alttitle4', 'WorkActivity1', 'WorkActivity2','WorkActivity3', 'WorkActivity4',
    'WorkActivity5', 'WorkActivity6','WorkActivity7', 'ValueType1', 'ValueType2',
    'ValueType3','EducationNorm', 'EducationMin', 'techskillkn1', 'techskillkn2',
    'techskillkn3', 'techskillkn4', 'MedWage', 'PrEarnGrowth','PrLongUnemp',
    'openingscount', 'openingsrate', 'openingsratecat', 'pctselfemp',
    'pctselfempcat', 'highoppjobfamily', 'jobzone'];


foreach( $keys as $key )
    echo sprintf( '<div class="col-lg-12"><strong>%s:</strong> %s</div>', $key, get_post_meta( get_the_ID(), $key, true ) );
