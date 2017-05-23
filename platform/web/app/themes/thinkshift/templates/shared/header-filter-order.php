<?php
/**
 * unused for now. used for generating ordering dropdowns
 */
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

<select name="education_min">
    <?php foreach( $educationMin as $k => $ob ) {
        echo sprintf( '<option value="%s" %s>%s</option>',
            $k,
            isset($_GET['education_min']) ? selected( $_GET['education_min'], $k, false ) : '',
            $ob
        );
    }
    ?>
</select>
<br />

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
<br />
<br />

<?php #global $wpdb; print($wpdb->last_query); vard($wpdb); ?>