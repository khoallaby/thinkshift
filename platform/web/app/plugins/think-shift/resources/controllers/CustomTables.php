<?php
namespace ThinkShift\Plugin;

use TSDBObj;



/**
 * Generic class for creating and interacting custom tables in WP
 *
 * https://deliciousbrains.com/managing-custom-tables-wordpress/
 * https://premium.wpmudev.org/blog/creating-database-tables-for-plugins/
 *
 */
class CustomTables extends Base {

    static $primary_key = 'id';
    static $table = '';
    static $prefix = '';
    static $db;
    static $output = OBJECT;

    protected function __construct() {
        #require dirname(__FILE__) . '/../vendor/autoload.php';

        # set up our instance of $db, so this can easily be linked to something else
        if( !isset(self::$db) ) {
            require_once dirname(__FILE__) . '/../../PBClub/TSDBObj.php';
            self::$db = new TSDBObj();
            #if( WP_DEBUG )
                #$wpdb->show_errors = true;
        }

        if( static::$table == null )
            static::$table = strtolower( get_called_class() );

        $this->register_activation_hook();
    }

    /**
     * Returns the table name with WP and custom $prefix
     * @return string
     */
    public static function _table() {
        return self::$db->prefix . static::$prefix . static::$table;
    }
    
    public static function _db() {
        return self::$db;
    }

    public static function _output( $type ) {
        if( $type )
            self::$output = $type;
        else
            self::$output = OBJECT;
    }




    /******************************************************************************************
     * CRUD functions
     ******************************************************************************************/




    public static function insert( array $data, $format = null ) {
        return self::$db->insert( self::_table(), $data, $format );
    }



    public static function query( $query ) {
        return self::$db->query( $query );
    }


    public static function update( $data, $where, $format = null, $where_format = null ) {
        return self::$db->update( self::_table(), $data, $where, $format, $where_format );

    }


    public static function delete( $where = array(), $where_format = null, $limit = null ) {
        return self::$db->delete( self::_table(), $where, $where_format = null );

    }





    /******************************************************************************************
     * Read/querying functions
     ******************************************************************************************/


    public static function get( $data = '*', $where = array(), $compare = array(), $order_by = '', $limit = null, $format = array() ) {
        $sql = self::createQuery( 'SELECT', $data, $where, $compare, $order_by, $limit, $format );
        return self::$db->get_results( $sql, self::$output );
    }



    public static function getRow( $data = '*', $where = array(), $compare = array(), $order_by = '', $format = array() ) {

        $sql = self::createQuery( 'SELECT', $data, $where, $compare, $order_by, null, $format );
        return self::$db->get_row( $sql, self::$output );
    }


    public static function getResults( $query ) {
        return self::$db->get_results( $query );
    }

    /**
     * Creates the SQL query based on the parameters below.
     * @param string $method    SELECT|DELETE
     * @param string $data      Field names
     * @param array $where      Array of variables to search for
     * @param array $compare    Array of comparison variables to use for the $where
     * @param string $order_by  Order by string
     * @param null $limit       Limit on rows return
     * @param array $format     Array for using with wpdb->prepare(), [ %d, %s, etc ]
     *
     * @return string
     */
    public static function createQuery( $method = 'SELECT', $data = '*', $where = array(), $compare = array(), $order_by = '', $limit = null, $format = array() ) {
        $sqlData = is_array( $data ) ? implode( ', ', $data ) : $data;


        $sql = $method . ' ' . $sqlData . ' FROM ' . self::_table();
        $sql .= !empty($where) ? self::createQueryWhere( $where, $compare, $format ) : '';
        $sql .= $limit ? sprintf( ' LIMIT %d' . $limit ) : '';

        return $sql;
    }


    /**
     * Builds the WHERE sql query using optional comparison operators
     * @todo: add support for OR where, numerical indices for $compare
     * @param array $where
     * @param array $compare
     */
    public static function createQueryWhere( array $where = array(), array $compare = array(), array $format = array() ) {
        if( !empty($where) ) {
            $sql = ' WHERE ';
            foreach ( $where as $k => $v ) {
                $formatValue = isset( $format[$k] ) ? $format[$k] : '%s';
                $compareValue = isset( $compare[$k] ) ? $compare[$k] : '';

                $sql .= self::$db->prepare( " $k = $formatValue $compareValue ", $v );

            }
            return $sql;
        }

        return false;

    }


    /**
     * Helper functions for dealing with DB and data
     */
    
    public static function last_insert_id() {
        return static::$db->insert_id;
    }

    public static function string_to_date( $date ) {
        return self::time_to_date( strtotime($date) );
    }

    public static function time_to_date( $time ) {
        return date( 'Y-m-d H:i:s', $time );
    }

    public static function now() {
        return self::time_to_date( time() );
    }

    public static function date_to_time( $date ) {
        return strtotime( $date . ' GMT' );
    }

    public static function remove_trailing_zeroes( $number ) {
        return rtrim( $number, "0" );
    }






    /**
     * Simplifies running the create_db() function on plugin activation
     * @param string $function  Name of function to run on plugin activation
     */
    public function register_activation_hook( $function = 'createDbSql' ) {
        register_activation_hook( thinkshift_plugin, [ get_called_class(), $function ] );
    }


    /**
     * Extend this function to create the SQL code for creating the table's columns
     */
    public static function createDbSql() {
        $sql = '';
        static::createDb( $sql );
    }


    /**
     * Create DB on plugin activation
     * @param string $sql  Sql code to run
     */
    public static function createDb( $sql = null ) {
        global $wpdb;

        if( $sql ) {
            $charset_collate = $wpdb->get_charset_collate();
            $table_name      = self::_table();

            $sqlCreateTable = "CREATE TABLE $table_name (" . "\n";
            $sqlCreateTable .= $sql;
            $sqlCreateTable .= "\n" . ") $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sqlCreateTable );

            # check to see if need to update
            self::updateDb( $sql, reignite_plugin_version );
        }
    }


    /**
     * Updates DB by calling create_db() if a newer version number is passed
     * @param string $sql           Sql code to run
     * @param float $newVersion     Version number to compare against
     */
    public static function updateDb( $sql = '', $newVersion ) {

        # pulls current plugin version out of wp_options, uses constant for default
        $currentVersion = get_option( 'reignite_plugin_version', reignite_plugin_version );

        # if older version, update DB, and update version number
        if ( version_compare( $currentVersion, $newVersion ) < 0 ) {
            update_option( 'reignite_plugin_version', $newVersion );
            self::createDb( $sql );
        }
    }
}

#add_action( 'plugins_loaded', array( \Reignite\Plugin\CustomTables::get_instance(), 'init' ));