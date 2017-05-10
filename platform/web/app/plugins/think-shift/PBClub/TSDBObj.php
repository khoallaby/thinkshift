<?php
/**
 *
 */

class TSDBObj
{

    private $con;

    function __construct($host, $awsuser, $password, $database='mysql', $port='3306')
    {
        // Check connection

        $this->con=mysqli_connect($host, $awsuser, $password, $database, $port);
        if (mysqli_connect_errno()) {
            return mysqli_connect_errno();
        } else {
            return true;
        }
    }

    function __destruct()
    {
        mysqli_close($this->con);
    }

    public function ts_email_exists($email) { // Tested
        $query="CALL ts_email_exists('$email');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_contact_db_id_by_email($email) { // Tested
        $query="CALL ts_contact_db_id_by_email('$email');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_contact_exists($email){ // Tested
        $query="CALL ts_contact_exists('$email');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_set_email_status($email, $status){ // Tested
        $query="CALL ts_set_email_status('$email', $status);";
        $return=$this->con->query($query);
        return $return;
    }

    public function ts_email_create($address, $status){ // Tested
        $query="CALL ts_email_create('$address', '$status');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_contact_create($first, $last, $email){ // Tested
        $query="CALL ts_contact_create('$first', '$last', '$email');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_tag_create($name, $desc, $tcid){
        $query="CALL ts_tag_create('$name', '$desc', '$tcid');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_tag_category_create($name, $desc){
        $query="CALL ts_tag_category_create('$name', '$desc');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_custom_field_create($label, $dataType){
        $query="CALL ts_custom_field_create('$label', '$dataType');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }

    public function ts_tag_exists($name){
        $query="CALL ts_tag_exists('$name');";
        $return=$this->con->query($query);
        $rows=$return->fetch_array();
        return $rows[0];
    }


}
