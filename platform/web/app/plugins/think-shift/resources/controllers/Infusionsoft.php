<?php

namespace ThinkShift\Plugin;

use iSDK;


class Infusion {
	private $api, $apiKey;
	private $clientId, $clientSecret, $token;


	function __construct( $url, $apiKey ) {


		require_once dirname(__FILE__) . '../../vendor/infusionsoft-php-isdk-master/src/isdk.php';

		error_reporting(E_ALL);
		error_reporting(-1);
		ini_set('error_reporting', E_ALL);

		$this->apiKey = $apiKey;

		$this->api = new iSDK();
		$this->api->cfgCon('fd341', $this->apiKey);


	}

	public function getUserFields() {
		return array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );
	}


	public function getContactByEmail($email) {

		$table = 'Contact';
		$query = array('Email' => $email );
		$fields = $this->getUserFields();

		$data = $this->api->dsQuery( $table, 1 ,0 , $query, $fields);

		if (is_array($data))
			return $data;
		else
			return false;
	}

	public function getContactById($id) {

		$table = 'Contact';
		$query = array('Id' => $id );
		$fields = array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );

		$data = $this->api->dsQuery( $table, 1 ,0 , $query, $fields);

		if (is_array($data))
			return $data;
		else
			return false;
	}



	public function addContact( $fields ) {

		$data = $this->api->addWithDupCheck( $fields, 'Email' );
		if( $data ) {
			/*
			# opt in email
			if ( isset( $fields['Email'] ) )
				$this->api->optIn( $fields['Email'] );
			*/
		}

		return $data;
	}



	public function getTagsByContactId( $contactId ) {
		return $this->getTagsByContact( array('Contact.Id'=> $contactId ) );
	}

	public function getTagsByContactEmail( $contactEmail ) {
		return $this->getTagsByContact( array('Contact.Email'=> $contactEmail ) );
	}


	public function getTagsByContact( $where ) {

		# get Contact
		$data = $this->api->dsQuery( 'ContactGroupAssign', 10, 1, $where, ["Contact.Groups"] );

		$groupsId = array_map( 'intval', explode(",", $data[0]["Contact.Groups"]));
		$groups = $this->api->dsQuery( 'ContactGroup', 10000, 0, ["Id" => $groupsId], ["GroupName", "GroupDescription", "GroupCategoryId"] );

		$groupsCat = array();

		foreach ($groups as $group){
			$category = $this->api->dsFind("ContactGroupCategory", 1, 0, "Id", $group["GroupCategoryId"], ["CategoryName"]);
			if( isset($category[0]) )
				$group["CategoryName"] = $category[0]["CategoryName"];

			$groupsCat[] = $group;
		}
		return $groupsCat;

	}


}



$appName = 'fd341';
$apiKey = '9122d201f6892d5b3397f675849baafa';

$infusionsoft = new Infusion( $appName, $apiKey );


$contactId = $infusionsoft->addContact( array(
	'FirstName' => 'Andy',
	'LastName' => 'Nguyen',
	'Email' => 'andrew.nguyen@colorado.edu'
) );

var_dump($contactId);

$contact = $infusionsoft->getContactById( $contactId );
var_dump($contact);




$contact = $infusionsoft->getContactByEmail( 'mminton@wethinkshift.org' );
var_dump($contact);
/*

$tags = $infusionsoft->getTagsByContactId( 81 );
var_dump($tags);
*/