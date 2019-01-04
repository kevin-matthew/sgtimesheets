<?php
require_once 'run/auth.php';
require_once 'lib/timesheets.php';
require_once 'lib/db.php';
$userID = $account->accountid; // Change once users are setup
$err_msg = "";

if(!empty($_POST['Submit'])) {
	if(insertTimesheet($_POST, 'attachment', $userID, $err_msg))
	{
		$err_msg = "<success>Successfully added new timesheet</success>";
	}
	else
	{
		$err_msg = "<error>" . $err_msg . "</error>";
	}
}

include 'run/header.php';
?>
<section>
<title>Add a new timesheet</title>
<main>
<h3>Add a new timesheet</h3>
	<form method="post" action="" enctype="multipart/form-data">
		<p class='usrin'>
	<label>Start Date</label>
		<input name="fromdate" type="date" value="<?=$timesheetData['fromdate'];?>">
	</p>
	<p class="usrin">
		<label>End Date</label>
		<input name="enddate" type="date" value="<?=$timesheetData['enddate']; ?>">
	</p>
	<p class="usrin">
		<label>Total Hours</label>
		<input name="totalhours" type="number" step="0.1" value="<?=$timesheetData['totalhours']; ?>">
	</p>
	<p class="usrin">
		<label>Attachment</label>
		<input type="file" name="attachment">
	</p>
	<?=$err_msg?>
		<input type="submit" name="Submit">
	</form>
	</main>
</section>
<?php
include ('../run/footer.php');
