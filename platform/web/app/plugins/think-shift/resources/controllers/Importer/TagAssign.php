<?php
namespace ThinkShift\Plugin;


class ImportTagAssign extends Importer {


    public function init() {
        add_action( 'admin_action_thinkshift-importer', [ $this, 'parseUpload' ] );
	}


	public static function setTableKeys() {
        $columns = TagAssign::getTableColumns();
        $columnsExtra = [
        ];

        $columns = array_merge( $columns, $columnsExtra );

        static::$keys = static::setImportKeys( array_flip($columns) );
    }



	public static function importData( $rows = [] ) {
        if( $rows ) {
            foreach( $rows as $row ) :
                $insertData = [
                    'ContactId'   => $row['ContactId'],
                    'DateCreated' => $row['DateCreated'],
                    'GroupId'     => $row['GroupId'],
                ];

                $insert = TagAssign::insert( $insertData );
            endforeach;
        }
    }


    public static function parseUpload() {
        if( $file = $_FILES['tag-assign-import-file']['tmp_name'] ) {
            self::setTableKeys();
            $data = static::parseFile( $file );
            self::importData( $data );
        }

    }






}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\ImportTagAssign::get_instance(), 'init' ));