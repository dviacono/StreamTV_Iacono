<?php
	session_start();
	require_once "connect.php";
?>

<!--reoffice_home.php-->
<html>
	<head>
	<title> REOffice </title>
	</head>

	<body style="font-family:'Calibri'">
		<?php
			if (!isset($_SESSION['cnum'])) {
				$_SESSION['cnum'] = md5(uniqid(rand(), TRUE));
			}	
		?>

		<!-- Header used for the whole site -->
		<center>
			<b>
				<font size=+4>
					<a href="streamtv.php">
						REOffice
					</a>
				</font>
			</b>
			<hr WIDTH="100%">
		</center>

		<!-- Search for a TV show -->
		<form action = "reoffice_search.php" method = "POST">
			Search for a house: <input name="house_search" type "text">
			<input type="submit" name="submit" value="Submit!">
		</form>
		<p>
	</body>
</html>