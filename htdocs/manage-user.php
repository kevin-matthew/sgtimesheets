<?php
require_once 'run/admin-only.php';
include ('../run/header.php');

?>
<title>User Management</title>

<section>
	<main>
		<h3>User Management</h3>
	</main>
	<ul>
		<li>Contains a table of all users. Should have columns for
			user name, owner's name, email, employee ID, start date,
			and is admin.</li>
		<li>Each row of the table links to a /account/[account-number].</li>
		<li>There should be ways to search/sort.</li>
		<ul>
			<li>Sort by last name, first name, user name, email, employee ID,
				and start date</li>
			<li>Search by ability for last name, first name, user name, email, 
				and employee ID</li>
		</ul>
	</ul>
</section>

</body>
</html>
<?php
include ('../run/footer.php');
