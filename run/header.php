<?php
require_once 'lib/account.php';
$a = account_session();
$loginmsg = $a != false ? '<nav> <a href="/" style="float: left;"> Home </a>'
	. '<a style="color:#444;"> Logged in as ' . $a->dname . '</a>'
	. '<a href="/logout.php" style="float: right;">Logout</a> </nav>'
	: '<a href="/login.php" style="float: right;">Login</a>';
?>
<html lang="en-US">
<head>
        <!-- (summarized disclaimer): This website is licensed under the agplv3. Meaning users of this websites must have access to this website's complete source code including javascript, php, css, ect. Any modifications you make you must have your newly updated source code distributed to the user base. Complete license here: https://www.gnu.org/licenses/agpl-3.0.en.html -->
        <meta name="license" content="https://www.gnu.org/licenses/agpl-3.0.en.html">
        <meta name="source" content="https://github.com/ellemlabs/sgtimesheets">
	<link rel='icon' href='/img/favicon.ico' type='image/x-icon'/>
	<meta name="MobileOptimized" content="width">
	<meta name="HandheldFriendly" content="true">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="/css/main.css">
</head>

<body>
	<header style="display:block;text-align:center;">
		<a style="color:#444;">
	 		<div style="text-align:center">
				<h1>Timesheet Manager</h1>
	 			<h2>(My Company Name)</h2>
	 		</div>
		</a>
		<h3 style="min-height:1px;"><?php print $loginmsg; ?></h3><br>
  </header>
