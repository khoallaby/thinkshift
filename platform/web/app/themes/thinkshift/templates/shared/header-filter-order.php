<?php
$orderby = [
    '' => '-- Order By --',
    'salary' => 'Average Salary'
];
$order = [
    '' => '-- Sort --',
    'asc' => 'Lowest to Highest',
    'desc' => 'Highest to Lowest'
];
?>
<select name="orderby">
    <?php foreach( $orderby as $k => $ob ) {
        echo sprintf( '<option value="%s" %s>%s</option>',
            $k, selected( $_GET['orderby'], $k, false ), $ob
        );
    }
    ?>
</select>


<select name="order">
    <?php foreach( $order as $k => $o ) {
        echo sprintf( '<option value="%s" %s>%s</option>',
            $k, selected( $_GET['orderby'], $k, false ), $o
        );
    }
    ?>
</select>

