<?php

namespace ThinkShift\Plugin;


class Videos extends CustomPostTypes {

    public function init() {
    }


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


    public static function getVideoThummbnailLink( $id, $source = '', $asImage = false ) {

        switch( $source ) {
            case 'youtube':
                $imgUrl = 'https://img.youtube.com/vi/' . $id . '/0.jpg';
                break;
            case 'vimeo':
                # todo: vimeo thumbnail urls -- https://gist.github.com/tjFogarty/08cddb1854e2ae593bf0
                $imgUrl = '';
                break;
            default:
                $imgUrl = '';
                break;
        }


        if( !$asImage )
            return $asImage;


        if( isset($imgUrl) )
            $image = '<img src="' . esc_attr( $imgUrl ) . '" class="img-fluid" />';
        else
            $image = $imgUrl;

        return $image;

    }


    public static function getVideos() {
        $videos = get_post_meta( get_the_ID(), 'videos', true );
        return $videos;
    }


    public static function filterVideos() {
        $videos = self::getVideos();
        foreach( $videos as $video ) {
            vard($video);
        }
        return $videos;

    }
}




#add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Videos::get_instance(), 'init' ));

