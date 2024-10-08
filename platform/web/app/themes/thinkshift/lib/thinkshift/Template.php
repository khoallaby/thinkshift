<?php

namespace ThinkShift\Theme;

use \ThinkShift\Plugin\UserAuthentication;

class Template {

    public function init() {
    }







    /******************************************************************************************
     * Tools
     ******************************************************************************************/


    /**
     * Checks to see if is external page, show external page theme/content/etc
     * @param bool $homePageCheck   Checks if a logged in user is attempting to view the home page, show them dashboard instead
     *
     * @return bool
     */
    public static function isExternalPage ( $homePageCheck = false ) {
        if( $homePageCheck ) {
            # if logged in on front page, return false, so we can pull the real dashboard
            if( is_front_page() )
                return is_user_logged_in() /*&& current_user_can( UserAuthentication::$marketplaceAccess )*/ ? false : true;
        }
        return is_page_template( 'template-external.php' );
    }

    public static function getResponsiveImage( $post_id, $size = 'medium', $attr ) {
        $defaults = array (
            'col-xs' => 12,
            'col-sm' => 6,
            'col-md' => 4,
            'fluid' => false,
            'gutter' => '15px',
            'class' => 'img-responsive',
            'srcset' => '',
            'sizes' => ''
        );
        $attr = wp_parse_args( $attr, $defaults );

        # todo: set up srcset/sizes
        return get_the_post_thumbnail( $post_id, $size, $attr );

    }







    /******************************************************************************************
     * Helpful functions for templating
     ******************************************************************************************/



    /* Functions for displaying open/closing rows for unknown # of posts per row */

    /**
     * Echos opening div if first column
     * @param $i
     * @param $columns      Number of Columns
     * @param string $class Class name(s) to append
     */
    public static function echo_open_row( $i, $columns, $class = '' ) {
        if( $i % $columns == 0 )
            echo sprintf( '<div class="row %s">' . "\n", $class );
    }


    /**
     * Echos closing div if last column, or total posts reached
     * @param $i
     * @param $columns      Number of Columns
     * @param null $total   Total number of posts, usually $query->found_posts
     */
    public static function echo_close_row( $i, $columns, $total = null ) {
        if( $i % $columns == 0 || $i == $total )
            echo "</div>\n";
    }




}


#add_action( 'plugins_loaded', array( \ThinkShift\Theme\Template::get_instance(), 'init' ));