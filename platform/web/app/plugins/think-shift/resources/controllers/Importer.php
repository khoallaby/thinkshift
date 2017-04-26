<?php
namespace ThinkShift\Plugin;

use PHPExcel_IOFactory;


class Importer extends Base {
    public static $keys;


    public function __construct( $keys = [] ) {
        if( !empty($keys) )
            self::setKeys( $keys );

    }

    public function init() {

	}




    /**
     * Sets the keys array. Which converts the meta_key names used between the file and the data in the DB
     * @param $keys
     */
	public static function setKeys( $keys ) {
        if( file_exists($keys) ) {
            $file = file_get_contents( $keys );
            $keys = json_decode( $file, true );
            self::$keys = $keys;
        } else {
            self::$keys = $keys;
        }

        return $keys;
    }



    # echos out an array to use
	public static function getHeaders( $file, $string = false ) {

        $phpExcel = PHPExcel_IOFactory::load( $file );
        $worksheet = $phpExcel->getActiveSheet();

        //excel with first row header, use header as key
        $highestColumn = $worksheet->getHighestColumn();
        $headingsArray = $worksheet->rangeToArray( 'A1:' . $highestColumn . '1', null, true, true, true );
        if( $string )
            return "['" . implode("', '", $headingsArray[1]) . "']";
        else
            return $headingsArray[1];
    }


    /**
     * Parses a file into an array
     * https://gist.github.com/calvinchoy/5821235
     * @param string $file      Filename
     * @param bool $useHeaders  If there's a header column at row 1, use that as metakeys
     *
     * @return array
     */
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



    /**
     * Imports excel sheet into Careers CPT
     * @param $file
     * @return int  Number of posts imported
     */
    public static function importCareersIntoCpt( $file ) {
	    $careers = self::parseFile( $file );
	    $strengthKeys = [ 'value_type1', 'value_type2', 'value_type3' ];
        $ignoreKeys = [ 'occup', 'occdesc' ];
        $i = 0;


	    if( $careers ) {
            foreach( $careers as $career ) :
                $postTitle = $career['occup'];
                $postContent = $career['occdesc'];
                $strengths = $career2 = []; # this holds new potential new metakeys


                # prepare the data, convert the metakeys if necessary
                foreach( $career as $key => $value ) :
                    $key2 = $key;

                    # if we're using the json to map new keys, try and change the keyname
                    if( !empty( self::$keys ) ) {
                        if( $newKey = array_search( $key, self::$keys ) )
                            $key2 = $newKey;

                    }

                    # if our key isn't a strength or ignored
                    if( !in_array( $key2, array_merge($ignoreKeys, $strengthKeys) ) )
                        $career2[$key2] = $value;

                    # strengths - add to taxonomy via separate array. remove from original array
                    if( in_array( $key2, $strengthKeys ) )
                        $strengths[] = $value;

                    /*
                    # remove our ignored keys
                    if( in_array( $key, $ignoreKeys ) )
                        unset( $career[$key] );*/

                endforeach;


                $args = [
                    'post_title'  => $postTitle,
                    'post_content'  => $postContent,
                    'post_status' => 'publish',
                    'post_type'   => 'career',
                    'meta_input'  => $career2
                ];


                # check for existing post by title

                # update post if exists, else create a new one
                if( $exists = get_page_by_title( $postTitle, OBJECT, 'career' ) ) {
                    $postId = $args['ID'] = $exists->ID;
                    wp_update_post( $args );


                } else {
                    $postId = wp_insert_post( $args );
                    $i ++; # counter for new posts for funsies
                }

                # sets/updates the tags (strengths)
                if( !empty( $strengths ) )
                    wp_set_object_terms( $postId, $strengths, 'tag-category' );

            endforeach;
        }
        return $i;
    }



}

#add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Importer::get_instance(), 'init' ));