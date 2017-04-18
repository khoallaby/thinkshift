<?php
if ( $careers = \ThinkShift\Plugin\Users::getUserMatchingCareers( 3 ) ) {
    foreach ( $careers as $career ) {
        ?>
        <div class="card mb-4 hidden-md-down">
            <div class="card-block">
                <div class="alert alert-success" role="alert">
                    <strong>New career card available</strong>
                </div>
                <h6 class="mb-3">Job Title: <a
                            href="<?php echo get_permalink( $career->ID ); ?>"><?php echo $career->post_title; ?></a>
                </h6>
                <div class="row">
                    <div class="col-lg-6">
                        <p>
                            <small><strong>Average Salary:</strong> $<?php echo number_format( $career->med_wage ); ?>
                            </small>
                        </p>
                        <p>
                            <small><strong># of Openings:</strong> <?php echo round( $career->openings_count ); ?>
                            </small>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <p>
                            <small><strong>Minimum Education:</strong> $<?php echo $career->education_min; ?></small>
                        </p>
                        <p>
                            <small><strong>Skillset</strong> <?php echo $career->tech_skill_kn1; ?></small>
                        </p>
                    </div>
                </div>
                <small><a class="btn btn-default btn-xs" href="<?php echo get_post_type_archive_link( 'career' ); ?>">Learn
                        more</a></small>

            </div>
        </div>
    <?php } ?>
<?php } else { ?>

<div class="card mb-4 hidden-md-down">
    <div class="card-block">
        <h2>No careers found</h2>
    </div>
</div>

<?php } ?>
