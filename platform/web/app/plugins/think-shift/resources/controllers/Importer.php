<?php
namespace ThinkShift\Plugin;

use PHPExcel_IOFactory;


class Importer extends Base {

	public function init() {

	}


	# https://gist.github.com/calvinchoy/5821235
    public static function parseFile( $file, $useHeaders = true ) {

        $phpExcel = PHPExcel_IOFactory::load( $file );
        $worksheet = $phpExcel->getActiveSheet();

        //excel with first row header, use header as key
        if ( $useHeaders ) {
            $highestRow    = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            $headingsArray = $worksheet->rangeToArray( 'A1:' . $highestColumn . '1', null, true, true, true );
            $headingsArray = $headingsArray[1];
            $dataArray     = array();
            $r             = - 1;

            for ( $row = 2; $row <= $highestRow; ++ $row ) {
                $dataRow = $worksheet->rangeToArray( 'A' . $row . ':' . $highestColumn . $row, null, true, true, true );
                if ( ( isset( $dataRow[ $row ]['A'] ) ) && ( $dataRow[ $row ]['A'] > '' ) ) {
                    ++ $r;
                    foreach ( $headingsArray as $columnKey => $columnHeading )
                        $dataArray[ $r ][ $columnHeading ] = $dataRow[ $row ][ $columnKey ];
                }
            }
        } else {
            $dataArray = $worksheet->toArray( null, true, true, true );
        }

        return $dataArray;

    }

    public static function importCareersIntoCpt( $file ) {
	    $ignoreKeys = ['occup'];

	    $careers = self::parseFile( $file );
	    if( $careers ) {
            foreach( $careers as $career ) {

                $postTitle = $career['occup'];

                # unset our ignored keys
                foreach( $career as $key => $value ) {
                    if( in_array( $key, $ignoreKeys ) )
                        unset($career[$key] );
                }

                $args = [
                    'post_title'  => $postTitle,
                    'post_status' => 'publish',
                    'post_type'   => 'career',
                    'meta_input'  => $career
                ];

                wp_insert_post( $args );

            }
        }
    }



}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Importer::get_instance(), 'init' ));