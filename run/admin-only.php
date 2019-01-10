<?php
require_once 'run/auth.php';

if(!$account->admin)
{
	ob_clean();
	header('HTTP/1.1 401 Access denined');
	exit;
}