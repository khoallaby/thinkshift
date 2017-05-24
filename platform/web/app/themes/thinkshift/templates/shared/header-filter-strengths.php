<?php
use ThinkShift\Plugin\CustomPostTypes,
    ThinkShift\Plugin\Tags,
    ThinkShift\Plugin\Users;

if( !isset($strengths) )
    $strengths = Tags::getAllStrengths( false );
?>

<form id="post-filter">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-themec-yellow">
                  <i class="icon fa fa-filter" aria-hidden="true"></i><h6>Filter Career Cards</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <div data-toggle="buttons" class="text-center">
                          <?php

                          if ( isset( $_GET['strengths'] ) )
                              $strengthArray = $_GET['strengths'];
                          else
                              $strengthArray = is_user_logged_in() ? array_keys( Users::getUserStrengths(2) ) : [];

                          if ( ! $strengthArray )
                              $strengthArray = [];



                          foreach ( $strengths as $strengthId => $strength ) {
                              $checked = in_array( $strengthId, $strengthArray );
                              ?>
                              <label class="btn mr-2 mb-2 btn-xs btn-pill btn-default <?php echo $checked ? 'active' : ''; ?>">
                                  <?php
                                  echo sprintf( '<input type="checkbox" %s class="" autocomplete="off" name="strengths[]" value="%s"/> %s',
                                      $checked ? 'checked' : '',
                                      $strengthId,
                                      $strength
                                  );
                                  ?>
                              </label>
                          <?php } ?>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <?php #if( CustomPostTypes::isPostType( 'career' ) ) get_template_part( 'templates/shared/header', 'filter-order' ); ?>
                      <input type="submit" class="btn btn-primary" value="Submit" />
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-xs-right">

        </div>
    </div>
</form>
