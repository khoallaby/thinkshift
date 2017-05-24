<?php
namespace ThinkShift\Plugin;




class TagAssign extends CustomTables {

    static $table = 'ContactGroupAssign';


    public function init() {
    }



    public static function getTableColumns() {

        $columns = [
            'Contact_id'  => 'Infusionsoft User ID',
            'DateCreated' => 'Date Created',
            'GroupId'     => 'Tag ID',
        ];

        return $columns;

    }



    public static function insert( array $data, $format = null ) {
        #return self::$tsdb->ts_tag_category_create( $data['GroupName'], $data['GroupDesc'], $data['GroupCatId'] );
    }
    

}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\TagAssign::get_instance(), 'init' ));