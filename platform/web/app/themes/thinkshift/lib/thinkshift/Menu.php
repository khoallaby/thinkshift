<?php
namespace ThinkShift\Theme\Menu;

use ThinkShift\Theme\Template;
use Walker;



class menuLoggedIn extends Walker {

    // Tell Walker where to inherit it's parent and id values
    var $db_fields = array(
        'parent' => 'menu_item_parent',
        'id'     => 'db_id'
    );

    /**
     * At the start of each element, output a <li> and <a> tag structure.
     *
     * Note: Menu objects include url and title properties, so we will use those.
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        #if( strtolower($item->title) == 'register' && is_user_logged_in() )
        #    return;

        $icon = '';



        $classes = $item->classes;

        # checks for font awesome icon, slices it out and applies that class to our <i>
        foreach( (array) $classes as $k => $class ) {
            if( substr( $class, 0, 3) == 'fa-' ) {
                $icon = array_slice( $classes, $k, 1 );
                $icon = $icon[0];
                unset( $classes[$k] );
            }
        }

        if( in_array( strtolower($item->title), ['log in', 'register', 'log out']) )
            $icon = 'fa-sign-out';


        if ( Template::isExternalPage( true ) )
            $icon = '';

        # adds font awesome icon html if exists
        if( !empty($icon) ) {
            $iconHtml = sprintf( '
                <div class="icon">
                    <i class="fa %s" aria-hidden="true"></i>
                </div> ', $icon
            );
        } else {
            $iconHtml = '';
        }

        # adds active class
        if( in_array( 'current-menu-item', $classes ) )
            $classes[] = 'active';


        $output .= sprintf( '
            <li class="%s">
                <a href="%s">%s
                    %s
                </a>
            </li>
            ',
            implode(' ', $classes),
            $item->url,
            $iconHtml,
            $item->title
        );
    }

}


class menuLoggedOut Extends \Walker_Nav_Menu {


    # removes the 'register' page for loggeed in users
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        if( strtolower($item->title) == 'register' && is_user_logged_in() )
            return;
        else
            parent::start_el( $output, $item, $depth, $args, $id );
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        if( strtolower($item->title) == 'register' && is_user_logged_in() )
            return;
        else
            parent::end_el( $output, $item, $depth, $args );
    }

}