<?php
namespace ThinkShift\Plugin;

use \TSDBObj;

class ImportContacts extends Importer {


    public function init() {
        add_action( 'admin_action_thinkshift-importer', [ $this, 'parseUpload' ] );
	}


	public static function setTableKeys() {
        $columns = Contacts::getTableColumns();
        $columnsExtra = [
        ];

        $columns = array_merge( $columns, $columnsExtra );

        static::$keys = static::setImportKeys( array_flip($columns) );
    }



	public static function importData( $rows = [] ) {

        if( $rows ) {

            foreach( $rows as $row ) {


                if( $user = get_user_by( 'email', $row['Email1'] ) )
                    $wpId = $user->ID;
                else
                    $wpId = null;

                $insertData = [
                    'Is_id'       => $row['Is_id'],
                    'Wp_id'       => $wpId,
                    'FirstName'   => $row['FirstName'],
                    'LastName'    => $row['LastName'],
                    'Email1'      => $row['Email1'],
                    'PostalCode'  => $row['PostalCode'],
                    'Tags'        => $row['Tags'],
                    'CreatedBy'   => $row['CreatedBy'],
                    'DateCreated' => $row['DateCreated'],
                    'LastUpdated' => $row['LastUpdated'],
                    'OwnerID'     => $row['OwnerID'],
                    'Leadsource'  => $row['Leadsource'],
                ];


                #$insert = Contacts::insert( $insertData );
                $insert = Contacts::insert( $insertData );

            }
        }

    }


    public static function parseUpload() {
        if( $file = $_FILES['contacts-import-file']['tmp_name'] ) {
            self::setTableKeys();
            $data = static::parseFile( $file );
            self::importData( $data );
        }

    }






}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\ImportContacts::get_instance(), 'init' ));