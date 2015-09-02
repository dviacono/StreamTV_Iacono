<?php
	//Author: David Iacono
	//CSC 436 Database Management Systems
	//Spring 2015 DiPippo
	//Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php
	session_start();
	
	//get the session information
	if(isset($_SESSION['cnum'])){
		$custID = $_SESSION['cnum'];
	}
	else{
		$custID = "";
	}
	
	//Gather URL Parameters
	$showID = $_GET['showID'];
	
	//If Customer is logged In, add the show to their Queue.
	//Otherwise, Redirect to main page to avoid error.
	if($custID == ""){
	
		header('Location: index.php');
	}
	else{
		//connect to database
		include 'connect.php';
		
		//set the date queued to todays date
		$dateQueued = date('Y-m-d');
		
		//String Query for Inserting into Queue
		$query = 	"INSERT INTO CUST_QUEUE (custID, showID, dateQueued) 
					VALUES (?, ?, ?)";
		
		if(!$stmt = mysqli_prepare($link,$query)){
			die("Error in Query");
		}
		
		//Replace ? markers with $custID $showID, and $dateQueued
		mysqli_stmt_bind_param($stmt,'sss',$custID,$showID,$dateQueued);
		
		//Execute the Query
		mysqli_stmt_execute($stmt);
		
		//after inserting has been completed, allow a hyperlink to the customer's queue
		echo "Queue Update Successful! -- <a href = 'cust_queue.php'>View Queue</a>";
		
		//close the query
		mysqli_stmt_close($stmt);
		
		//close the connection to the database
		mysqli_close($link);
		
	}
?>

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