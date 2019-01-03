<?php
require_once 'lib/account.php';
$a = account_session();
$loginmsg = $a != false ? "Logged in as " . $a->dname
	. '. <a href="/logout.php">Logout</a>' : '';
?>
<html lang="en-US">
<head>
	<meta name="MobileOtimized" content="width">
	<meta name="HandheldFriendly" content="true">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" type="text/css" href="/css/main.css">
</head>

<body>
  <header>
	 <div style="text-align:center"><h1>Timesheet Manager</h1>
	 <h2>SmartGeo Tech</h2></div>
	<h3><?php print $loginmsg; ?></h3>
  </header>
