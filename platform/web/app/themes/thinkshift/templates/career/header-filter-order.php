<?php
use ThinkShift\Plugin\Careers;


global $wpdb;

$educationMin = [
    '' => '-- Minimum Education --',
];
$educationMin = array_merge( $educationMin, $keys = Careers::getEducationKeys() );


$orderby = [
    '' => '-- Order By --',
];
$orderby = array_merge( $orderby, $keys = Careers::careerKeys() );



$order = [
    '' => '-- Sort --',
    'asc' => 'Lowest to Highest',
    'desc' => 'Highest to Lowest'
];

?>

    <?php foreach( $educationMin as $k => $ob ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['education_min']) ? selected( $_GET['education_min'], $k, false ) : '',
            $ob
        );
    }
    ?>

    <h5>Filter By Salary</h5>
    <!-- <?php foreach( $orderby as $k => $ob ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['orderby']) ? selected( $_GET['orderby'], $k, false ) : '',
            $ob
        );
    }
    ?> -->


    <?php foreach( $order as $k => $o ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['order']) ? selected( $_GET['order'], $k, false ) : '',
            $o
        );
    }
    ?>
<?php #global $wpdb; print($wpdb->last_query); vard($wpdb); ?>
