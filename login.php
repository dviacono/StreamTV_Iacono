<?php
	// Uses session to send token for CSRF verification
	// Start the session
	session_start();

	// Compute a token to be passed with this post form to guard against CSRF attack
	if (!isset($_SESSION['token'])) {
		$_SESSION['token'] = md5(uniqid(rand(), TRUE));
	}

	$token = $_SESSION['token'];
?>

<!-- 
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Login form to allow customer to log into the system. 
	Fill in form with username and password.
	Data sent to check_login.php using POST method.
-->

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Login Form</title>
	</head>
 
<body style="font-family:'Calibri'">
	<!-- Header used for all pages on the site -->
	<center>
		<b>
			<font size=+4>
				<a href="streamtv.php">
					streamTV
				</a>
			</font>
		</b>
		<hr WIDTH="100%">
	</center>

	<!-- Create login form -->
		<form id="form1" name="form1" method="post" action="check_login.php">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<table width="510" border="0" align="center">
				<tr><td colspan="2">Login Form</td></tr>
				<tr><td>Username:</td><td><input type="text" name="uname" id="uname" /></td></tr>
				<tr><td>Password:</td><td><input type="password" name="pword" id="pword" /></td></tr>
				<tr><td>&nbsp;</td><td><input type="submit" name="button" id="button" value="Submit" /></td></tr>
			</table>
		</form>
	</body>
</html>