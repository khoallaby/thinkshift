<?php

namespace ThinkShift\Plugin;

use iSDK;


class Infusionsoft extends base {
	private static $api;
	private $clientId, $clientSecret, $token, $apiKey;


	function __construct() {
		require_once dirname(__FILE__) . '/../../vendor/jimitit/infusionsoft-php-isdk/src/isdk.php';

		# todo: pull these from wp_options
		$appName = 'fd341';
		$this->apiKey = '9122d201f6892d5b3397f675849baafa';

		$this->connect( $appName, $this->apiKey );

	}


	function connect( $url, $apiKey ) {
		$this->apiKey = $apiKey;

		self::$api = new iSDK();
		self::$api->cfgCon( $url, $this->apiKey );
	}



	public function getUserFields() {
		return array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );
	}


	public static function getContactByEmail($email) {

		$table = 'Contact';
		$query = array('Email' => $email );
		$fields = self::getUserFields();

		$data = self::$api->dsQuery( $table, 1 ,0 , $query, $fields);

		if (is_array($data))
			return $data;
		else
			return false;
	}

	public function getContactById($id) {

		$table = 'Contact';
		$query = array('Id' => $id );
		$fields = array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );

		$data = self::$api->dsQuery( $table, 1 ,0 , $query, $fields);

		if (is_array($data))
			return $data;
		else
			return false;
	}



	public function addContact( $fields ) {

		$data = self::$api->addWithDupCheck( $fields, 'Email' );
		if( $data ) {
			/*
			# opt in email
			if ( isset( $fields['Email'] ) )
				self::$api->optIn( $fields['Email'] );
			*/
		}

		return $data;
	}



	public function getTagsByContactId( $contactId ) {
		return $this->getUserTags( array( 'Contact.Id' => $contactId ) );
	}

	public function getTagsByContactEmail( $contactEmail ) {
		return $this->getUserTags( array( 'Contact.Email' => $contactEmail ) );
	}


    /**
     * Gets all the tags of a Contact
     * @param $where
     * @return array|false
     */
	public function getUserTags( $where ) {

		# get Contact
		$data = self::$api->dsQuery( 'ContactGroupAssign', 10, 1, $where, ["Contact.Groups"] );

		# queries the Groups with list of IDs (Contact.Groups)

        if( !isset($data[0]["Contact.Groups"]) )
            return false;

		$groupsId = array_map( 'intval', explode(",", $data[0]["Contact.Groups"]));
		$groups = self::$api->dsQuery( 'ContactGroup', 10000, 0, ["Id" => $groupsId], ["GroupName", "GroupDescription", "GroupCategoryId"] );

		$groupsCat = array();

        # builds the array
		foreach ($groups as $group){
			$category = self::$api->dsFind("ContactGroupCategory", 1, 0, "Id", $group["GroupCategoryId"], ["CategoryName"]);
			if( isset($category[0]) )
				$group["CategoryName"] = $category[0]["CategoryName"];

			$groupsCat[] = $group;
		}
		return $groupsCat;

	}


    /**
     * Calls Infusionsoft, to get a user's tags by Category Name or ID
     * @param $category
     * @param $contactId
     *
     * @return array|false
     */
    public function getUserTagsByCategory( $category, $contactId ) {
        $key = is_int($category) ? 'GroupCategoryId' : 'CategoryName';
	    $tags = $this->getUserTags( array( 'Contact.Id' => $contactId ) );

        if( $tags ) {
            foreach ( $tags as $k => $tag ) {
                // careful, some might not have CategoryName
                if ( ! isset( $tag[ $key ] ) || $tag[ $key ] != $category ) {
                    unset( $tags[ $k ] );
                }
            }

            return $tags;
        } else {
            return false;
        }
    }



}


