<?php
require_once 'lib/db.php';
require_once 'lib/communication.php';


session_start();
$account_error = '';

class account
{
	public $accountid;
	public $username;
	public $email;
	public $_data;
	public $admin = 0;
	public $dname; // display name.
	public $resetpassword;
}

function account_validpost(array $postdata, string &$err_str = '', bool $edit_proc = true) : bool
{
	// username
	if(!preg_match("/^[a-zA-Z0-9_]*$/",@$postdata['username']))
	{
		$err_str = "Username must be alphanumaric";
		return false;
	}

	// first name
	if(!preg_match('/^[a-zA-Z]+$/', @$postdata['firstname']))
	{
		$err_str = "Invalid firstname";
		return false;
	}

	// last name
	if(!preg_match('/^[a-zA-Z]+$/', @$postdata['lastname']))
	{
		$err_str = "Invalid lastname";
		return false;
	}

	if($edit_proc)
	{
		// email
		if(!filter_var(@$postdata['email'], FILTER_VALIDATE_EMAIL))
		{
			$err_str="Invalid email format";
			return false;
		}

		// employeeid
		if(!preg_match('/^[a-zA-Z0-9]+$/', @$postdata['employeeid']))
		{
			$err_str = "Invalid employeeid must be alpha-numaric";
			return false;
		}

		// startdate
		$matches = array();
		if(!preg_match('/^(\d{4})\-(\d{2})\-(\d{2})$/', @$postdata['startdate'], $matches)
		|| !checkdate($matches[2], $matches[3], $matches[1]))
		{
			$err_str = "Invalid date";
			return false;
		}
	}
	return true;
}

/**
 * Attempts to create a new account, and returns success rate.
 * If false is returned, be sure to show the user account_error()
 * which will explain what when wrong. In case of a system (uknown 
 * error), see the log
 */
function account_create(array $postdata) : bool
{
	global $account_error;

	//validate the post data
	if(!account_validpost($postdata, $account_error))
		return false;
	
	// prepare the needed statements
	$cq = getDB()->prepare("insert into users (username,password,firstname,lastname,email,employeeid,startdate)
		values (?, ?,?,?,?,?,?)");
	$rq = getDB()->prepare("select count(*) from users where email=? OR username=? OR employeeid=?");


	// match some variables (this paragraph is used for code
	// portability)
	$email    = $postdata['email'];
	$username = $postdata['username'];
	$fname    = $postdata['firstname'];
	$lname    = $postdata['lastname'];
	$rawpass  = $postdata['password'];
	
	if(!$rq->execute(array($email,$username,$postdata['employeeid'])))
	{
		log_crit($rq->errorInfo()[2]);
		$account_error = "Unkown error";
		return false;
	}
	if($rq->fetch()[0] !== "0")
	{
		$account_error = "An account with that email, username, or employeeid has already been made.";
		return false;
	}

	// Password
	$password = account_password($rawpass, $account_error);
	if(!$password)
		return false;



// Insert the user
	$args = array();
	array_push($args, $username);
	array_push($args, $password);
	array_push($args, $fname);
	array_push($args, $lname);
	array_push($args, $email);
	array_push($args, $postdata['employeeid']);
	array_push($args, $postdata['startdate']);
	if(!$cq->execute($args))
	{
		$account_error = "Information couldn't be stored: database rejection";
		return false;
	}
	return true;
}

/*
 * Generates a passowrd hash and an ecryption salt with the given
 * email and password. returns null when the password does not match
 * the requirements, and $errstring is set to a user-readable error
 */
function account_password(string $pass, &$password_error)
{
	if(!preg_match('/.{5,}/', $pass))
	{
		$password_error = "Password must be at least 5 characters long";
		return null;
	}
	$password = password_hash($pass, PASSWORD_DEFAULT);
	return $password;
}

function account_ispassword(account $a, string $pass)
{
	return password_verify($pass, $a->_data['password']);
}


/**
 * Attempts to log into the account using the email and password (plaintext)
 * provided. If successful, the account object is saved into the session,
 * this object can be retrieved from `account_session()`.
 * On failure, null is returned. On error, null is returned and the error
 * is logged (See $account_error)
 */
function account_login(string $email, string $password)
{
	// Get the account
	global $account_error;
	if(!strlen($email))
	{
		$account_error = "No email provided";
		return null;
	}
	$stmt = getDB()->prepare("select * from users where email=?");
	if(!$stmt->execute(array($email)))
	{
		$account_error = "Error when finding email in database.";
		return null;
	}

	// See if any rows were returned
	$data = $stmt->fetch(2);
	if(!$data)
	{
		$account_error = "Email not found";
		return null;
	}

	// Check to see if password hash lines up.
	if(!password_verify($password, $data['password']))
	{
		$account_error = "Incorrect password";
		return null;
	}

	// User is OK. Make a new account object and return.
	$a = _account_forge($data, $password);
	$_SESSION['account_data'] = $a;
	return $a;
}

function _account_forge($data, string $password="")
{
	$a = new account();
	$a->accountid     = $data['userid'];
	$a->username      = $data['username'];
	$a->_data         = $data;
	$a->email         = $data['email'];
	$a->admin         = (int)$data['admin'];
	$a->resetpassword = $data['resetpassword'];
	$a->dname         = $data['firstname'] . ' ' . $data['lastname'];
	return $a;
}

/**
 * Selects an account given a reset_password_token.
 * If reset_password_token isn't found, null is returned.
 * An account object is returned on success.
 */
function account_selectt(string $token)
{
	$select = paydb()->prepare("select * from users where resetpassword = ?");
	if(!$select->execute(array($token)))
	{
		log_crit($select->errorInfo()[2]);
		return null;
	}

	$account_data = $select->fetch();
	if(!$account_data) {
		return null;
	}

	return _account_forge($account_data);
}

/**
 * Selects the account with a given id. If the query failed or the
 * account was not found, null is returned.
 */
function account_selectid(int $id)
{
	$res = getDB()->query("select * from users where userid = $id");
	if(!$res) return null;
	$row = null;
	if(!($row = $res->fetch(2))) return null;
	return _account_forge($row);
}

/**
 * Returns the last user-readable error that occoured.
 * For example "password was wrong" or "already logged in"
 * or "unknown error". null = no error
 */
function account_error()
{global $account_error; return $account_error;}

/**
 * Logs out of the account that has been stored into the session.
 * If there is no account already logged in, false is returned.
 */
function account_logout()
{
	$empt = empty($_SESSION['account_data']);
	unset($_SESSION['account_data']);
	return !$empt;
}

/**
 * Retrieves the account from the session. If there is no account
 * logged in, null is returned.
 */
function account_session()
{
	if(empty($_SESSION['account_data'])) return null;
	return $_SESSION['account_data'];
}