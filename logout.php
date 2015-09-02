<?php
	//start session	
	session_start();

	//Reset session variable cnum to empty and redirect to home page.	
	$_SESSION['cnum'] = "";
	header('Location: streamtv.php');
?>

<!--
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	This class logs the user out of their session.
-->
<html>
	<head>
		<title>streamTV</title>
	</head>
</html>