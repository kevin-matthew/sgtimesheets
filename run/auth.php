<?php
require_once 'lib/account.php';
$account = account_session();
if(!$account)
{
	header('location:/login');
	exit;
}