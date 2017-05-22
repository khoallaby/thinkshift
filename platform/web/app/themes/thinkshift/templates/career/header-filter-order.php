<?php
use ThinkShift\Plugin\Careers;


global $wpdb;

$educationMin = Careers::getEducationKeys();
$orderby = Careers::careerKeys();

$order = [
    'asc' => 'Lowest to Highest',
    'desc' => 'Highest to Lowest'
];

?>

    <h5>Filter By Education</h5>
    <?php foreach( $educationMin as $k => $ob ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['education_min']) ? selected( $_GET['education_min'], $k, false ) : '',
            $ob
        );
    }
    ?>

    <!-- <?php foreach( $orderby as $k => $ob ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['orderby']) ? selected( $_GET['orderby'], $k, false ) : '',
            $ob
        );
    }
    ?> -->


    <h5>Order By</h5>
    <?php foreach( $order as $k => $o ) {
        echo sprintf( '<label><input type="checkbox" %s class="" autocomplete="off" value="%s"/> %s</label>',
            $k,
            isset($_GET['order']) ? selected( $_GET['order'], $k, false ) : '',
            $o
        );
    }
    ?>
<?php #global $wpdb; print($wpdb->last_query); vard($wpdb); ?>
