<?php
use ThinkShift\Plugin\Careers;


global $wpdb;

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
<select name="orderby">
    <?php foreach( $orderby as $k => $ob ) {
        echo sprintf( '<option value="%s" %s>%s</option>',
            $k,
            isset($_GET['orderby']) ? selected( $_GET['orderby'], $k, false ) : '',
            $ob
        );
    }
    ?>
</select>


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

