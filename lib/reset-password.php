<?php
require_once 'lib/account.php';
require_once 'lib/paydata.php';
require_once 'lib/communication.php';

/*
 * Resets the accounts password. It is not possible without providing
 * the current password. If the old password was lost, you must use
 * reset_password_lossy.
 *
 * Updates the row in the database. It does not update the session.
 * This function decrypts all paydata and re-encrypts it with the new
 * password.
 *
 * On error, null is returned and errorstr is set to user-readable error.
 */
function reset_password(account $a
, string $old_pass
, string $new_pass
, &$errorstr)
{
	// Make sure the old password is right
	if(!account_ispassword($a, $old_pass))
	{
		$errorstr = "Current password is incorrect.";
		return null;
	}
	
	// Generate a new password hash and a new key.
	list($newsalt, $passhash) = account_password($a->email, $new_pass, $errorstr);
	if(!$newsalt || !$passhash)
	{
		return null;
	}
	$oldkey = $a->_enc_data;
	$newkey = account_key($new_pass, $newsalt);
	$newact = clone $a;
	$newact->_enc_data = $newkey; // Copy the existing account and replace the key with the new one.

	// Get all the paydata objects for the account
	$pds    = paydata_selecta($a->accountid);
	if($pds == -1)
	{
		log_crit("Could not load paydata from account " + $a->accountid);
		$errorstr = "Could not load your encrypted data.";
		return null;
	}

	// Go through each paydata object and recrypt it
	$len = count($pds);
	for($i = 0; $i < $len; $i++)
	{
		if(!_recrypt_paydata($pds[$i], $a, $newact))
		{
			log_crit("Could not recrypt data for " + $a->accountid);
			$errorstr = "There was an error recrypting your data.";
			return null;
		}
	}

	// Begin database operations...
	paydb()->beginTransaction();

	// Update all the paydata
	foreach($pds as $pd)
	{
		if(!paydata_dbforge($pd, $newact))
		{
			log_crit("Could not update recrypt data for " + $a->accountid);
			$errorstr = "There was an error updating your data.";
			paydb()->rollBack();
			return null;
		}
	}

	//Update the account itself.
	$uq = paydb()->prepare("update Accounts set password=?, encryption_salt=?, reset_password_token=NULL, reset_password_expiry=NULL where accountid=?");
	if(!$uq->execute(array($passhash, $newsalt, $a->accountid)))
	{
		log_crit($uq->errorInfo()[2]);
		$errorstr = "Unkown error, our administraters have been notified.";
		paydb()->rollBack();
		return null;
	}

	// We made it
	paydb()->commit();
	return $newact;
}

/*
 * Resets the password for the account and deletes all encrypted data
 * such as paydata.
 * Used for reseting a password when the password is forgotten.
 */
function reset_password_lossy(account $a, string $new_pass, &$errorstr)
{
	// Generate a new password hash and a new key.
	list($newsalt, $passhash) = account_password($a->email, $new_pass, $errorstr);
	if(!$newsalt || !$passhash)
	{
		return null;
	}
	$newkey = account_key($new_pass, $newsalt);
	$newact = clone $a;
	$newact->_enc_data = $newkey; // Copy the existing account and replace the key with the new one.

    // Begin database operations...
	paydb()->beginTransaction();
	
	$delete_pay = paydb()->exec("delete from PayData where accountid = ".$a->accountid);
	if($delete_pay === false) {
		log_crit("Error with the delete query for PayData in reset_password_lossy (reset-password.php).");
		$errorstr = "Unknown error, our administrators have been notified.";
		paydb()->rollBack();
		return null;
	}

	//Update the account itself.
	$uq = paydb()->prepare("update Accounts set password=?, encryption_salt=?, reset_password_token=NULL, reset_password_expiry=NULL where accountid=?");
	if(!$uq->execute(array($passhash, $newsalt, $a->accountid)))
	{
		log_crit($uq->errorInfo()[2]);
		$errorstr = "Unknown error, our administrators have been notified.";
		paydb()->rollBack();
		return null;
	}

	// We made it
	paydb()->commit();
	return $newact;
}

// Unencrypts the paydata using old account, the encrypts the data with
// new account. Both accounts must be logged in.
function _recrypt_paydata(paydata &$pd, account $old, account $new)
{
	$res = paydata_decrypt($pd, $old);
	if($res !== 0)
	{
		log_warn("Could not decrypt account({$old->accountid})'s paydata({$pd->paydataid}): $res");
		return false;
	}

	$res = paydata_encrypt($pd, $new);
	if($res !== 0)
	{
		log_warn("Could not encrypt account({$old->accountid})'s paydata({$pd->paydataid}): $res");
		return false;
	}
	return true;
}

/**
 * This first checks if the email is a valid user.
 * If the email does exist, then a reset token is inserted in that 
 * user's row.
 * Once the token is inserted, an email with a link to their
 * unique password reset page.
 * Resturns true on success and false on error.
 * If an error occurs, an error message is inserted in the $err_msg.
 */
function insert_reset_token(string $email, string &$err_msg) {
	if(!($id = account_get_id($email, $err_msg))) {
		return false;
	}

	// make sure that the token doesn't already exist:
	$selectToken = paydb()->prepare("select * from Accounts where reset_password_token = ?");
	do {
		$token = _account_generate_hash(64);
		$selectToken->execute(array($token));
	} while($selectToken->fetch()[0]);
		
	$update = paydb()->prepare("update Accounts set reset_password_token = ?, reset_password_expiry = ? where email = ?");
	$expiry = date('Y-m-d H:i:s', strtotime('+1 day', time()));
	
	if(!$update->execute(array($token, $expiry, $email))) {
		log_crit($update->errorInfo()[2]);
		$err_msg = "Unknown error, our administrators have been notified.";
		return false;
	}

	// communication_send_email(shit n stuff);
	return true;
}
