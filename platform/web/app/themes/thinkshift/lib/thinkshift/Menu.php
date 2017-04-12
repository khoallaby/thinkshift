<?php
namespace ThinkShift\Theme;

use Walker;



class Menu extends Walker {

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
        $icon = 'fa-tasks';
        $output .= sprintf( '
            <li%s>
                <a href="%s">
                    <div class="icon">
                        <i class="fa %s" aria-hidden="true"></i>
                    </div> 
                    %s
                </a>
            </li>
            ',
            ( $item->object_id === get_the_ID() ) ? ' class="active"' : '',
            $item->url,
            $icon,
            $item->title
        );
    }

}