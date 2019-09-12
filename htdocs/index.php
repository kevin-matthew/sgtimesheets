<?php
require_once 'run/auth.php';
require_once 'lib/db.php';
	 $db = getDB();
	 $isadmin = $account->admin;
include ('../run/header.php');
	 ?>
<style>
	nav.panel
	{
	
	}
	nav.panel a
	{
	display:block;
	padding:10px;
	margin:4px;
	background-color:#37749D;
	color:#fff;
	}
	nav.panel.admin a
	{
	background-color:#4f7fdf;
	}
	
</style>
<section class="content">
	<main>
		<h2>Welcome to SG Timesheet Manager</h2>
		<p>
			Select one of the follow menus to begin exchanging
			timesheets.
		</p>
		<?php if($isadmin): ?>
		<p>
			<strong>You are logged into an administrative account</strong>,
			additional menus will be displayed.</p>
		</p>
		<?php endif; ?>
	</main>

<aside>
	<h3>User Panel</h3>
			<nav class="panel">
				<a href="/add-timesheet">Add a new timesheet</a>
				<a href="/account">Manage your Account</a>
				<a href="/view-timesheets">See your timesheets</a>
				<a style="background-color:#990000" href="/logout">Log out</a>
			</nav>
		</aside>
<?php if($isadmin): ?>
<aside>
	<h3>Administrative Panel</h3>

	<nav class="panel admin">
		<a href="/view-timesheets?all">See all timesheets</a>
		<a href="/add-user">Add User</a>
		<a href="/manage-user">Manage Existing Users</a>
	</nav>
</aside>
<?php endif; ?>

	</section>
</body>
</html>
<?php
include ('../run/footer.php');
?>
