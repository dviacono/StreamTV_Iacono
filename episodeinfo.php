<?php
	//Create session
	session_start();
?>

<!-- 
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Display episode information 
-->

<html>
	<!-- Header used for all pages on the site -->
	<center>
		<b>
			<font size=+4>
				<a href="streamtv.php">
					streamTV
				</a>
			</font>
		</b>
		<hr WIDTH="100%"></hr>
	</center>

	<body style="font-family:'Calibri'">
		<!-- Title of the page -->
		<b class="newStyle1">The television show you searched for: </b> 

		<?php 
			//Include the connect.php file for connections to the database.
			include 'connect.php';

			//Get post data for showID and episodeID
			$showID = $_GET['showID'];
			$episodeID = $_GET['episodeID'];

			//Create the query as a string with markers for showID and episodeID
			$query = 	"SELECT S.title, E.title, airdate 
						FROM SHOWS S,EPISODE E 
						WHERE S.showID=E.showID AND E.showID=? AND E.episodeID=?";  

			//Set up the sql statement
			if($stmt = mysqli_prepare($link, $query)){
				//Bind the parameter $showID and $episodEID to the ? markers in the query string
				mysqli_stmt_bind_param($stmt, 'ss', $showID,$episodeID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $showTitle,$episodeTitle,$airDate
				mysqli_stmt_bind_result($stmt, $showTitle, $episodeTitle, $airDate);

				//Get the result and print it
				if(mysqli_stmt_fetch($stmt)){
					echo "<br>Show: <a href='showinfo.php?showID=$showID'>$showTitle</a>
					<br>Episode: <i>$episodeTitle</i>
					<br>Original Air Date: $airDate";
				}
				else {
					echo "<br>Television Show not found";
				}

				//Close the statement
				mysqli_stmt_close($stmt);
			}

			//Allow customer to view if loggedin
			if($_SESSION['cnum'] != ""){
				//Create the query to get the showID's from the customer's queue
				echo "<p><a href = 'newwatched.php?showID=$showID&episodeID=$episodeID&showTitle=$showTitle&episodeTitle=$episodeTitle'>
						Watch This Episode</a>";    
			}

			//Query for Main Cast
			echo "<p><b>Main Cast:</b></p>";

			$query = 	"SELECT CONCAT(fname, ' ',lname), M.actID, role 
						FROM MAIN_CAST M, ACTOR A
						WHERE M.actID=A.actID AND showID=? 
						ORDER BY lname";

			if($stmt = mysqli_prepare($link, $query)){
				//Bind the parameter $showID to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $actorName,$actID,$actorRole
				mysqli_stmt_bind_result($stmt, $actorName,$actID,$actorRole);

				//Get the result and print it
				while(mysqli_stmt_fetch($stmt)){
					echo "<a href = 'actorinfo.php?actorID=$actID&actorName=$actorName'>$actorName </a> 
							as <i>$actorRole</i><br>";
				}
			}  
			//Close the statement
			mysqli_stmt_close($stmt);

			//Query for Recurring Cast
			echo "<p><b>Episode Cast:";

			$query = 	"SELECT CONCAT(fname, ' ',lname), RECURRING_CAST.actID, role, COUNT(*) 
						FROM RECURRING_CAST, ACTOR 
						WHERE RECURRING_CAST.actID=ACTOR.actID AND showID=? AND episodeID=? 
						GROUP BY CONCAT(fname, ' ',lname) 
						ORDER BY lname";

			if ($stmt = mysqli_prepare($link, $query)) {
				//Bind the parameter $showID,$episodEID to the ? markers in the query string
				mysqli_stmt_bind_param($stmt, 'ss', $showID, $episodeID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $actorName,$actID,$actorRole,$numAppeared
				mysqli_stmt_bind_result($stmt, $actorName,$actID,$actorRole,$numAppeared);

				mysqli_stmt_store_result($stmt);
				$rows = mysqli_stmt_num_rows($stmt);

				echo " ($rows)</b><br>";
				if($rows > 0){
					//Display the results
					while(mysqli_stmt_fetch($stmt)){
						echo "<p><a href = 'actorinfo.php?actorID=$actID&actorName=$actorName'>$actorName</a> 
								as <i>$actorRole</i><br>";
					}
				}
				else{
					echo "No Episode Cast.";
				}
			}  

			//Close the statement
			mysqli_stmt_close($stmt);

			//Close the link to the database
			mysqli_close($link);
		?>
	</body>
</html>
