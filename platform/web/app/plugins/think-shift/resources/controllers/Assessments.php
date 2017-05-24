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
     * Sees what assessments the user can access, user can only access the first incompleted assessment
     *
     * @return array    The element with false, is the current one
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
        $tags = self::getAssessmentTags();
        $assesments = [];

        foreach ( $tags as $tag )
            $assesments[] = Users::userHasTag( $tag );

        return $assesments;
    }


    /**
     * Gets the key names (slug) of the Assessment Tags. Pulls from the Assessment's metadata
     */
    public static function getAssessmentTags() {
        global $wp_query;
        $tags = [];

        if( is_post_type_archive( 'assessment' ) ) {
            $posts = $wp_query->get_posts();
        } else {
            $posts = \ThinkShift\Plugin\Base::getPosts( 'assessment', [
                'orderby' => 'menu_order',
                'order' => 'ASC'
            ] );
        }

        foreach ( $posts as $post )
            $tags[] = get_post_meta( $post->ID, 'tag-complete', true );
        
        return $tags;

    }


    /**
     * Checks if User has completed the Assessment, $tag. Or all of them if $tag = null
     * @param null $tag         Name of the Assessment
     *
     * @return bool|\WP_Error
     */
    public static function hasUserCompletedAssessments( $tag = null ) {
        $tags = self::getAssessmentTags();
        if( $tag ) {
            return Users::userHasTag( $tag );
        } else {
            foreach( $tags as $tag ) {
                if( !Users::userHasTag( $tag ) )
                    return false;
            }
            return true;
        }
    }


}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Assessments::get_instance(), 'init' ));

