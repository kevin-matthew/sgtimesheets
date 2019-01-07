<?php
require_once 'lib/account.php';
$a = account_session();
$loginmsg = $a != false ? '<div style="float: left;">Logged in as ' . $a->dname . '</div>'
	. ' <a href="/logout.php" style="float: right;">Logout</a>' : '<a href="/login.php" style="float: right;">Login</a>';
?>
<html lang="en-US">
<head>
	<link rel='icon' href='/img/favicon.ico' type='image/x-icon'/>
	<meta name="MobileOtimized" content="width">
	<meta name="HandheldFriendly" content="true">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" type="text/css" href="/css/main.css">
</head>

<body>
	<header style="display:block;text-align:center;">
	 <a href="/" style="color:#444;">
	 	<div style="text-align:center"><h1>Timesheet Manager</h1>
	 	<h2>SmartGeo Tech</h2>
	 	</div>
	 </a>
	<h3><?php print $loginmsg; ?></h3>
	<br>
  </header>
