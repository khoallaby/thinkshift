<?php

namespace ThinkShift\Plugin;


class Assessments extends CustomPostTypes {

    public function init() {
    }


    private static function boolArray( $bool, $array ) {
        $boolString = $bool ? 'true' : 'false';
        return array_map( '__return_' . $boolString, $array );
    }


    /**
     * Sees what assessments the user can access
     * @return array
     */
    public static function canAccess() {
        $assesments = self::getCompletedStatus();

        # if all are false, set the first one as true
        if( array_sum($assesments) == 0 ) {
            $assesments = self::boolArray( true, $assesments );
            $assesments[0] = false;

        } else {
            # find where first incomplete assessment is
            foreach( $assesments as $k => $assesment ) {
                if( $assesment == false ) {
                    if( !isset($firstIndex) )
                        $firstIndex = $k;
                }
            }

            # mark all as complete except the first incomplete one
            if( isset($firstIndex) ) {
                # make all false first
                $assesments = self::boolArray( true, $assesments );
                # then make the first assessment that was true, the only true one
                $assesments[ $firstIndex ] = false;
            }

        }

        return $assesments;

    }


    /**
     * Checks to see if the User has the Assessment's 'tag-complete' tag
     * @return array
     */
    public static function getCompletedStatus() {
        global $wp_query;
        $assesments = [];

        foreach ( $wp_query->get_posts() as $post ) {
            $completeTag = get_post_meta( $post->ID, 'tag-complete', true );
            $assesments[] = Users::userHasTag( $completeTag );
        }

        return $assesments;
    }


}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Assessments::get_instance(), 'init' ));

