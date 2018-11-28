<?php
if(!empty($_POST['Submit'])) {
	include ('../lib/timesheets.php');
	updateTimesheet($_POST);
	/*
	  Send to the home page with a success message.
	 */
}
	 
include ('../lib/db.php');
$db = getDB();

$timesheetID = (int)$_GET['id'];

$userID = 1; // Change once users are setup

$timesheetQuery = $db->query("SELECT * FROM timesheets WHERE timesheetid = ".$timesheetID.";");
$timesheetData = $timesheetQuery->fetch(2);

if((int)$timesheetData['userid'] !== $userID) { // Add condition if the user is an admin
	print "Nope";
	/* 
	Send to home with an error message saying something like
	"Aw hell naw, mufucka. No you di'n't. Don't thank 'bout edit'n
	dat timesheet."
	*/
	exit;
}

include ('../run/header.php');
?>

<section>
	<form method="post" action="">
		Start Date:
		<input name="fromdate" type="date" value="<?=$timesheetData['fromdate']; ?>">
		End Date:
		<input name="enddate" type="date" value="<?=$timesheetData['enddate']; ?>">
		Total Hours:
		<input name="totalhours" type="number" step="0.1" value="<?=$timesheetData['totalhours']; ?>">
		Attachment:
		<input name="attachment" type="file">
		<input type="submit" name="Submit">
	</form>
</section>

</body>
</html>
<?php
include ('../run/footer.php');
?>
