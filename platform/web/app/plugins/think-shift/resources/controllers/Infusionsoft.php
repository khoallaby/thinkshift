<?php

namespace ThinkShift\Plugin;

use iSDK;


class Infusionsoft extends base {
	public static $api;
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


    /**
     * General function to use for querying the api, in case SDK changes
     * https://developer.infusionsoft.com/docs/xml-rpc/#data-query-a-data-table
     *
     * @param $table                The name of the $table
     * @param $limit                Limit on rows returned
     * @param $page                 Starts on page
     * @param $query                The query, defaults to pulling all records (where ID != 0)
     * @param $fields               Array of field names to retrieve
     * @param string $orderByField  Orders by this field name
     * @param bool $ascending       Orders by ascending (true), or descending (false)
     *
     * @return mixed
     */
    public static function apiQuery( $table, $limit = 100, $page = 0, $query = [], $fields = [], $orderByField = '', $ascending = TRUE ) {

        if( empty( $query ) )
            $query = ['Id' =>  '~<>~0']; # this lets us grab ALL the fields (translates to WHERE Id != 0)

	    if( empty( $orderByField ) )
            return self::$api->dsQuery( $table, $limit, $page, $query, $fields );
	    else
            return self::$api->dsQueryOrderBy( $table, $limit, $page, $query, $fields, $orderByField, $ascending );


    }




    /******************************************************************************************
     * Contact/users related
     ******************************************************************************************/

	public function getUserFields() {
		return array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );
	}


	public static function getContactByEmail($email) {

		$table = 'Contact';
		$query = array('Email' => $email );
		$fields = self::getUserFields();

		$data = self::apiQuery( $table, 1 ,0 , $query, $fields );

		if (is_array($data))
			return $data;
		else
			return false;
	}

	public function getContactById($id) {

		$table = 'Contact';
		$query = array('Id' => $id );
		$fields = array( 'Id', 'Email', 'FirstName', 'LastName', 'City', 'State' );

		$data = self::apiQuery( $table, 1 ,0 , $query, $fields);

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






    /******************************************************************************************
     * Tags
     ******************************************************************************************/


    /**
     * Gets all the Tag categories
     * @return array
     */
    public function getAllTagCategories() {

        $table      = 'ContactGroupCategory';
        $where      = [ 'Id' => '~<>~0' ];
        $fields     = [ 'Id', 'CategoryName', 'CategoryDescription' ];
        $categories = self::apiQuery( $table, 1000, 0, $where, $fields, 'Id' );

        return $categories;

    }


    /**
     * Gets all the Tags
     * @return array
     */
    public function getAllTags() {

        $table  = 'ContactGroup';
        $where  = [ 'Id' => '~<>~0' ];
        $fields = [ 'Id', 'GroupName', 'GroupCategoryId', 'GroupDescription' ];
        $tags   = self::apiQuery( $table, 1000, 0, $where, $fields, 'Id' );

        return $tags;

    }


    /**
     * Gets all the Tags for all Users
     * @return array
     */
    public function getAllUserTags() {

        $table  = 'ContactGroupAssign';
        $where  = [ 'Contact.Id' => '~<>~0' ];
        $fields = [ 'Contact.Id', 'Contact.Email', 'Contact.Groups' ];
        $tags   = self::apiQuery( $table, 1000, 0, $where, $fields, 'Contact.Id' );

        return $tags;

    }


    /**
     * Gets a user's Tags by their Contact ID
     * @param $contactId
     * @return array|false
     */
    public function getTagsByContactId( $contactId ) {
		return $this->getUserTags( array( 'Contact.Id' => $contactId ) );
	}

    /**
     * Gets a user's Tags by their Email
     * @param $contactId
     * @return array|false
     */
	public function getTagsByContactEmail( $contactEmail ) {
		return $this->getUserTags( array( 'Contact.Email' => $contactEmail ) );
	}


    /**
     * Gets all the tags of a Contact
     * @param $where
     * @return array|false
     */
	public function getUserTags( $where ) {

        $groupIds = $groupCats = [];

		# get Contact groupIDs. -- "Groups" is a prebuilt array, GroupId is a row per group

        #$data = self::apiQuery( 'ContactGroupAssign', 1000, 0, $where, [ /*'Contact.Email', 'Contact.FirstName', 'ContactGroup',*/ 'GroupId' ] );
		$data = self::apiQuery( 'ContactGroupAssign', 1, 0, $where, [ 'Contact.Groups' ] );


        # builds our groupIds
        if( isset($data[0]["Contact.Groups"]) ) {
            $groupIds = array_map( 'intval', explode(",", $data[0]["Contact.Groups"]));
        } elseif( isset($data[0]["GroupId"]) ) {
            foreach( $data  as $datum )
                $groupIds[] = intval( $datum['GroupId'] );
        }


        if( empty($groupIds) ) {
            return [];
        } else {
            # queries the Groups with list of IDs (Contact.Groups)
            $groups = self::apiQuery( 'ContactGroup', 10000, 0, [ "Id" => $groupIds ], [
                "GroupName",
                "GroupDescription",
                "GroupCategoryId"
            ] );

            # builds the array
            foreach ( (array) $groups as $group ) {
                $category = self::$api->dsFind( "ContactGroupCategory", 1, 0, "Id", $group["GroupCategoryId"], [ "CategoryName" ] );
                if ( isset( $category[0] ) )
                    $group["CategoryName"] = $category[0]["CategoryName"];
                $groupCats[] = $group;
            }

            return $groupCats;
        }

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


