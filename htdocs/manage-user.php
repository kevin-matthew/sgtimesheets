<?php
require_once 'run/admin-only.php';
include ('../run/header.php');

function view_users()
{
	$sql = 'select * from users';
	$res = getDB()->query($sql);
	if(!$res)
		return '<tr><td colspan="100%">Error with database</td></tr>';
	$ret = '';
	while($row = $res->fetch(2))
	{
		$ret .= sprintf('<tr><td>%s %s</td><td>%s</td><td>%s</td></tr>'
		, $row['firstname']
		, $row['lastname']
		, $row['email']
		, '<a href="/account?editid=' . $row['userid'] . '">Edit</a>');
	}
	if(!$ret)
		$ret = '<tr><td colspan="100%">No data found</td></tr>';
	return $ret;
}
?>
<title>User Management</title>

<section>
	<main>
		<h3>User Management</h3>
		<table>
			<thead><tr><th>Name</th><th>Email</th><th>Link</th></tr></thead>
			<tbody>
				<?=view_users()?>
			</tbody>
		</table>
	</main>
</section>

</body>
</html>
<?php
include ('../run/footer.php');
