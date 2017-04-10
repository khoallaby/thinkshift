<?php

$keys = ['ValueType1', 'ValueType2','ValueType3','occcode', 'occup', 'occdesc', 'alttitle1', 'alttitle2', 'alttitle3',
    'alttitle4', 'WorkActivity1', 'WorkActivity2','WorkActivity3', 'WorkActivity4',
    'WorkActivity5', 'WorkActivity6','WorkActivity7', 'EducationNorm', 'EducationMin', 'techskillkn1', 'techskillkn2',
    'techskillkn3', 'techskillkn4', 'MedWage', 'PrEarnGrowth','PrLongUnemp',
    'openingscount', 'openingsrate', 'openingsratecat', 'pctselfemp',
    'pctselfempcat', 'highoppjobfamily', 'jobzone'];

    echo sprintf('
      <a class="job-title"
      href="' . get_the_permalink() .'"
      data-largesrc="images/1.jpg"
      data-ValueType2="'.get_post_meta( get_the_ID(), 'ValueType2', true).'"
      data-title="' . get_the_permalink() .'"
      data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
        <h1>' . get_the_title() . '</h1>
      </a>
    ');
?>
