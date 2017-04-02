<div class="card mb-4 hidden-md-down">
    <div class="card-block">
        <h2 class="mb-3">Congratulations! You have completed your assessments.</h2>
        <div class="row">
            <div class="col-lg-6">
                <h6>Here are a few recommendations:</h6>
                <?php if( $careers = \ThinkShift\Plugin\Users::getUserMatchingCareers( 3 ) ) : ?>
                <ul>
                    <?php /*vard($careers);*/ foreach( $careers as $career ) { ?>
                    <li><a href="<?php echo get_permalink( $career->ID ); ?>"><?php echo $career->post_title; ?></a></li>
                        <?php
                        # @todo: for testing to confirm all 3 strengths match, remove
                        #for( $i = 1; $i <= 3; $i++ )
                            #echo $i . ': ' . get_user_meta( \ThinkShift\Plugin\Users::$userId, 'strength_' . $i, true ) .'<br>';
                        ?>
                    <?php } ?>
                </ul>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <h6>Not interested?</h6>
                <button class="btn btn-outline-primary btn-sm">Explore more careers</button>
            </div>
        </div>
    </div>
</div>