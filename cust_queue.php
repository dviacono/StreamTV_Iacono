<?php
	//Author: David Iacono
	//CSC 436 Database Management Systems
	//Spring 2015 DiPippo
	//Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	//This PHP class shows the customer's show queue.

	//Create session
	session_start();

	//If the session varaible csnum is set, store the variable.  Otherwise 
	//store empty customer number.
	//This page is only accessible if customer is logged in.
	
	if(isset($_SESSION['cnum'])){
		$cnum = $_SESSION['cnum'];
	}
	else{
		$cnum = "";
	}
?>

<!-- Header used for all pages on the site -->
<html>
	<center>
		<b>
			<font font = 'Calibri' size=+4>
				<a href="streamtv.php">
					streamTV
				</a>
			</font>
		</b>
	  	<hr WIDTH="100%">
	</center>
	<body style="font-family:'Calibri'">
	</body>
</html>

<!-- Display Shows that have been queued by the customer -->
<?php 
	// Include the connect.php file to connect to the database.
	include 'connect.php';

	//Create the query for user information
	$query = 	"SELECT CONCAT(fname, ' ', lname), email
				FROM CUSTOMER
				WHERE custID = ?";

	//Prepare the query statement
	if(!$stmt = mysqli_prepare($link, $query)){
		die("Error in query.");
	}
	  
	//Bind the parameter $cnum to the ? marker in the query string
	mysqli_stmt_bind_param($stmt, 's', $cnum);
	  
	//Execute the query statement
	mysqli_stmt_execute($stmt);
	  
	//Bind the result to the variables $name, $email
	mysqli_stmt_bind_result($stmt, $name, $email);
	  
	if(mysqli_stmt_fetch($stmt)){
		echo "TV Show Information For $name<br>$email";
	}
	else{
		echo "ERROR FETCHING INFO";
	}

	mysqli_stmt_close($stmt);

	//Create the query string with marker for custID
	$query = "SELECT title, Q.showID, datequeued
				FROM CUST_QUEUE Q, CUSTOMER C, SHOWS S 
				WHERE C.custID = Q.custID AND 
						S.showID = Q.showID AND 
						C.custID = ?";
				
	//Prepare the query statement
	if(!$stmt = mysqli_prepare($link, $query)){
		die("Error in query");
	}
	
	//Bind the parameter $cnum to the ? marker in the query string
	mysqli_stmt_bind_param($stmt, 's', $cnum);
	
	//Execute the query statement
	mysqli_stmt_execute($stmt);
	
	//Bind the result to the variables $showTitle, $showID, $dateQueued
	mysqli_stmt_bind_result($stmt, $showTitle, $showID, $dateQueued);
	
	//Store the result so we can get number of rows
	mysqli_stmt_store_result($stmt);

	//Get the number of rows in the result
	$rows = mysqli_stmt_num_rows($stmt);
		
	if($rows > 0){
		//Create a table to display the results
		echo "<p>streamTV Queue:<br>";
		echo "<table border=1><tr><td>SHOW TITLE</td><td>DATE QUEUED</td><td>LIST WATCHED EPISODES</td></tr>";
		
		//Display result set
		while (mysqli_stmt_fetch($stmt)) {
			echo "<td><a href = 'showinfo.php?showID=$showID'>$showTitle</a></td><td>$dateQueued</td>";
			echo "<td><a href = 'watched_info.php?showID=$showID&title=$showTitle&name=$name'><b>X</a></td></tr>";
		}
	
		echo "</table>";
	}
  	else{
		//No shows queued
		echo "<p>No shows Queued.<br>";
	}

	//Close the statement
	mysqli_stmt_close($stmt);

	//Close the database link
	mysqli_close($link);
?>