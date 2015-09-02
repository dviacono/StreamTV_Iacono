<!-- 
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Receives actor or show post data from streamtv.php.
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
		<hr WIDTH="100%">
	</center>

	<body style="font-family:'Calibri'">
		<!-- PHP -->
		<?php 
  			//require the connect.php file to connect to the database.
			require_once "connect.php";

			$allSearch = $_POST['newSearch'];
  
			echo "Search Results for: $allSearch";

			//Create the wildcard search string for the query
			$allSearch = '%'.$allSearch.'%';

			//Create the query string with marker for the search string
  			$query = 	"SELECT showID, title 
  						FROM SHOWS 
  						WHERE (title LIKE ?)";
  
  			//Prepare the query statement

  			if ($stmt = mysqli_prepare($link, $query)){
			
				//Bind the parameter $tsrch to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $allSearch);
	
				//Execute the query statement
				mysqli_stmt_execute($stmt);
	
				//Bind the result to the variables $name, $showID
				mysqli_stmt_bind_result($stmt, $showID, $title);
	
				//Store the result so we can get the number of rows
				mysqli_stmt_store_result($stmt);
	
				//Find out how many rows in the result
				$rows = mysqli_stmt_num_rows($stmt);
				echo "<br>$rows Shows match the search<br>";
	
				if ($rows > 0){
					//Loop through the rows of the result and print
					while (mysqli_stmt_fetch($stmt)){
						echo "<p><a href = 'showinfo.php?showID=$showID'>$title</a>";
					}
				}
				else{
					echo "<p>No Shows match<br>";
				}
			}
			
			//Close the statement
			mysqli_stmt_close($stmt);

			//And Search Again for Actors
			//Create the wildcard search string for the query
		  	$allSearch = '%'.$allSearch.'%';
		
			//Create the query string with marker for the search string
			$query = 	"SELECT CONCAT(fname, ' ', lname), actID 
						FROM ACTOR 
						WHERE (CONCAT(fname, ' ', lname) LIKE ?)";

			//Prepare the query statement
  			if ($stmt = mysqli_prepare($link, $query)) {
				//Bind the parameter $tsrch to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 's', $allSearch);
		
				//Execute the query statement
				mysqli_stmt_execute($stmt);
		
				//Bind the result to the variables $name, $actID
				mysqli_stmt_bind_result($stmt, $name, $actID);
		
				//Store the result so we can get the number of rows
				mysqli_stmt_store_result($stmt);
		
				//Find out how many rows in the result
				$rows = mysqli_stmt_num_rows($stmt);
				echo "<br><p>$rows Actors match the search<br>";
			
				//If the list has elements, print them
				if ($rows > 0) {
					//Loop through the rows of the result and print
					while (mysqli_stmt_fetch($stmt)) {
						echo "<p><a href = 'actorinfo.php?actorID=$actID&actorName=$name'>$name</a>";
					}
				}
				else{
					echo "<p>No Actors match<br>";
				}
  			}
			//Close the statement
  			mysqli_stmt_close($stmt);

  			//Close the connection
  			mysqli_close($link);
		?>
	</body>
</html>	