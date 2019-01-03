<?php
require_once 'lib/account.php';
include 'run/auth.php';
// account to edit. This will either be the person logged in or
// it could be an admin edditing someonelse account
$acc2edit = $account;

if($account->admin == 1 && is_numeric(@$_GET['editid']))
	$acc2edit = account_selectid($_GET['editid']);

if(!$acc2edit)
{
	header('HTTP/1.1 404 Account not found');
	exit;
}
var_dump($_POST);
function update_account($account, $post, $edit_proc)
{
	global $error;
	if(!account_validpost($post, $error, $edit_proc))
		return false;
	// username
	$sql = 'UPDATE `sgdb`.`users` SET ';
	//password (if applicicable)
	if(!empty($post['password']))
		$sql .= '`password` = ?,';
	//firstname/lastname
	$sql .= '`firstname` = ?,`lastname` = ? ';
	if($edit_proc)
	{
		// uname email, admin, employeeid, startdate, (if admin is
		// editing)
		$sql .= ', `username` = ?, `email` = ?,
			`admin` = ?,
			`employeeid` = ?,
			`startdate` = ? ';
	}
	$sql .= 'WHERE `userid` = ?'; // userid

	/* Now we can add all the info to an array */
	$ar = array();
	//username
	// password (if applicaable)
	if(!empty($post['password']))
	{
		$pass = account_password($post['password'], $error);
		if(!$pass)
			return false;
		array_push($ar, $pass);
	}
	array_push($ar, $post['firstname' ]); //fnaem
	array_push($ar, $post['lastname' ]);  //lname
	if($edit_proc)
	{
		// email admin employeeid startdate
		array_push($ar, $post['username' ]);
		array_push($ar, $post['email' ]);
		array_push($ar, isset($post['admin']) && $post['admin'] ? 1 : 0);
		array_push($ar, $post['employeeid' ]);
		array_push($ar, $post['startdate' ]);
	}
	array_push($ar, $account->accountid); //userid
	$stmt = getDB()->prepare($sql);
	if(!$stmt)
	{
		$error = "Database error, could not update.";
		return false;
	}

	$res = $stmt->execute($ar);
	if(!$res)
	{
		$error = "Database input error, could not update.";
		return false;
	}

	if(!$stmt->rowCount())
	{
		$error = "Update successful, but 0 records were modified";
		return false;
	}
	return true;
}

$error = '';
if(!empty(@$_POST['submit']))
{
	if($_POST['password'] !== $_POST['rpassword'])
	{
		$error = '<error>Passwords did not match.</error>';
	}
	else
	{
		if(!update_account($acc2edit, $_POST, $account->admin == 1))
			$error = '<error>' . $error . '</error>';
		else
		{
			$error = '<success>Succesfully updated.</success>';

			// Sense we updated the account we'll have to re-fetch it.
			$acc2edit = account_selectid($acc2edit->accountid);
		}
	}
	
}

include ('../run/header.php');
?>

<section>

	<main>
		<form method="post" action="">
			<p class="usrin">
				<label >First Name</label>
				<input type="text" name="firstname" value="<?=$acc2edit->_data['firstname']?>">
			</p>
			<p class="usrin">
				<label >Last Name</label>
	<input type="text" name="lastname" value="<?=$acc2edit->_data['lastname']?>">
			</p>
			<p class="usrin">
				<label >User Name</label>
				<input type="text" disabled value='<?=$acc2edit->username?>'>
			</p>
			<p class="usrin">
				<label >Change Password</label>
				<input type="password" name="password" value placeholder="">
			</p>
			<p class="usrin">
				<label >Change Password (Repeat)</label>
				<input type="password" name="rpassword">
			</p>
			<hr>
			<?php if($account->admin==1): ?>
			<p>The fields below can only be edited by administrators.</p>
	<p class="usrin">
	<label>Username</label>
	<input type="text" name="username" value='<?=$acc2edit->_data['username']?>'>
			</p>
			<p class="usrin">
				<label >Employee ID</label>
				<input type="text" name="employeeid" value='<?=$acc2edit->_data['employeeid']?>'>
			</p>
			<p class="usrin">
				<label >Email</label>
	<input type="text" name="email" value="<?=$acc2edit->_data['email']?>" placeholder="Jsmih@example.com">
			</p>
			
			<p class="usrin">
				<label >Start Date</label>
	<input type="date" name="startdate" value="<?=$acc2edit->_data['startdate']?>" placeholder="Start Date">
			</p>
<p class="usrin">
<label>Admin</label>
	<input type="checkbox" name="admin" <?php if($acc2edit->_data['admin']) print 'checked'; ?>>
</p>
			<?php endif; ?>

			<div style="text-align:center">
				<?=$error?>
				<input type="submit" name="submit" value="Update User" style="background:green;color:white;">
			</div>
		</form>
	</main>
</section>
<?php
include ('../run/footer.php');
