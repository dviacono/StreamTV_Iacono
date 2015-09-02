<!--
	Author: David Iacono
	CSC 436 Database Management Systems
	Spring 2015 DiPippo
	Using Fellowship Technologies HTML Code Standards: http://developer.fellowshipone.com/patterns/code.php
-->

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Registration</title>
	</head>
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
			//Data verification section
			//The first time the data is posted from the form, each form variable
			//is checked here.

			//Define variables and set to empty values
			$unameErr = $pword1Err = $pword2Err = "";
			$fnameErr = $lnameErr = $emailErr = $misatchErr = $tokenErr = "";
			$uname = $pword1 = $pword2 = $fname = $lname = $email = $match = "";

			//Test the data entered into the form to make sure that required fields are 
			//entered, password is verified, remove dangerous characters

			if($_SERVER["REQUEST_METHOD"] == "POST"){
				$allCorrect = true;
				if (empty($_POST["uname"])) {
					$unameErr = "Username is required";
					$allCorrect = false;
				}
				else{
					$uname = test_input($_POST["uname"]);
				}
				
				if (empty($_POST["pword1"])) {
					$pword1Err = "Password is required";
					$allCorrect = false;
				}
				else{
					$pword1 = test_input($_POST["pword1"]);
				}
			
				if(empty($_POST["pword2"])){
					$pword2Err = "Password verification is required";
					$allCorrect = false;
				}
				else{
					$pword2 = test_input($_POST["pword2"]);

					if (empty($_POST["fname"])) {
						$fnameErr = "First name is required";
						$allCorrect = false;
					}
					else{
						$fname = test_input($_POST["fname"]);
					}
				
					if(empty($_POST["lname"])){
						$lnameErr = "Last name is required";
						$allCorrect = false;
					}
					else{
						$lname = test_input($_POST["lname"]);
					}
				}
			
				if(empty($_POST["email"])){
					$emailErr = "Email is required";
					$allCorrect = false;
				}
				else{
					$email = test_input($_POST["email"]);
				}
				
				if($pword1 != $pword2){
					$mismatchErr = "Password verification does not match";
					$allCorrect = false;
				}
				else{
					$match = "match";
				}

				if($_POST['token'] != $token){
					$tokenErr = "Token failure".$_POST['token'];
					$allCorrect = false;
				}
				
				if ($allCorrect){
					//When all of the data passes the verification, session variables are set 
					//and page is redirected to register.php

					require_once "register.php";
					exit();
				}
			}

			//Function to test the data to remove dangerous characters
			function test_input($data){
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>

		<!-- Form for registration data.  
		Includes one hidden item to send the token with the form when it posts.
		-->

		<form name = "register" method="post" 
			action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<table  border="0">
				<tr>
					<td colspan="2"><p><strong>Registration Form</strong></p></td>
					</tr>
				<tr>
					<td>Username:</td>
						<td><input type="text" name="uname" maxlength="20" 
						value="<?php echo $uname;?>" />*</td>
					<td><span class="error"><?php echo $unameErr;?> </span></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="pword1"  />*</td>
					<td><span class="error"><?php echo $pword1Err;?> </span></td>
				</tr>
				<tr>
					<td>Confirm Password:</td>
					<td><input type="password" name="pword2"  />*</td>
					<td><span class="error"> <?php echo $pword2Err;?></span></td>
					<td><span class="error"> </span></td>
				</tr>
				<tr>
					<td>First Name:</td>
					<td><input type="text" name="fname" id="fname" 
					value="" />*</td>
					<td><span class="error"><?php echo $fnameErr;?> </span>
					</td>
				</tr>
				<tr>
					<td>Last Name:</td>
					<td><input type="text" name="lname" id="lname" 
					value="" />*</td>
					<td><span class="error"> <?php echo $lnameErr;?></span>
					</td>
				</tr>
				<tr>
					<tr>
						<td>Email:</td>
						<td><input type="text" name="email" id="email" 
						value="" />*</td>
						<td><span class="error"><?php echo $emailErr;?> </span></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td><input type="submit" value="Register" /></td>
					</tr>
				</tr>
			</table>
		</form>
	</body>
</html>