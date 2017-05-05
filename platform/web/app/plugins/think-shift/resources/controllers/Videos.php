<?php

namespace ThinkShift\Plugin;

use ThinkShift\Plugin\Users;


class Videos extends CustomPostTypes {
    public static $postType = 'Video';

    public function init() {
    }


    /**
     * Generic function to get videos
     * @param array $args
     * @param int $limit
     *
     * @return \WP_Query|\WP_User_Query
     */
    public static function getVideos( $args = [], $limit = 10 ) {
        $defaults = array (
            'posts_per_page' => $limit
        );

        $args = wp_parse_args( $args, $defaults );
        $videos = static::getQuery( self::$postType, $args );

        return $videos;
    }


    /**
     * Get Videos by their Tags, can search by tag id/slug/name
     * @param array $tags
     * @param int $limit
     * @param array $args
     *
     * @return \WP_Query|\WP_User_Query
     */
    public static function getVideosByTags( $tags = [], $limit = 10, $args = [] ) {
        $defaults = array (
            'posts_per_page' => $limit
        );
        $args = wp_parse_args( $args, $defaults );

        if( !empty($tags) ) {
            $field = is_int(array_values($tags)[0]) ? 'id' : 'name';
            $args['tax_query'] = static::getTaxQueryBy( $field, $tags );
        }

        $videos = self::getVideos( $args, $limit );

        return $videos;
    }





    /******************************************************************************************
     * Misc functions
     ******************************************************************************************/


    /**
     * Gets the Video's URL based on $source
     * @todo: Get rid of using ID, parse for youtube.com/vimeo.com and show accordingly
     * @param $id
     * @param string $source    (custom)/youtube/vimeo
     *
     * @return string           The URL to the Video
     */
    public static function getVideoLink( $id, $source = '' ) {

        switch( $source ) {
            case 'youtube':
                $url = 'https://www.youtube.com/embed/' . $id;
                break;
            case 'vimeo':
                $url = 'https://player.vimeo.com/video/' . $id;
                break;
            default:
                $url = $id;
                break;
        }


        return $url;

    }


    /**
     * Retrieves URL of the Video's thumbnail based on $source
     * @param $id
     * @param string $source    Youtube/Vimeo/etc
     * @param bool $asImage     Returns as an <img> tag or just the URL
     *
     * @return string           Link to the video as an absolute URL or <img> tag
     */
    public static function getVideoThummbnailLink( $id, $source = '', $asImage = false ) {

        switch( $source ) {
            case 'youtube':
                $imgUrl = 'https://img.youtube.com/vi/' . $id . '/0.jpg';
                break;
            case 'vimeo':
                # todo: vimeo thumbnail urls -- https://gist.github.com/tjFogarty/08cddb1854e2ae593bf0
                $imgUrl = '//lorempixel.com/340/225';
                break;
            default:
                $imgUrl = '';
                break;
        }


        if( !$asImage || $imgUrl == '' )
            return $imgUrl;


        if( isset($imgUrl) )
            $image = '<img src="' . esc_attr( $imgUrl ) . '" class="img-fluid" />';
        else
            $image = $imgUrl;


        return $image;

    }


}




#add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Videos::get_instance(), 'init' ));

