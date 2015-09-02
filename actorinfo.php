<!-- 
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	Display information regarding an actor.
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
				<hr WIDTH="100%"></hr>
		</center>

		<!-- PHP to return the Actor information -->
		<?php
			//Connect to the Database
			include 'connect.php';

			//Get Parameters from the URL
			$actID = $_GET['actorID'];
			$name = $_GET['actorName'];
		
			echo "<b>The actor you selected:</b><p>$name</p>";
		
			//Query for the Main Cast
			$query = 	"SELECT S.showID, title, role 
						FROM SHOWS S, MAIN_CAST M 
						WHERE S.showID = M.showID AND actID = ?";
		
			//Prepare the SQL Statement
			if(!$stmt=mysqli_prepare($link,$query)){
				die("Error in query");
			}

			//Place $actID into the ? marker
			mysqli_stmt_bind_param($stmt, "s", $actID);
		
			//Execute the Query Statement
			mysqli_stmt_execute($stmt);
		
			//Bind the Result Set into variables $showID $sTitle and $role
			mysqli_stmt_bind_result($stmt,$showID,$sTitle,$role);
		
			//Store the Result in order to get the Row count
			mysqli_stmt_store_result($stmt);

			//Get the Number of rows in the result set
			$rows = mysqli_stmt_num_rows($stmt);
			//Display Result List
		
			echo "<p><u><b>Main Cast:</b></u><br>";
		
			//If the query returns with information,
			if($rows != 0){
				//print the Main Cast
				while(mysqli_stmt_fetch($stmt)){
					echo"<br><a href='showinfo.php?showID=$showID'>$sTitle</a> as <i>$role</i>";
				}
			}

			//Echo that it didn't find anything
			else{
				echo "$name is not in the Main Cast of any shows";
			}
		
			//Close the statement before beginning next query
			mysqli_stmt_close($stmt);
		
			//Retrieve the Main Cast
			$query = 	"SELECT S.showID, title, role 
						FROM SHOWS S, RECURRING_CAST R 
						WHERE S.showID = R.showID AND actID = ? 
						GROUP BY role";
		
			//Prepare the Statement
			if(!$stmt=mysqli_prepare($link,$query)){
				die("Error in Query");
			}
		
			//set #actID as ? marker
			mysqli_stmt_bind_param($stmt, "s", $actID);
		
			//Execute the Query statement
			mysqli_stmt_execute($stmt);
		
			//Bind the Result Set into variables $showID $sTitle and $role
			mysqli_stmt_bind_result($stmt, $showID, $sTitle, $role);
		
			//Store the result in order to get the row count
			mysqli_stmt_store_result($stmt);
		
			//Get the Number of Rows in the result set
			$rows = mysqli_stmt_num_rows($stmt);
			
			//Display the Guest Cast title
			echo "<p><b><u>Guest Cast:</u></b><br>";

			//If the query returns a value
			if($rows != 0){
				//Display all results from the query
				while(mysqli_stmt_fetch($stmt)){
					echo"<br><a href='showinfo.php?showID=$showID'>$sTitle</a> as <i>$role</i>";
				}
			}

			//Otherwise State that we found nothing
			else{
				echo "$name is not in the guest cast of any shows";
			}
			//close the statement
			mysqli_stmt_close($stmt);

			//close connection to the DB
			mysqli_close($link);
		?>
	</body>
</html>
