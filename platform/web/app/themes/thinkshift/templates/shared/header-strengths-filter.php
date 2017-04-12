<?php
use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Users;

if( !isset($strengths) )
    $strengths = Tags::getAllStrengths( false );
?>

<form id="post-filter">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div data-toggle="buttons">
                  <?php

                  if( isset($_GET['strengths']) )
                      $strengthArray = $_GET['strengths'];
                  else
                      $strengthArray = array_keys( Users::getUserStrengths() );
                  if( !$strengthArray )
                      $strengthArray = [];



                  foreach( $strengths as $strengthId => $strength ) {
                      $checked = in_array( $strengthId, $strengthArray );
                      ?>
                      <label class="btn mr-4 mb-4 btn-xs btn-pill btn-default <?php echo $checked ? 'btn-success' : ''; ?>">
                          <?php
                          echo sprintf('<input type="checkbox" %s class="" autocomplete="off" name="strengths[]" value="%s"/> %s',
                              $checked ? 'checked' : '',
                              $strengthId,
                              $strength
                          );
                          ?>
                      </label>
                  <?php } ?>
                </div>
            </div>
          </div>
      </div>
    </div>
    <div class="row">
        <div class="col-12 text-xs-right">
            <input type="submit" class="btn btn-primary" value="Submit" />
        </div>
    </div>
</form>
