<?php
namespace ThinkShift\Plugin;

use PHPExcel_IOFactory, PHPExcel_Cell;


class Importer extends Base {
	protected $the_title = 'the_title';

	public function init() {
		add_shortcode( 'ts_show_tags', array( $this, 'showTags' ) );

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

    public static function parseFileWithHeaders( $file ) {
	    $data = self::parseFile( $file );
    }


}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Importer::get_instance(), 'init' ));