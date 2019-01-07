<?php
require_once 'lib/account.php';
$error = '';
if(!empty(@$_POST['submit']))
	 {
	if(!account_create($_POST))
	{
		$error = '<error>' . account_error() . '</error>';
	}
	else
	{
	 $error = '<success>Success!</success>';
	 $error = '<p>The user ' . $_POST['username'] . ' has been created.</p>';
	}
}
include 'run/header.php';
	 ?>

<title>Add User</title>

<section>
	<main>
		<h3>Add User</h3>
		<form method="post" action="">
			<p class="usrin">
				<label >First Name</label>
				<input type="text" name="firstname" value placeholder="John" maxlength="25">
			</p>
			<p class="usrin">
				<label >Last Name</label>
				<input type="text" name="lastname" value placeholder="Jingleheimerschmidt">
			</p>
			<p class="usrin">
				<label >User Name</label>
				<input type="text" name="username" value placeholder="JJingle">
			</p>
			<p class="usrin">
				<label >Temp Password</label>
				<input type="password" name="password" value placeholder="">
			</p>
			<p class="usrin">
				<label >Employee ID</label>
				<input type="text" name="employeeid" value placeholder="">
			</p>
			<p class="usrin">
				<label >Email</label>
				<input type="text" name="email" value placeholder="Jsmih@example.com"   style="">
			</p>
			<p class="usrin">
				<label >Start Date</label>
				<input type="date" name="startdate" value placeholder="Start Date" maxlength="10">
			</p>
			<div style="text-align:center">
			<?=$error?>
			<input type="submit" name="submit" value="Add User" style="background:green;color:white;"></div>
		</form>
	</main>
</section>
	<?php
	include 'run/footer.php';
