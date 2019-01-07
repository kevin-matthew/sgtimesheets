<?php
require_once 'run/auth.php';
require_once 'lib/db.php';
$db = getDB();
function genTableBody($id) {
	global $db;
	$sql = "SELECT * FROM timesheets WHERE userid=:userid;";
	$query = $db->prepare($sql);
	$query->execute(array(':userid' => $id));
	$result = $query->fetchAll(2);

	foreach($result as $row) {
		print '<tr>';
		print '<td>' . $row['fromdate'] . '</td>';
		print '<td>' . $row['enddate'] . '</td>';
		print '<td>' . $row['totalhours'] . '</td>';
		print '<td>' . $row['filelocation'] . '</td>';
		print '<td><a href="/timeSheet?id='.$row['timesheetid'].'">edit</a>';
		print '</tr>';
	}
}

include ('../run/header.php');
?>
	<h3 style="text-align: center;">My Timesheets</h3>
    <section class="content">
		<a class="button" href="/timesheet">Add Timesheet</a>
		<table>
			<thead>
				<tr class="tableColumnLabels">
					<th>Start Date</th>
					<th>End Date</th>
					<th>Total Hours</th>
					<th>Attachment</th>
				</tr>
			</thead>
			<tbody>
				<?=genTableBody($account->accountid); ?>
			</tbody>
		</table>
	</section>
</body>
</html>
<?php
include ('../run/footer.php');
?>
