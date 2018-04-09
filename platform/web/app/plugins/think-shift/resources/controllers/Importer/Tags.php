<?php
namespace ThinkShift\Plugin;

use \TSDBObj;

class ImportTags extends Importer {


    public function init() {
        add_action( 'admin_action_thinkshift-importer', [ $this, 'parseUpload' ] );
	}


	public static function setTableKeys() {
        $columns = Tag::getTableColumns();
        $columnsExtra = [
        ];

        $columns = array_merge( $columns, $columnsExtra );

        static::$keys = static::setImportKeys( array_flip($columns) );
    }



	public static function importData( $rows = [] ) {

        if( $rows ) {
            foreach( $rows as $row ) :
                if( $user = Tags::getTagBy( 'slug', $row['GroupName'] ) )
                    $wpId = $user->ID;
                else
                    $wpId = null;

                $insertData = [
                    'Is_id'       => $row['Is_id'],
                    'Wp_id'       => $wpId,
                    'GroupName'   => $row['GroupName'],
                    'GroupDesc'   => $row['GroupDesc'],
                    'GroupCatId'  => $row['GroupCatId'],
                ];

                $insert = Tag::insert( $insertData );
            endforeach;
        }

    }


    public static function parseUpload() {
        if( $file = $_FILES['tags-import-file']['tmp_name'] ) {
            self::setTableKeys();
            $data = static::parseFile( $file );
            self::importData( $data );
        }

    }






}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\ImportTags::get_instance(), 'init' ));