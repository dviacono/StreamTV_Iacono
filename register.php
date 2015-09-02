<!-- 
  Author: David Iacono
  CSC 436 Database Management Systems
  Spring 2015 DiPippo
  Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

  Registers new in database 
-->

<html>
	<head>
		<title>streamTV</title>
	</head>
	
	<body style="font-family:'Calibri'">
		<?php
			//set membersince and renewaldates for the new user
			//Today's date in YYY-MM-DD Format
			$membersince = date("Y-m-d");

			//Renewal Date in 1 year from Today.
			$renewaldate = date('Y-m-d', strtotime('+1 year'));

			//Hash the password to be stored securely in the database
			//$hashed_pword = hash('sha256', $pword1);
			$hash_pword = md5(uniqid($pword1, TRUE));

			//Connect to the database
			include 'connect.php';

			//Create query string with marker for $uname
			$query = 	"SELECT * 
						FROM CUSTOMER 
						WHERE username = ?";

			//Prepare the query statement
			if(!$stmt = mysqli_prepare($link, $query)){
				die("Error in query");
			}

			//Bind the parameter $uname to ? marker in the query string
			mysqli_stmt_bind_param($stmt, 's', $uname);

			//Execute the query statement
			mysqli_stmt_execute($stmt);

			//Store the result so we can get number of rows
			mysqli_stmt_store_result($stmt);

			//Find out how many rows in the result 
			$rows = mysqli_stmt_num_rows($stmt);

			if ($rows != 0) {
				//if username already exists, offer to try again to register or
				//log in with existing username
				echo "Username $uname already exists.
				<p> Register with a new username: <a href= 'registration.php'>REGISTER</a>
				<p> Log in with your existing username: <a href='login.php'>LOGIN</a>";

				//Close statement
				mysqli_stmt_close($stmt);
			}
			else{
				//Create new custID to add the customer to the database
				//Query the current Max ID
				$query = "SELECT MAX(custID) FROM CUSTOMER";
				$stmt = mysqli_prepare($link, $query);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				//Bind the result to the variable #maxID
				mysqli_stmt_bind_result($stmt, $maxID);
				mysqli_stmt_fetch($stmt);

				//Seperate the prefix from the ID#
				$custID = substr($maxID,0,4);
				$idNum = (int)substr($maxID,-3);

				//Set New Customer's ID to the next available ID number
				//Assume there are no deleted customers so there are no missing ID's from 001 - MAX 
				$custID = $custID.str_pad($idNum + 1,3,"0",STR_PAD_LEFT);

				//close the query
				mysqli_stmt_close($stmt);
				//
				//NEEDS HELP ABOVE
				//
				//Create query to submit the registration information to the database
				$query = 	"INSERT INTO CUSTOMER (custID, fname, lname, email, membersince, renewaldate, password, username)
							VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";

				//Prepare the query statement
				if (!$stmt = mysqli_prepare($link, $query)) {
					die("Error in query");
				}

				//Bind parameters to markers
				mysqli_stmt_bind_param($stmt, 'ssssssss', $custID, $fname, $lname, $email, $membersince, $renewaldate, $hash_pword, $uname);

				//Execute the query statement
				mysqli_stmt_execute($stmt);

				echo "Registration successful -- <a href = 'login.php'>LOGIN</a>";

				//Close the statement
				mysqli_stmt_close($stmt);
			}

			//Close the connection
			mysqli_close($link);
		?>
	</body>
</html>