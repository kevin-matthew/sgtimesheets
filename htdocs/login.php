<?php
include 'run/header.php';
require_once 'lib/account.php';

$account = account_session();
if($account)
{
	print '<section><main><p>Already logged in, <a href="/logout.php">logout?</a></p></main></section>';
	include 'run/footer.php';
	exit;
}

?>
<section>
	<main>
<h3>Login</h3>
		<form method="post" action="">
			
			<input type="text" name="user" value placeholder="Username" maxlength="255" size="25" style="margin-bottom: 10px;">
			<br>
			
            <input type="password" name="pass" value placeholder="Password" maxlength="255" size="25" style="margin-bottom: 10px;">
            <br>
            
            <input type="submit" name="submit" value="Login" style="position: relative; left: 50%; margin-left: -27px; text-align: center;">
        </form>
	</main>
</section>
<?php
include 'run/footer.php';
?>
