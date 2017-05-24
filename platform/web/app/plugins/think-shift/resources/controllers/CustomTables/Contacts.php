<?php
namespace ThinkShift\Plugin;



class Contacts extends CustomTables {

    static $table = 'Contacts';


    public function init() {
    }



    public static function getTableColumns() {

        $columns = [
            'Is_id'       => 'Infusionsoft ID',
            'Wp_id'       => 'Wordpress ID',
            'FirstName'   => 'First Name',
            'LastName'    => 'Last Name',
            'Email1'      => 'Email1',
            'PostalCode'  => 'Postal Code',
            'Tags'        => 'Tags',
            'CreatedBy'   => 'Created By',
            'DateCreated' => 'Date Created',
            'LastUpdated' => 'Last Updated',
            'OwnerID'     => 'Owner ID',
            'Leadsource'  => 'Lead Source',
        ];

        return $columns;

    }


    public static function getContacts() {
        return self::get();
    }

}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Contacts::get_instance(), 'init' ));