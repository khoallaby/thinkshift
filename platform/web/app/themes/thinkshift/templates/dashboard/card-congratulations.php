
<div class="card mb-4" id="dashboard-career-card">
  <div class="card-header bg-themec-yellow">
    <i class="icon fa fa-id-card-o fa-1x"></i> <span><h6>Career Possibilities Based on your Strengths</h6></span>
  </div>
  <div class="card-body no-padding">
    <div class="table-responsive">
      <table class="table card-table">
        <thead>
          <tr>
            <th>Occupation Name</th>
            <th>Avg Salary</th>
            <th>Minimum Education</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

        <?php
        if ( $careers = \ThinkShift\Plugin\Users::getUserMatchingCareers( 20 ) ) {
            foreach ( $careers as $career ) {

                ?>
                <tr>
                  <td><a href="<?php echo get_permalink( $career->ID ); ?>"><?php echo $career->post_title; ?></a></td>
                  <td>$<?php echo number_format( $career->med_wage ); ?></td>
                  <td><?php echo $career->education_min; ?></td>
                  <td><a href="<?php echo get_post_type_archive_link( 'career' ); ?>"><span class="badge badge-info badge-icon"><i class="fa fa-credit-card" aria-hidden="true"></i><span>Learn More</span></span></a></td>
                </tr>
        <?php } ?>

        </tbody>
      </table>
    </div>
  </div>
</div>
<?php } else { ?>

  <div class="card mb-4 hidden-md-down mb-4" id="dashboard-career-card1">
    <div class="card-header bg-themec-yellow">
      Career Cards
    </div>
      <div class="card-block">
          <h2>No careers found</h2>
      </div>
  </div>

<?php } ?>
