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
function updateTimesheet(array $post, string $attachmentname, int $timesheetid, string &$err_msg = '') {
	/*
	  Check if the time sheet is the user's/the user is an admin
	 */
	
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

	// Force totalhours into a float:
	$totalhours = (float)$post['totalhours'];

	if(!$attachmentlocation = uploadattachment($attachmentname, $err_msg)) {
		return false;
	}

	$db = getDB();

	$updateQuery = $db->prepare("UPDATE timesheets "
	. "SET fromdate = :fromdate, enddate = :enddate, totalhours = :totalhours, filelocation = :attachment "
	. "WHERE timesheetid = :timesheetid");
	
	if(!$updateQuery->execute(array(':fromdate'=>$fromdate, ':enddate'=>$enddate,
	':totalhours'=>$totalhours, ':timesheetid'=>$timesheetid, ':attachment'=>$attachmentlocation))) {
		$err_msg = "Something went wrong with the database, try again later.";
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

	// Force totalhours into a float:
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
?>