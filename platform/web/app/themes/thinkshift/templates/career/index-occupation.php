<?php

use ThinkShift\Plugin\Users;

$keys = ['alt_title1', 'alt_title2', 'alt_title3',
    'alt_title4', 'work_activity1', 'work_activity2','work_activity3', 'work_activity4',
    'work_activity5', 'work_activity6','work_activity7', 'education_norm', 'education_min', 'tech_skill_kn1', 'tech_skill_kn2',
    'tech_skill_kn3', 'tech_skill_kn4', 'med_wage', 'openings_count', 'openings_rate', 'openings_rate_cat', 'pct_self_emp',
    'pct_self_emp_cat'];

$strengths = Users::getObjStrengths( get_the_ID() );
$strengthIds = array_keys($strengths);

var_dump($strengths);

$i = 0;
$strengthString = '';

foreach( $strengths as $k => $strength ) {
    $i++;
    $strengthString .= 'data-valuetype' . $i . '="' . $strength . '" ';
}

echo sprintf('
  <a class="job-title"
      href="' . get_the_permalink() . '"
      '.$strengthString.'
      data-work_activity1="'. get_post_meta( get_the_ID(), 'work_activity1', true) .'"
      data-work_activity2="'. get_post_meta( get_the_ID(), 'work_activity2', true) .'"
      data-work_activity3="'. get_post_meta( get_the_ID(), 'work_activity3', true) .'"
      data-work_activity4="'. get_post_meta( get_the_ID(), 'work_activity4', true) .'"
      data-work_activity5="'. get_post_meta( get_the_ID(), 'work_activity5', true) .'"
      data-work_activity6="'. get_post_meta( get_the_ID(), 'work_activity6', true) .'"
      data-work_activity7="'. get_post_meta( get_the_ID(), 'work_activity7', true) .'"

      data-tech_skill_kn1="'. get_post_meta( get_the_ID(), 'tech_skill_kn1', true) .'"
      data-tech_skill_kn2="'. get_post_meta( get_the_ID(), 'tech_skill_kn2', true) .'"
      data-tech_skill_kn3="'. get_post_meta( get_the_ID(), 'tech_skill_kn3', true) .'"
      data-tech_skill_kn4="'. get_post_meta( get_the_ID(), 'tech_skill_kn4', true) .'"

      data-education_norm="'. get_post_meta( get_the_ID(), 'education_norm', true) .'"
      data-education_min="'. get_post_meta( get_the_ID(), 'education_min', true) .'"

      data-med_wage="'. get_post_meta( get_the_ID(), 'med_wage', true) .'"

      data-openings_count="'. get_post_meta( get_the_ID(), 'openings_count', true) .'"
      data-openings_rate="'. get_post_meta( get_the_ID(), 'openings_rate', true) .'"
      data-openings_rate_cat="'. get_post_meta( get_the_ID(), 'openings_rate_cat', true) .'"

      data-pct_self_emp="'. get_post_meta( get_the_ID(), 'pct_self_emp', true) .'"
      data-pct_self_emp_cat="'. get_post_meta( get_the_ID(), 'pct_self_emp_cat', true) .'"

      data-alt_title1="'. get_post_meta( get_the_ID(), 'alt_title1', true) .'"
      data-alt_title2="'. get_post_meta( get_the_ID(), 'alt_title2', true) .'"
      data-alt_title3="'. get_post_meta( get_the_ID(), 'alt_title3', true) .'"
      data-alt_title4="'. get_post_meta( get_the_ID(), 'alt_title4', true) .'"

      data-largesrc="images/1.jpg"
      data-strengths="' . implode( ',', $strengthIds ) . '"
      data-title="' . get_the_title() .'"
      data-description="' . get_the_content() . '">
      <h1>' . get_the_title() . '</h1>
  </a>
');
?>
