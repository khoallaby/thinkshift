<?php
use ThinkShift\Plugin\Tags;
use ThinkShift\Plugin\Users;

global $strengths;
if( !isset($strengths) )
    $strengths = Tags::getAllStrengths( false );
?>

<form id="post-filter">
    <div class="row">
        <div class="col-12 text-xs-center" data-toggle="buttons">
            <?php

            if( isset($_GET['strengths']) )
                $strengthArray = $_GET['strengths'];
            else
                $strengthArray = Users::getUserStrengths();
            if( !$strengthArray )
                $strengthArray = [];



            foreach( $strengths as $strength ) {

                $checked = in_array( $strength, $strengthArray );
                ?>
                <label class="btn mr-4 mb-4 btn-lg btn-pill btn-primary <?php echo $checked ? 'active' : ''; ?>">
                    <?php
                    echo sprintf('<input type="checkbox" %s class="" autocomplete="off" name="strengths[]" value="%s"/> %s',
                        $checked ? 'checked' : '',
                        $strength,
                        $strength
                    );
                    ?>
                </label>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-xs-right">
            <input type="submit" class="btn btn-primary" value="Submit" />
        </div>
    </div>
</form>