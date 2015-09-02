<?php
	//Start session
	session_start();
?>

<!--
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	This displays the watched shows
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
			//Confirm session IDs match
			if(isset($_SESSION['cnum'])){
			$cID=$_SESSION['cnum'];
			}
			else{
				$cID="";
			}

			//if not logged in, redirect to main page
			if($cID==""){
				header("Location: index.php");
			}

			else{
				//connect to database to add as watched
				include 'connect.php';

				//get URL Parameters
				$showID = $_GET['showID'];
				$episodeID = $_GET['episodeID'];
				$showTitle = $_GET['showTitle'];
				$episodeTitle = $_GET['episodeTitle'];
				$date= date("Y-m-d");
				echo "Now watching <i><b>$showTitle</b></i>";
				echo "<br>Episode: <i><b>$episodeTitle</b></i></br>";

				//SQL statement for watched shows
				$query = 	"INSERT INTO WATCHED (custID, showID, episodeID, datewatched)
							VALUES (?,?,?,?)";

				//Prepare the statement		  
				if(!$stmt = mysqli_prepare($link,$query)){
					die("Error in Query");
				}

				//Bind $cID,$showID,$episodeID,$date to ? markers
				mysqli_stmt_bind_param($stmt,"ssss", $cID, $showID, $episodeID, $date);

				//Execute the Query Statement
				mysqli_stmt_execute($stmt);

				//close the Query
				mysqli_stmt_close($stmt);
				mysqli_close($link);
			}
		?>

		<iframe width="1280" height="720" src="https://www.youtube.com/watch?v=dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe>
	</body>
</html>