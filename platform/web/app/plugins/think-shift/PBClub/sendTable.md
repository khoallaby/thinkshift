The sendTable function uses priority, table assignment and direct
updates or stored json for later retrieval using metered updates to
Infusionsoft.  The parameters are as follows:

 * @param $priority = either CRITICAL (direct) or NON_CRITICAL (DB Queue) (see constants)
    * Contants are either CRITICAL or NON_CRITICAL
 * @param $table = binary assignment for the table (see constants)
    * Table constants currently are CONTACT, CONTACT_GROUP or CONTACT_GROUP_ASSIGN
      (CONTACT_GROUP is a placeholder as it is not yet implemented)
 * @param $data = associative array of all correctly named fields for the table/action
    * In the case of the contact array it will be elemented by the actual table field names.
      In the case of applying a tag to a contact, use the associative names ContactId and GroupId
 
<code> 
function sendTable($priority, $table, $data) <br>{
&nbsp;&nbsp;global $con, $log, $debug, $app;

	if ($priority==CRITICAL) {
		if ($table==CONTACT) {

			$return=$app->addWithDupCheck($data, 'Email');
			if ($debug) {
				$log->lfWriteLn('addWithDupCheck results = '.$return);
			}

		} elseif ($table==CONTACT_GROUP_ASSIGN) {

			$return=$app->grpAssign($data['ContactId'],$data['GroupId']);
			if ($debug) {
				$log->lfWriteLn('grpAssign results = '.$return);
			}

		}
	} elseif ($priority==NON_CRITICAL) {

		$json=json_encode($data);
		$query="INSERT INTO transfers (TName,JSON) VALUES ('$table','$json')";
		$result=mysqli_query($con,$query);
		$return=$con->insert_id;
		if ($debug) {
			$log->lfWriteLn('Enqueue INSERT INTO transfers result = '.$result);
			$log->lfWriteLn('Enqueue INSERT INTO transfers insert_id = '.$return);
		}
	}
}</code>