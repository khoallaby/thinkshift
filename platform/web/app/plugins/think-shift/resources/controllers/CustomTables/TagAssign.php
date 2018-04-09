<?php
namespace ThinkShift\Plugin;


class TagAssign extends CustomTables {

    static $table = 'ContactGroupAssign';


    public function init() {
    }



    public static function getTableColumns() {

        $columns = [
            'ContactId'  => 'Infusionsoft User ID',
            'DateCreated' => 'Date Created',
            'GroupId'     => 'Tag ID',
        ];

        return $columns;

    }



    public static function insert( array $data, $format = null ) {
        # @todo: john needs to make ts_tag_assign_create()
        return self::$tsdb->ts_tag_assign_create( $data['ContactId'], $data['DateCreated'], $data['GroupId'] );
    }
    

}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\TagAssign::get_instance(), 'init' ));