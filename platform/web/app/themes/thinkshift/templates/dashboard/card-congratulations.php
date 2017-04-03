<?php if( $careers = \ThinkShift\Plugin\Users::getUserMatchingCareers( 3 ) ) : ?>
  <?php /*vard($careers);*/ foreach( $careers as $career ) { ?>
    <?php
    # @todo: for testing to confirm all 3 strengths match, remove
    #for( $i = 1; $i <= 3; $i++ )
        #echo $i . ': ' . get_user_meta( \ThinkShift\Plugin\Users::$userId, 'strength_' . $i, true ) .'<br>';
    ?>
    <div class="card mb-4 hidden-md-down">
        <div class="card-block">
            <h2 class="mb-3"><a href="<?php echo get_permalink( $career->ID ); ?>"><?php echo $career->post_title; ?></a></h2>
            <small><a class="btn btn-outline-primary btn-sm" href="<?php echo get_post_type_archive_link( 'career' ); ?>">Explore more careers</a></small>
        </div>
    </div>
  <?php } ?>
<?php endif; ?>
