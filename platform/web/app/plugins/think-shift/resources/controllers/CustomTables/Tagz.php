<?php
namespace ThinkShift\Plugin;




class Tagz extends CustomTables {

    static $table = 'ContactGroup';


    public function init() {
    }



    public static function getTableColumns() {

        $columns = [
            'Is_id'      => 'Infusionsoft ID',
            'Wp_id'      => 'Wordpress ID',
            'GroupName'  => 'Tag Name',
            'GroupDesc'  => 'Tag Description',
            'GroupCatId' => 'Tag Category ID',
        ];

        return $columns;

    }



    public static function insert( array $data, $format = null ) {
        return self::$tsdb->ts_tag_create( $data['GroupName'], $data['GroupDesc'], $data['GroupCatId'] );
    }
    

}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Tagz::get_instance(), 'init' ));