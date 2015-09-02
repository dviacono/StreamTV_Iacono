<!--reoffice_search.php-->	
<html>
	<!-- Header used for all pages on the site -->
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

	<body style="font-family:'Calibri'">
		<!-- PHP -->
		<?php 
  			//require the connect.php file to connect to the database.
			require_once "connect.php";

			//require the session token to match the post token.
			if($session_token != $post_token) {
				die("Token mismatch");
			}

			$homeSearch = $_POST['house_search'];
  
			echo "Search Results for: $homeSearch";

			//Create the wildcard search string for the query
			$homeSearch = '%'.$homeSearch.'%';

			//Create the query string with marker for the search string
  			$query = 	"SELECT House.MLS, House.Addr, House.Price 
  						FROM 	REOffice, House, Showing 
  						WHERE 	REOffice.OffID = Agent.OffID 
  								AND Agent.AgID = Showing.AgID
  								AND Showing.MLS = House.MLS
  								AND (OffID = ?)";
  
  			//Prepare the query statement

  			if ($stmt = mysqli_prepare($link, $query)){
			
				//Bind the parameter $tsrch to the ? marker in the query string
				mysqli_stmt_bind_param($stmt, 'sss', $homeSearch);
	
				//Execute the query statement
				mysqli_stmt_execute($stmt);
	
				//Bind the result to the variables $name, $showID
				mysqli_stmt_bind_result($stmt, $MLS, $Addr, $Price);
	
				//Store the result so we can get the number of rows
				mysqli_stmt_store_result($stmt);
	
				//Find out how many rows in the result
				$rows = mysqli_stmt_num_rows($stmt);
				echo "<br>$rows Houses match the search<br>";
	
				if ($rows > 0){
					// Create a table to display the results
					echo "<p>Houses of <i>$homeSearch</i><br>";
					echo "<table border=1><tr><td>MLS#</td><td>ADDRESS</td><td>PRICE</td></tr>";

					//Iterate through result set
					while (mysqli_stmt_fetch($stmt)) {
						echo "<td><center>$MLS</center></td>";
						echo "<td>$Addr</td>";
						echo "<td>$Price</td></tr>";
					}
					echo "</table>";
				}
				else{
					echo "<p>No houses match<br>";
				}

				
			}

			//Close the statement
  			mysqli_stmt_close($stmt);

  			//Close the connection
  			mysqli_close($link);
		?>
	</body>
</html>	