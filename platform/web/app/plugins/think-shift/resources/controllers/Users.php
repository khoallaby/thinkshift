<?php
namespace ThinkShift\Plugin;


class Users extends Base {
	private static $infusionsoft;


	public function init() {
		parent::init();

		self::$infusionsoft = new \ThinkShift\Plugin\Infusionsoft();

		add_action( 'wp_login', array( $this, 'wpLogin' ), 10, 2 );
		add_action( 'wp_register', array( $this, 'wpRegister' ), 1 );

	}



	/*
	 * Runs on successful user Log in
	 */
	public function wpLogin( $user_login, $user ) {
		#if ( current_user_can( 'subscriber' ) ) {
			$fields = $this->parseFields( $user->ID );
			$this->addInfusionsoftContact( $user->ID, $fields );
		#}
	}

	/*
	 * Runs on successful user registration
	 */
	public function wpRegister( $userId ) {
			$fields = $this->parseFields( $userId );
			$this->addInfusionsoftContact( $userId, $fields );
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


	public function addInfusionsoftContact( $userId, $fields ) {

		$contactId = self::$infusionsoft->addContact( $fields );
		if( $contactId )
			update_user_meta( $userId, 'infusionsoft_id', $contactId );

	}


	public static function getUserTags( $userId ) {
		$contactId = get_user_meta( $userId, 'infusionsoft_id', true );

		# todo: save tags somewhere later
		if( $contactId )
			return self::$infusionsoft->getTagsByContactId( $contactId );
		else
			return false;

	}


}

add_action( 'plugins_loaded', array( \ThinkShift\Plugin\Users::get_instance(), 'init' ));
