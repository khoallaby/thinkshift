<?php
namespace ThinkShift\Plugin;


class Users extends Base {

	public function init() {
		parent::init();


		add_action( 'wp_login', array( $this, 'wpLogin' ), 10, 2 );
		add_action( 'wp_register', array( $this, 'wpRegister' ), 1 );

	}



	/*
	 * Runs on successful user registration
	 */
	public function wpLogin( $user_login, $user ) {
		if ( current_user_can( 'subscriber' ) ) {
			$fields = $this->parseFields( $user->ID );
			$this->addContact( $user->ID, $fields );
		}
	}

	/*
	 * Runs on successful user registration
	 */
	public function wpRegister( $userId ) {
			$fields = $this->parseFields( $userId );
			$this->addContact( $userId, $fields );
	}


	public function parseFields( $userId ) {
		# if regular login
		#if( true ) {
			$userMeta = get_userdata( $userId );
			$fields = array(
				'FirstName' => $userMeta->first_name,
				'LastName' => $userMeta->last_name,
				'Email' => $userMeta->user_email
			);
		#}
		# todo: add parse for social media logins

		return $fields;

	}


	public function addContact( $userId, $fields ) {
		$infusionsoft = new \ThinkShift\Plugin\Infusionsoft();

		$contactId = $infusionsoft->addContact( $fields );
		if( $contactId )
			update_user_meta( $userId, 'infusionsoft_id', $contactId );

	}


}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
