<?php

use ThinkShift\Plugin\Users;

// $keys = ['alt_title1', 'alt_title2', 'alt_title3',
//     'alt_title4', 'work_activity1', 'work_activity2','work_activity3', 'work_activity4',
//     'work_activity5', 'work_activity6','work_activity7', 'education_norm', 'education_min', 'tech_skill_kn1', 'tech_skill_kn2',
//     'tech_skill_kn3', 'tech_skill_kn4', 'med_wage', 'openings_count', 'openings_rate', 'openings_rate_cat', 'pct_self_emp',
//     'pct_self_emp_cat'];

// $i = 0;
// $strengthString = $dataString = '';
$strengths = Users::getObjStrengths( get_the_ID() );
// $strengthIds = array_keys($strengths);
// $image = 'images/1.jpg';

// # generate data attributes for strengths
// foreach( $strengths as $k => $strength ) {
//     $i++;
//     $strengthString .= '      data-valuetype' . $i . '="' . $strength . '" ' . "\n";
// }
//
// # generate the rest of data attributes
// foreach( $keys as $key )
//     $dataString .= '      data-' . $key . '="' . esc_attr(get_post_meta( get_the_ID(), $key, true )) . '" ' . "\n";
?>

<div class="card mb-4 career-card-job-desc">
  <div class="card-header"><?php echo get_post_meta( get_the_ID(), 'alt_title1', true); ?></div>
    <div class="card-body">
      <!--career data -->
      <div class="row">
        <div class="col-lg-6">

          <div class="wrap">
            <h5>Good For People With Strengths In:</h5>

            <p><?php echo implode( ', ', $strengths ); ?></p>
          </div>

          <div class="wrap">
            <h5>Major Activities:</h5>
            <p>
            <?php # @todo: this would probably be better as a list, for readability ?>
              <?php echo get_post_meta( get_the_ID(), 'work_activity1', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity2', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity3', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity4', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity5', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity6', true); ?>,
              <?php echo get_post_meta( get_the_ID(), 'work_activity7', true); ?>
            </p>
          </div>

        </div>
        <div class="col-lg-6">
          <div class="wrap">
            <h5>Specialized Skills & Knowledge:</h5>
            <p><?php echo get_post_meta( get_the_ID(), 'alt_title1', true); ?>, <?php echo get_post_meta( get_the_ID(), 'alt_title2', true); ?>, <?php echo get_post_meta( get_the_ID(), 'alt_title3', true); ?>, <?php echo get_post_meta( get_the_ID(), 'alt_title4', true); ?></p>
          </div>
          <div class="wrap">
            <h5>Education Levels:</h5>
            <p><strong>Minimum: </strong><?php echo get_post_meta( get_the_ID(), 'education_norm', true); ?><br>
               <strong>Typical: </strong><?php echo get_post_meta( get_the_ID(), 'education_min', true); ?>
            </p>
          </div>
          <div class="wrap">
            <h5>Median Annual Wage:</h5>

            <p>$<?php
                $salary = get_post_meta( get_the_ID(), 'med_wage', true);
                echo number_format($salary);
              ?>
            </p>
          </div>
        </div>
      </div>

    </div>
</div>
