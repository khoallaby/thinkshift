<?php

namespace ThinkShift\Plugin;



class Careers extends CustomPostTypes {

    public function init() {
        #add_action( 'wp_footer', array( $this, 'wpFooter' ) );
    }




    /******************************************************************************************
     * Actions/filters, i.e. for user log in/registration
     ******************************************************************************************/






}


add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Careers::get_instance(), 'init' ));

