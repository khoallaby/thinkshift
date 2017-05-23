<?php
use ThinkShift\Plugin\CustomPostTypes,
    ThinkShift\Plugin\Tags,
    ThinkShift\Plugin\Users,
    ThinkShift\Plugin\Careers;

if( !isset($strengths) )
    $strengths = Tags::getAllStrengths( false );



$educationMin = Careers::getEducationKeys();
$careerFields = Careers::getCareerFieldKeys();
$selfEmployment = Careers::getSelfEmploymentKeys();

$order = [
    'asc' => 'Lowest to Highest',
    'desc' => 'Highest to Lowest'
];


?>
<div class="col-lg-4 mt-4">
<form id="post-filter">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-themec-yellow">
                  <i class="icon fa fa-filter" aria-hidden="true"></i><h6>Filter Career Cards</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12" id="filters-strengths">
                            <h5>Strengths</h5>
                            <?php

                            if ( isset( $_GET['strengths'] ) ) {
                                $strengthArray = $_GET['strengths'];
                            } else {
                                $strengthArray = is_user_logged_in() ? array_keys( Users::getUserStrengths( 2 ) ) : [];
                            }

                            if ( ! $strengthArray ) {
                                $strengthArray = [];
                            }


                            foreach ( $strengths as $strengthId => $strength ) {
                                $checked = in_array( $strengthId, $strengthArray );
                                ?>
                                <label class="<?php echo $checked ? 'active' : ''; ?>">
                                    <?php
                                    echo sprintf( '<input type="checkbox" %s name="strengths[]" value="%s"/> %s',
                                        $checked ? 'checked' : '',
                                        $strengthId,
                                        $strength
                                    );
                                    ?>
                                </label>
                            <?php } ?>
                        </div>
                        <div class="col-lg-12" id="filters-education">
                            <h5>Minimum Education Required</h5>
                            <?php foreach( $educationMin as $k => $name ) {
                                echo sprintf( '<label><input type="checkbox" %s value="%s" name="education[]" /> %s</label>',
                                    isset($_GET['education']) ? checked( in_array($k, $_GET['education']), true, false ) : '',
                                    $k,
                                    $name
                                );
                            }
                            ?>
                        </div>
                        <div class="col-lg-12" id="filters-career-fields">
                            <h5>Career Field</h5>
                            <?php foreach( $careerFields as $k => $name ) {
                                echo sprintf( '<label><input type="checkbox" %s value="%s" name="career[]" /> %s</label>',
                                    isset($_GET['career']) ? checked( $_GET['career'], $k, false ) : '',
                                    $k,
                                    $name
                                );
                            }
                            ?>
                        </div>
                        <div class="col-lg-12" id="filters-self-employment">
                            <h5>Self-Employment Prospects</h5>
                            <?php foreach( $selfEmployment as $k => $name ) {
                                echo sprintf( '<label><input type="checkbox" %s value="%s" name="self[]" /> %s</label>',
                                    isset($_GET['self']) ? checked( $_GET['self'], $k, false ) : '',
                                    $k,
                                    $name
                                );
                            }
                            ?>
                        </div>
                        <div class="col-lg-12" id="filters-orderby">
                            <h5>Order By</h5>
                            <select name="order">
                                <?php foreach( $order as $k => $o ) {
                                    echo sprintf( '<option value="%s" %s>%s</option>',
                                        $k,
                                        isset($_GET['order']) ? selected( $_GET['order'], $k, false ) : '',
                                        $o
                                    );
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-lg-12" id="filters-submit">
                            <br />
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
</div>
