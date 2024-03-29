<?php
require_once('db.php');
require_once('attachment.php');

/**
 * Updates a certain timesheet with given post data.
 *
 * @param array $post
 *  Array of proper post data. Should include names fromdate, enddate,
 *  totalhours, and attachment.
 * @param int $timesheetid
 *  ID of the timesheet to modify.
 * @param string &$err_msg
 *  Pointer to a string variable that will store an error message
 *  if anything fails.
 */
function updateTimesheet(array $post
, string $attachmentname
, int $timesheetid
, int $userid
, int $isadmin = 0
, string &$err_msg = '') {
	if(!_validate($post, $err_msg)) {
		return false;
	}
	
	$fromdate = $post['fromdate'];
	$enddate = $post['enddate'];
	$totalhours = (float)$post['totalhours'];

	// Check to make sure that this user has permission to edit this
	// timesheet.
	if(!$isadmin)
	{
		$res = getDB()->query("select timesheetid from timesheets 
where userid=$userid and $timesheetid=$timesheetid");
		if(!$res || !$res->fetch(2))
		{
			$err_msg = "You do not have permission to edit this timesheet.";
			return false;
		}
		
	}

	if(!$attachmentlocation = uploadattachment($attachmentname, $err_msg)) {
		return false;
	}

	$db = getDB();

	$updateQuery = $db->prepare("UPDATE timesheets "
	. "SET fromdate = :fromdate, enddate = :enddate, totalhours = :totalhours, filelocation = :attachment "
	. "WHERE timesheetid = :timesheetid");
	
	if(!$updateQuery->execute(array(
		':fromdate'=>$fromdate
		,':enddate'=>$enddate
		,':totalhours'=>$totalhours
		,':timesheetid'=>$timesheetid
		,':attachment'=>$attachmentlocation)
	))
	{
		$err_msg = "Something went wrong with the database, try again later.";
		return false;
	}

	

	if(!$updateQuery->rowCount())
	{
		$err_msg = "Database exeucted successfully, but no record was modified.";
		return false;
	}
	
	return true;
}

/**
 * Inserts a new timesheet with given post data and userid.
 *
 * @param array $post
 *  Array of proper post data. Should include names fromdate, enddate,
 *  totalhours, and attachment.
 * @param int $userID
 *  ID of the user inserting the timesheet.
 * @param string &$err_msg
 *  Pointer to a string variable that will store an error message
 *  if anything fails.
 */
function insertTimesheet(array $post, string $attachmentname, int $userID, string &$err_msg = '') {
	if(!_validate($post, $err_msg)) {
		return false;
	}
	
	$fromdate = $post['fromdate'];
	$enddate = $post['enddate'];
	$totalhours = (float)$post['totalhours'];

	if(!$attachmentlocation = uploadattachment($attachmentname, $err_msg)) {
		return false;
	}
	
	$db = getDB();
	
	$insert = $db->prepare("INSERT INTO timesheets (userid, fromdate, enddate, totalhours, filelocation) "
		."VALUES (?,?,?,?,?)");
	
	if(!$insert->execute(array($userID, $fromdate, $enddate, $totalhours, $attachmentlocation))) {
		$err_msg = "Something went wrong with the database, try again later.";
		return false;
	}

	return true;
}

function _validate(array $post, string &$err_msg = '') {
	// Validate start date:
	$date = explode("-", $post['fromdate']);
	if(count($date) !== 3) {
		$err_msg = "The start date is an invalid date.";
		return false;
	}
	if(!checkdate($date[1], $date[2], $date[0])) {
		$err_msg = "The start date is an invalid date.";
		return false;
	}
	$fromdate = $post['fromdate'];

	// Validate end date:
	$date = explode("-", $post['enddate']);
	if(count($date) !== 3) {
		$err_msg = "The end date is an invalid date.";
		return false;
	}
	if(!checkdate($date[1], $date[2], $date[0])) {
		$err_msg = "The end date is an invalid date.";
		return false;
	}
	$enddate = $post['enddate'];

	// Make sure enddate is after fromdate:
	if(strtotime($enddate) < strtotime($fromdate)) {
		$err_msg = "The end date can't be a date before the start date.";
		return false;
	}

	$totalhours = (float)$post['totalhours'];
	if($totalhours < 0) {
		$err_msg = "Total hours cannot be a negative number.";
		return false;
	}
	
	return true;
}
?>