<?php
namespace ThinkShift\Plugin;

use \ThinkShift\Plugin\Infusionsoft;

class Users extends Base {

	public function init() {
		parent::init();


		add_action( 'wp_login', array( $this, 'wpLogin' ), 10, 2 );

	}


	/*
	 * Runs on successful user log in
	 */
	public function wpLogin( $user_login, $user ) {
		if ( current_user_can( 'subscriber' ) ) {
			$infusionsoft = new \ThinkShift\Plugin\Infusionsoft();
			#print_r($user_login);
			#print_r($user);
		}


	}


}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
