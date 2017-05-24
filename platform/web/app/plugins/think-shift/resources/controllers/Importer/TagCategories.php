<?php
namespace ThinkShift\Plugin;


class ImportTagCategories extends Importer {


    public function init() {
        add_action( 'admin_action_thinkshift-importer', [ $this, 'parseUpload' ] );
	}


	public static function setTableKeys() {
        $columns = TagCategories::getTableColumns();
        $columnsExtra = [
        ];

        $columns = array_merge( $columns, $columnsExtra );

        static::$keys = static::setImportKeys( array_flip($columns) );
    }



	public static function importData( $rows = [] ) {
        if( $rows ) {
            foreach( $rows as $row ) :
                if( $user = get_user_by( 'email', $row['Email1'] ) )
                    $wpId = $user->ID;
                else
                    $wpId = null;

                $insertData = [
                    'Is_id'        => $row['Is_id'],
                    'Wp_id'        => $wpId,
                    'CategoryName' => $row['CategoryName'],
                    'CategoryDesc' => $row['CategoryDesc'],
                ];

                $insert = TagCategories::insert( $insertData );
            endforeach;
        }

    }


    public static function parseUpload() {
        if( $file = $_FILES['tag--categories-import-file']['tmp_name'] ) {
            self::setTableKeys();
            $data = static::parseFile( $file );
            self::importData( $data );
        }

    }






}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\ImportTagCategories::get_instance(), 'init' ));