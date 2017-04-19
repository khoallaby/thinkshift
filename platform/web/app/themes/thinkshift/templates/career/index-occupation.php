<?php

use ThinkShift\Plugin\Users;

$keys = ['alt_title1', 'alt_title2', 'alt_title3',
    'alt_title4', 'work_activity1', 'work_activity2','work_activity3', 'work_activity4',
    'work_activity5', 'work_activity6','work_activity7', 'education_norm', 'education_min', 'tech_skill_kn1', 'tech_skill_kn2',
    'tech_skill_kn3', 'tech_skill_kn4', 'med_wage', 'openings_count', 'openings_rate', 'openings_rate_cat', 'pct_self_emp',
    'pct_self_emp_cat'];



$i = 0;
$strengthString = $dataString = '';
$strengths = Users::getObjStrengths( get_the_ID() );
$strengthIds = array_keys($strengths);
$image = 'images/1.jpg';




# generate data attributes for strengths
foreach( $strengths as $k => $strength ) {
    $i++;
    $strengthString .= '      data-valuetype' . $i . '="' . $strength . '" ' . "\n";
}

# generate the rest of data attributes
foreach( $keys as $key )
    $dataString .= '      data-' . $key . '="' . esc_attr(get_post_meta( get_the_ID(), $key, true )) . '" ' . "\n";



echo sprintf('
  <a class="job-title"
      href="' . get_the_permalink() . '"
      ' . $strengthString . $dataString . '
      data-largesrc="' . esc_attr( $image  ). '"
      data-strengths="' . implode( ',', $strengthIds ) . '"
      data-title="' . esc_attr( get_the_title() ) .'"
      data-description="' . esc_attr( get_the_content() ) . '">
      <h4>' . get_post_meta( get_the_ID(), 'med_wage', true ) . '</h4>
      <h4>' . get_post_meta( get_the_ID(), 'education_min', true ) . '</h4>
  </a>
');
?>
