<?php
include ('../lib/db.php');
$db = getDB();
function genTableBody($id) {
	global $db;
	$sql = "SELECT * FROM timesheets WHERE userid=:userid;";
	$query = $db->prepare($sql);
	$query->execute(array(':userid' => $id));
	$result = $query->fetchAll();

	foreach($result as $row) {
		print '<tr>';
		print '<td>' . $row['fromdate'] . '</td>';
		print '<td>' . $row['enddate'] . '</td>';
		print '<td>' . $row['totalhours'] . '</td>';
		print '<td>' . $row['filelocation'] . '</td>';
		print '<td><a href="/editTimeSheet?id='.$row['timesheetid'].'">edit</a>';
		print '</tr>';
	}
}

include ('../run/header.php');
?>
    <section class="content">
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
				<?=genTableBody(1); /* '1' is for temporary testing, replace with actual user's ID */ ?>
			</tbody>
		</table>
	</section>
</body>
</html>
<?php
include ('../run/footer.php');
?>
