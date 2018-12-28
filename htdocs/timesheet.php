<?php
$userID = 1; // Change once users are setup

if(!empty($_POST['Submit'])) {
	include ('../lib/timesheets.php');
	$err_msg = "";
	if(!empty($_GET['id']) && updateTimesheet($_POST, 'attachment', $_GET['id'], $err_msg)) {
		/*
		Send to the home page with a success message.
		*/
		header('Location: /');
		exit;
	}
	elseif(insertTimesheet($_POST, 'attachment', $userID, $err_msg)) {
		/*
		Send to the home page with a success message.
		*/
		header('Location: /');
		exit;
	}
}

// Check if the user is wanting to edit a timesheet:
if(!empty($_GET['id'])) {
	require_once('../lib/db.php');
	$db = getDB();
	
	$timesheetID = (int)$_GET['id'];

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
}

include ('../run/header.php');
?>

<?php if(!empty($err_msg)): ?>
<section>
	<error><?=$err_msg; ?></error>
</section>
<?php endif; ?>

<section>
	<form method="post" action="" enctype="multipart/form-data">
		Start Date:
		<input name="fromdate" type="date" value="<?=$timesheetData['fromdate']; ?>">
		End Date:
		<input name="enddate" type="date" value="<?=$timesheetData['enddate']; ?>">
		Total Hours:
		<input name="totalhours" type="number" step="0.1" value="<?=$timesheetData['totalhours']; ?>">
		Attachment:
		<input type="file" name="attachment">
		<input type="submit" name="Submit">
	</form>
</section>

</body>
</html>
<?php
include ('../run/footer.php');
?>