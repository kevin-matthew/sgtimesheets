<?php
require_once('db.php');

function updateTimesheet(array $post, int $timesheetid, &$err_msg) {
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

	$db = getDB();

	$updateQuery = $db->prepare("UPDATE timesheets "
	. "SET fromdate = :fromdate, enddate = :enddate, totalhours = :totalhours "
	. "WHERE timesheetid = :timesheetid");
	
	if(!$updateQuery->execute(array(':fromdate'=>$fromdate, ':enddate'=>$enddate,
	':totalhours'=>$totalhours, ':timesheetid'=>$timesheetid))) {
		$err_msg = "Something went wrong with the database, try again later.";
		return false;
	}
	
	return true;
}
?>