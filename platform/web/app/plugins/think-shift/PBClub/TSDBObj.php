<?php
/**
 *
 */

class TSDBObj
{

    public $access, $refresh, $con;
    public static $api;

    function __construct($host, $awsuser, $password, $database='mysql', $port='3306')
    {
        global $con;
        require_once 'src/isdk.php';

        // Check connection
        $con=mysqli_connect($host, $awsuser, $password, $database, $port);

        self::$api = new iSDK();
        self::$api->setSecret('St9WnkKkk8');
        self::$api->setClientId('9sbtkn2vfjrr7cp93yaswgpq');
        self::$api->setPostURL("https://api.infusionsoft.com/crm/xmlrpc/v1");
        self::$api->setToken($this->ts_get_token('gv368'));
        self::$api->setRefreshToken($this->refresh);

        if (mysqli_connect_errno()) {
            return mysqli_connect_errno();
        } else {
            return true;
        }

    }

    function __destruct()
    {
        global $con;
        mysqli_close($con);
    }

    function close() {
        global $con;
        mysqli_close($con);
    }

    /*******************************************************************************
     *
     *                              Utility methods
     *
     * Email status codes for ts_set_email_status $status parameter
     *
     * 0 SingleOptIn - This person has opted in but not confirmed their email address
     * 1 UnengagedMarketable - This person has been unengaged for a period of time
     * 2 DoubleOptin - This person has clicked an email confirmation link.
     * 3 Confirmed - This person has confirmed their email address.
     * 4 UnengagedNonMarketable - This person has been unengaged for too long a period of time to be marketed to
     * 5 NonMarketable - There is no evidence that this person has consented to receive marketing.
     * 6 Lockdown - This person was added while the app was locked down.
     * 7 Bounce - This person's email address has bounced too many times.
     * 8 HardBounce - This person's email address has hard bounced.
     * 9 Manual - This person has opted out of all email marketing.
     * 10 Admin - This person was manually opted out by an administrator.
     * 11 ListUnsubscribe - This person has opted out of all email marketing.
     * 12 Feedback - This person reported spam messages to his/her provider.
     * 13 Spam - This person provided feedback when opting out.
     * 14 Invalid - This email address failed the regular expression validation
     *
     *******************************************************************************/

    /** get a tag id by name
     *
     *  This is used in the development environment condition only for accounting
     *  for the difference in tag id's between live and sandbox apps
     *
     **/
    public function ts_tag_id_by_name($name){ ## Tested
        global $con;
        $query="CALL ts_tag_id_by_name('$name');";
        $return=mysqli_query($con,$query);

        $rows=$return->fetch_array();

        return $rows[0];

    }

    // set the email status value by the email address
    public function ts_set_email_status($email, $status){ // Tested
        global $con;
        $query="CALL ts_set_email_status('$email', $status);";
        $return=mysqli_query($con,$query);
        return $return;
    }

    public function ts_set_wp_id($cid,$wpid){ // Tested
        global $con;
        $query="CALL ts_set_wp_id('$cid', '$wpid');";
        mysqli_query($con,$query);
    }

    public function ts_apply_tag($cid, $tid){ ## Tested
        global $con;
        $query="CALL ts_apply_tag('$cid', '$tid');";
        mysqli_query($con,$query);
    }

    public function ts_apply_tags($cid, $tags=[]){ ## Tested
        global $con;
        foreach ($tags as $tag){
            self::ts_apply_tag($cid,$tag);
        }
    }

    public function ts_send_token($appName, $access, $refresh){ ## Tested
        global $con;
        $query="CALL ts_send_tokens('$appName', '$access', '$refresh');";
        mysqli_query($con,$query);
    }

    public function ts_get_access_token($appName){ ## Tested
        global $con;
        $query="CALL ts_get_access_token('$appName');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_get_refresh_token($appName){ ## Tested
        global $con;
        $query="CALL ts_get_refresh_token('$appName');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    /*******************************************************************************
     *
     *                              IfExists methods
     *
     *******************************************************************************/

    // does email exist in the db?
    public function ts_email_exists($email) { ## Tested
        global $con;
        $query="CALL ts_email_exists('$email');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    // does the contact by email address exist?
    public function ts_contact_exists($email){ ## Tested
        global $con;
        $query="CALL ts_contact_exists('$email');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    // does a specific tag by the name exist
    public function ts_tag_exists($name){ ## Tested
        global $con;
        $query="CALL ts_tag_exists('$name');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    /*******************************************************************************
     *
     *                              FindBy methods
     *
     *******************************************************************************/

    // find the db id for the contact by the email address
    public function ts_contact_db_id_by_email($email) { ## Tested
        global $con;
        $query="CALL ts_contact_db_id_by_email('$email');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_contact_db_id_by_wp_id($wpid){ ## Tested
        global $con;
        $query="CALL ts_contact_db_id_by_wp_id('$wpid');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_tags_by_category($cid, $catId){
        global $con;
        $query="CALL ts_tags_by_category('$cid', '$catId');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows;
    }

    /*******************************************************************************
     *
     *                              CRUD methods
     *
     *******************************************************************************/

    /**
     * CRUD Email
     */

    // create a row on the email table with dup check
    public function ts_email_create($address, $status){ // Tested
        global $con;
        $query="CALL ts_email_create('$address', '$status');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    /**
     * CRUD Phone
     */

    /**
     * CRUD Tags
     */

    // create a row on the ContactGroup table with dup check
    public function ts_tag_create($name, $desc, $tcid){ ## Tested
        global $con;
        $query="CALL ts_tag_create('$name', '$desc', '$tcid');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    // create a row on the ContactGroupCategory table with dup check
    public function ts_tag_category_create($name, $desc){ ## Tested
        global $con;
        $query="CALL ts_tag_category_create('$name', '$desc');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_tag_read($tid){ ## Tested
        global $con;
        $query="CALL ts_tag_read($tid);";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows;
    }

    /**
     * CRUD Custom Fields
     */

    // create a row on the DataFormField table with dup check
    public function ts_custom_field_create($label, $dataType){
        global $con;
        $query="CALL ts_custom_field_create('$label', '$dataType');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    /**
     * CRUD Contact
     */

    // create a row on the Contact table with dup check by email address
    public function ts_contact_create($first, $last, $email){ // Tested
        global $con;
        $query="CALL ts_contact_create('$first', '$last', '$email');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_contact_read($dbid){ // Tested
        global $con;
        $query="CALL ts_contact_read('$dbid');";
        $return=mysqli_query($con,$query);
        $rows=$return->fetch_array();
        return $rows;
    }

    public function ts_get_token($name){ ## Tested
        global $con;
        $query = "SELECT AccessToken, RefreshToken FROM OAuth2 WHERE AppName='$name';";
        $token=mysqli_query($con,$query);

        $dat=$token->fetch_array();
        $this->refresh=$dat['RefreshToken'];
        $this->access=$dat['AccessToken'];
        return $this->access;
    }

}

