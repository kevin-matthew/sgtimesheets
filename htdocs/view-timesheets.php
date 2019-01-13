<?php
require_once 'run/auth.php';
require_once 'lib/db.php';

// download a given timesheet.
function dl(int $id, account $account)
{
	$sql = "select timesheets.filelocation 
, users.username
, timesheets.fromdate
, timesheets.enddate
from timesheets
join users on timesheets.userid=users.userid
where timesheets.timesheetid = $id
";
	if(!$account->admin)
	{
		$sql .= " and timesheets.userid = " . $account->accountid;
	}
	$res = getDB()->query($sql);
	if(!$res)
	{
		header('HTTP/1.1 500 Query failed');
		exit;
	}
	$row = $res->fetch(2);
	if(!$row)
	{
		header('HTTP/1.1 404 Record not found');
		exit;
	}
	$filepath = $row['filelocation'];
	if(!file_exists($filepath))
	{
		header('HTTP/1.1 404 File not found');
		exit;
	}
	// get the extension from the file
	preg_match('/\.([a-zA-Z0-9]+)$/'
	, $filepath
	, $matches);
	$ext = $matches[1];

	// generate a user-friendly file name;
	$fname = $row['username']
		. ' '
		. $row['fromdate']
		. ' to '
		. $row['enddate']
		. '.' . $ext;
	
	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$fname.'"');
	header('Content-Length: ' . filesize($filepath));
	readfile($filepath);
}

if(!empty(@$_GET['dl']) && is_numeric($_GET['dl']))
{
	dl($_GET['dl'], $account);
	exit;
}

// Will only show all if $showall = true and $account is admin
function genrows(account $account, $showall)
{
	$sql = <<<EOF
select concat(users.firstname, ' ', users.lastname) as fullname
, timesheets.totalhours
, timesheets.fromdate
, timesheets.enddate
, concat('<a href="?dl=', timesheets.filelocation, ' ">Download File</a>') as link
from timesheets
join users on timesheets.userid = users.userid
EOF;
	if(!$showall || !$account->admin)
		$sql .= ' where timesheets.userid = ' . $account->accountid;
	$sql .= ' ORDER BY timesheets.filelocation';
	$error = '';
	$res = getDB()->query($sql);
	if(!$res)
	{
		return '<tr><td class="errcell" colspan="100%">Failed to execute query</td></tr>';
	}

	$ret = '';
	while($row = $res->fetch(2))
	{
		$ret .= "<tr>";
		foreach($row as $k=>$col)
			$ret .= "<td>" . $col . "</td>";
		$ret .= "</tr>";
	}
	if(!$ret) $ret = '<tr><td class="errcell" colspan="100%">No timesheets found</td></tr>';
	return $ret;
}

include 'run/header.php';
?>
<section class="content">
	<main style="width:100%">
	<h3>All Timesheets Submitted</h3>
	<table>
		<thead>
			<tr class="tableColumnLabels">
				<th>User Name</th>
				<th>Total Hours</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Download</th>
			</tr>
		</thead>
		<tbody>
<?=genrows($account, isset($_GET['all']))?>
		</tbody>
	</table>
	</main>
</section>
<?php
	include 'run/footer.php';;
