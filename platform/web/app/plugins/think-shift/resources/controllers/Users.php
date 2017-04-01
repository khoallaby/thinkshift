<?php
namespace ThinkShift;

use XooUserUltra;

class Users extends Base {

	public function init() {


		add_action( 'wp_login', array( $this, 'wp_login' ), 10, 2 );
		echo 'weeeeee';
		$xoouserultra = new XooUserUltra();
		$xoouserultra->plugin_init();
		echo 'weeeeee';
	}


	public function wp_login( $user_login, $user ) {
		var_dump($user_login);
		var_dump($user);
		die();

	}


}


add_action( 'plugins_loaded', array( \ThinkShift\Users::get_instance(), 'init' ));
