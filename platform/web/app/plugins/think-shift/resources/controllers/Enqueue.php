<?php

namespace ThinkShift\Plugin;

use iSDK,
    LogFileObj;


class Enqueue extends Infusionsoft {
    public static $debug, $log;

	function init( $debug = true ) {
	    parent::__construct();

        # todo: if live $debug = false;
        self::$debug = $debug;

	    if( self::$debug ) {
            require_once dirname( __FILE__ ) . '/logging/LogFileObj.php';

            self::$log = new LogFileObj( dirname( __FILE__ ) . '/logging/Enqueue.log' );
            self::$log->lfWriteLn( '***********************************************************' );
            self::$log->lfWriteLn( 'conEnqueue Process BEGIN :' );
        }

        #require_once dirname(__FILE__) . '/../../vendor/jimitit/infusionsoft-php-isdk/src/isdk.php';
        require_once dirname(__FILE__) . '/../../vendor/infusionsoft-oauth-isdk/src/isdk.php';




	}







    /******************************************************************************************
     * Enqueueing functions
     ******************************************************************************************/





    /**
     * Creates/writes a record in Infusionsoft. I.e. a tag or contact.  Based on priority,
     * the record will either be created directly (instantly) or enqueued and created later via a cron job.
     *
     * @param $priority = either CRITICAL (direct) or NON_CRITICAL (DB Queue) (see constants)
     * @param $table = binary assignment for the table (see constants)
     * @param $data = associative array of all correctly named fields for the table/action
     */
    public static function createInfusionsoftRecord( $table, $data, $priority = false ) {

        global $wpdb;

        if ( $priority == CRITICAL ) {

            if ( static::$debug )
                static::$log->lfWriteLn( 'CRITICAL flag submitted.  Directly writing to Infusionsoft' );

            if ( $table == CONTACT ) {
                //$return = static::$api->addWithDupCheck( $data, 'Email' );
                $return = static::addContact( $data, $priority );

                if ( static::$debug )
                    static::$log->lfWriteLn( 'addWithDupCheck return = ' . $return );
                
            } elseif ( $table == CONTACT_GROUP_ASSIGN ) {
                $return = static::$api->grpAssign( $data['ContactId'], $data['GroupId'] );
                if ( static::$debug )
                    static::$log->lfWriteLn( 'grpAssign return = ' . $return );
            }


        } elseif ( $priority == NON_CRITICAL ) {

            if ( static::$debug )
                static::$log->lfWriteLn( 'NON_CRITICAL flag submitted.  Writing request to queue database' );

            # todo: $wpdb->prepare the queries
            if ( $table == CONTACT ) {
                $json   = json_encode( $data );
                $query  = "INSERT INTO transfers (TName,JSON) VALUES ('$table','$json')";
                $result = $wpdb->query( $query );
                $return = $wpdb->insert_id;
            } elseif ( $table == CONTACT_GROUP_ASSIGN ) {
                $json   = json_encode( $data );
                $query  = "INSERT INTO transfers (TName,JSON) VALUES ('$table','$json')";
                $result = $wpdb->query( $query );
                $return = $wpdb->insert_id;
            }

            if ( static::$debug ) {
                static::$log->lfWriteLn( 'Query = ' . $query );
                static::$log->lfWriteLn( 'Enqueue INSERT INTO transfers result = ' . $result );
                static::$log->lfWriteLn( 'Enqueue INSERT INTO transfers insert_id = ' . $return );
            }

        }

    }

}


