<?php
	//Author: David Iacono
	//CSC 436 Database Management Systems
	//Spring 2015 DiPippo
	//Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php

	//This PHP class checks the login information to verify the customer's credentials

	//Create session
	session_start();

	//Collect session and post tokens
	$session_token = $_SESSION['token'];
	$post_token = $_POST['token'];

	//Compare session and post tokens
	if($session_token != $post_token) {
		die("Token mismatch");
	}

	//Retrieve username and password from post
	$uname = $_POST['uname'];
	$pword = $_POST['pword'];

	//Hash the password
	$hash_pword = md5(uniqid($pword, TRUE));

	//Connect to the database
	include 'connect.php';
	
	//Retrieve the customer ID from the database with the given username
	//Create query string to look up password
	$query = "SELECT password, custid
	        FROM CUSTOMER
	        WHERE username = ?";
	        
	//Prepare the query statement
	if (!$stmt = mysqli_prepare($link, $query)) {
		die("Error in query");
	}
		
	//Bind the parameter $uname to the ? marker in the query string
	mysqli_stmt_bind_param($stmt, 's', $uname);
		
	//Execute the query statement
	mysqli_stmt_execute($stmt);
		
	//Bind the result to the variables $retrievedPwd and $cnum
	mysqli_stmt_bind_result($stmt, $retrievedPwd, $cnum);
		
	//If no result returned from database, redirct to login page
	if(!mysqli_stmt_fetch($stmt)){
		header('Location: login.php');
	}
		
	//Verify password using php function
	$valid = password_verify($hash_pword, $retrievedPwd);
	
	//Compare the hashed passwords
	if(!$hash_pword === $retrievedPwd){
		//Incorrect password. Redirect to login_form.
		$_SESSION['cnum'] = "";
		header('Location: login.php');
	}
	else {
		//Redirect to home page after login.
		//Set session variable cnum to the customer number collected from the query
		$_SESSION['cnum'] = $cnum;
		header('Location: streamtv.php');
	}

	//Close the SQL stream
	mysqli_close();
?>