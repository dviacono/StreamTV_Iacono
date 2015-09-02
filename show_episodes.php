<?php
	session_start();
?>
	
<!-- 
  Author: David Iacono
  CSC 436 Database Management Systems
  Spring 2015 DiPippo
  Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

  Display the Episodes of a Specific Show 
-->

<html>
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

		<?php
			if(isset($_SESSION['cnum'])){
				$custID = $_SESSION['cnum'];
			}
			else{
				$custID = "";
			}

			//Get URL Params
			$sID = $_GET['showID'];
			$sTitle = $_GET['showTitle'];

			//connect to database
			include 'connect.php';

			//Query for the Season, episodeID, title, and Airdate of all episodes from a given ShowID
			$query = 	"SELECT SUBSTRING(episodeID,1,1), episodeID, title, airdate 
						FROM EPISODE 
						WHERE showID = ?";

			//prepare the Statement
			if(!$stmt = mysqli_prepare($link,$query)){
				die("Error in Query");
			}

			//bind the ? marker with the variable //sID
			mysqli_stmt_bind_param($stmt,"s", $sID);

			//Execute the Query
			mysqli_stmt_execute($stmt);

			//bind the Result into the variables $season,$episodeID,$eTitle,$airdate
			mysqli_stmt_bind_result($stmt,$season,$episodeID,$eTitle,$airdate);

			// Create a table to display the results
			echo "<p>Episodes of <i>$sTitle</i>  <br>";
			echo "<table border=1><tr><td>SEASON</td><td>TITLE</td><td>AIR DATE</td></tr>";

			//Iterate through result set
			while (mysqli_stmt_fetch($stmt)) {
				echo "<td><center>$season</center></td>";
				echo "<td><a href= 'episodeinfo.php?showID=$sID&episodeID=$episodeID'>$eTitle</a></td>";
				echo "<td>$airdate</td></tr>";
			}
			echo "</table>";

			#close the Query
			mysqli_stmt_close($stmt);
			mysqli_close($link);
		?>
	</body>
</html>
