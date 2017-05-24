<?php
namespace ThinkShift\Plugin;




class TagCategories extends CustomTables {

    static $table = 'ContactGroupCategory';


    public function init() {
    }



    public static function getTableColumns() {

        $columns = [
            'Is_id'        => 'Infusionsoft ID',
            'Wp_id'        => 'Wordpress ID',
            'CategoryName' => 'Tag Name',
            'CategoryDesc' => 'Tag Description',
        ];

        return $columns;

    }



    public static function insert( array $data, $format = null ) {
        return self::$tsdb->ts_tag_category_create( $data['GroupName'], $data['GroupDesc'] );
    }
    

}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\TagCategories::get_instance(), 'init' ));