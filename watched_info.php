<?php
	//Start a session
	session_start();
?>

<!-- 
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Display Episodes watched by the customer given a specific show
-->

<html>
	<head>
		<title> streamTV </title>
	</head>
	<body style="font-family:'Calibri'">
		<?php
			if(isset($_SESSION['cnum'])){
				$cnum = $_SESSION['cnum'];
			}
			else{
				$cnum = "";
			}
		?>

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

		<?php
			//Get Parameters from URL
			$showID = $_GET['showID'];
			$showTitle = $_GET['title'];
			$name  = $_GET['name'];

			//Display the number of episodes watched
			echo "<p>Episodes of <b><i>$showTitle</i></b> watched by $name:<br>";

			//Include the connect.php file to connect to the database.
			include 'connect.php';

			//Query for all the watched episodes.
			$query = 	"SELECT E.episodeID, title, datewatched 
						FROM WATCHED W, EPISODE E
						WHERE W.episodeID = E.episodeID AND W.custID = ? 
							AND W.showID = ? AND E.showID = ?";

			if(!$stmt = mysqli_prepare($link, $query)){
				die("Error in Query");
			}

			//bind the ? markers with variables $cnum, $showID(x2)
			mysqli_stmt_bind_param($stmt, 'sss', $cnum, $showID, $showID);

			//execute the query
			mysqli_stmt_execute($stmt);

			//bind the result to variables $episodeID, $episode, $dateWatched
			mysqli_stmt_bind_result($stmt, $episodeID, $episode, $dateWatched);

			//Store the result so we can get number of rows
			mysqli_stmt_store_result($stmt);

			//Get the number of rows in the result
			$rows = mysqli_stmt_num_rows($stmt);

			if ($rows > 0) {
				//Create a table to display the results
				echo "<p><table border=1><tr><td>TITLE</td><td>DATE WATCHED</td></tr>";

				//Iterate through result set
				while (mysqli_stmt_fetch($stmt)) {
					echo "<td><a href='episodeinfo.php?showID=$showID&episodeID=$episodeID'>$episode</a></td>";
					echo "<td>$dateWatched</td></tr>";
				}
				echo "</table>";
			}
			else {
				//No episodes found
				echo "<p> No episodes watched. <br>";
			}

			mysqli_stmt_close($stmt);
			mysqli_close($link);
		?>
	</body>
</html>	
