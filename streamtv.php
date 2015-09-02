<?php
	session_start();
	require_once "connect.php";
?>

<!--
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php
-->

<html>
	<head>
	<title> streamTV </title>
	</head>

	<body style="font-family:'Calibri'">
		<?php
			if (!isset($_SESSION['cnum'])) {
				$_SESSION['cnum'] = "";
			}	
		?>

		<!-- Header used for the whole site -->
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

		<!-- Search for a TV show -->
		<form action = "all_search.php" method = "post">
			Search for a TV show or actor by title: <input name="newSearch" type "text">
			<input type="submit" name="submit" value="Submit!">
		</form>

		<?php
			if($_SESSION['cnum'] == ""){
				echo "<a href = 'login.php'>Login</a> as an existing customer.
					<p><a href = 'registration.php'>Register</a> as a new customer.";
			}

			else{
				echo "View your <a href = 'cust_queue.php'>queued shows</a>.
					<p><a href = 'logout.php'>Logout</a>.";
			}
		?>
		<p>
	</body>
</html>