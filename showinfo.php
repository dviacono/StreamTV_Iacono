<?php
	//Create session
	session_start();
?>
	
<!--
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Display details about a specific show 
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

		<b>The television show you searched for: </b> 

		<?php 
			//Include the connect.php file for connections to the database.
			include 'connect.php';

			//Use the _GET method for getting variables passed through from the 
			//previous page.
			$showID = $_GET['showID'];

			//Create the query as a string with marker for showID
			$query = 	"SELECT showID, title, premiere_year, network, creator, category 
						FROM SHOWS 
						WHERE showID=?";  

			if($stmt = mysqli_prepare($link, $query)){
				//Bind the parameter $showID to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $showID, $title, $pYear, $network, $creator, $category
				mysqli_stmt_bind_result($stmt, $showID, $title, $pYear, $network, $creator, $category);

				//Get the result and print it
				//var_dump(mysqli_stmt_fetch($stmt));
				if(mysqli_stmt_fetch($stmt)){
					echo "<br>Title: $title
					<br>Premiere Year: $pYear
					<br>Network: $network
					<br>Creator: $creator
					<br>Category: $category<br>
					<br>View <a href = 'show_episodes.php?showID=$showID&showTitle=$title'>All Episodes</a><br>";
				}
				else{
					echo "<br>Television Show not found";
				}
				//Close the statement
				mysqli_stmt_close($stmt);
			}

			//If customer is Logged In,
			//Allow to Queue, if show does not already exists.

			if($_SESSION['cnum'] != ""){
				$custID = $_SESSION['cnum'];
				//Create the query to get the showID's from the customer's queue
				$query = 	"SELECT showID 
							FROM CUST_QUEUE 
							WHERE custID=? AND showID=?";

				if(!$stmt = mysqli_prepare($link, $query)){
					die("Error in Query");
				}

				//Bind the parameter $custID and $showID to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 'ss', $custID, $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $name and $price
				mysqli_stmt_bind_result($stmt, $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Store the result so we can get the number of rows
				mysqli_stmt_store_result($stmt);

				//Find out how many rows in the result
				$rows = mysqli_stmt_num_rows($stmt);

				//if logged in, and show does not already exist in queue, allow to add to queue.
				if($rows === 0){
					echo "<br><a href= 'queueshow.php?showID=$showID'>Add to Queue</a><br>";
				}

				//Close the statement
				mysqli_stmt_close($stmt);

			}

			//Query for Main Cast
			echo "<p><b>Main Cast:</b></p>";

			$query = 	"SELECT CONCAT(fname, ' ', lname), MAIN_CAST.actID, role 
						FROM MAIN_CAST, ACTOR 
						WHERE MAIN_CAST.actID=ACTOR.actID AND showID=? 
						ORDER BY lname";  

			if($stmt = mysqli_prepare($link, $query)){
				//Bind the parameter $showID to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $actorName,$actID,$actorRole
				mysqli_stmt_bind_result($stmt, $actorName, $actID, $actorRole);

				//Get the result and print it
				while(mysqli_stmt_fetch($stmt)){
					echo "<a href = 'actorinfo.php?actorID=$actID&actorName=$actorName'>$actorName </a> 
						as $actorRole<br>";
				}
			}  
			//Close the statement
			mysqli_stmt_close($stmt);

			//Query for Recurring Cast
			echo "<p><b>Recurring Cast:</b></p>";

			$query = 	"SELECT CONCAT(fname, ' ',lname), RECURRING_CAST.actID, role, COUNT(*) 
						FROM RECURRING_CAST, ACTOR 
						WHERE RECURRING_CAST.actID=ACTOR.actID AND showID=? 
						GROUP BY CONCAT(fname, ' ',lname) 
						ORDER BY lname";  

			if ($stmt = mysqli_prepare($link, $query)) {
				//Bind the parameter $showID to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $showID);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variables $actorName,$actID,$actorRole,$numAppeared
				mysqli_stmt_bind_result($stmt, $actorName,$actID,$actorRole,$numAppeared);

				//Get the result and print it
				while(mysqli_stmt_fetch($stmt)) {
					echo "<a href = 'actorinfo.php?actorID=$actID&actorName=$actorName'>$actorName</a> 
						appeared in $numAppeared episodes as <i>$actorRole</i><br>";
				}
			}  
			//Close the statement
			mysqli_stmt_close($stmt);



			//Close the link to the database
			mysqli_close($link);
		?>
	</body>
</html?
