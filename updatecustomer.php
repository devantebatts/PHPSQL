<?php
//Validating First Name
$FirstName = htmlentities($_POST['FirstName']);
if($FirstName=='') {
	$validform = false;
	$firstnameerrormessage = 'First Name is a required field.';
} else {
	$emptyform = false;
	if (strlen($FirstName)>=30) {
		$validform = false;
		$firstnameerrormessage = 'Your first name must be less than 30 characters long.';
	}
}

//Validating Last Name
$LastName = htmlentities($_POST['LastName']);
if($LastName=='') {
	$validform = false;
	$lastnameerrormessage = 'Last Name is a required field.';
} else {
	$emptyform = false;
	if (strlen($LastName)>=30) {
		$validform = false;
		$lastnameerrormessage = 'Your last name must be less than 30 characters long.';
	}
}

//Validating Address
$Adress1 = htmlentities($_POST['Adress1']);
if($Adress1=='') {
	$validform = false;
	$addresserrormessage = 'Address is a required field.';
} else {
	$emptyform = false;
	if (strlen($Adress1)>100) {
		$validform = false;
		$addresserrormessage = 'Your address must be less than 100 characters long.';
	}
}

//Validating City 
$City = htmlentities($_POST['City']);
if($City=='') {
	$validform = false;
	$cityerrormessage = 'City is a required field.';
} else {
	$emptyform = false;
	if (strlen($City)>30) {
		$validform = false;
		$cityerrormessage = 'Your city must be less than 30 characters long.';
	}
}

//Validating State 
$State = htmlentities($_POST['State']);
if($State=='') {
	$validform = false;
	$stateerrormessage = 'We need to know your state.';
} else {
	$emptyform = false;
	if (strlen($State)>2) {
		$validform = false;
		$stateerrormessage = 'Enter your state abbreviation.';
	}
}

//Validating Zip 
$Zip = htmlentities($_POST['Zip']);
if($Zip=='') {
	$validform = false;
	$ziperrormessage = 'We need your Zip code.';
} else {
	$emptyform = false;
		if ($Zip<=9999 or $Zip >= 99999) {
			$validform = false;
			$ziperrormessage = 'Your ZIP code must be between the numbers 9999 and 99999.';
		} 
}


//Updating Database
if ($validform){
			echo "All data was valid.";
			echo "Connecting to database server. <br />";
			try {
				$conn = new PDO("mysql:host=db78a.pair.com","1033971_3_w","RtrjqQQu");
			} catch(PDOException $e) { //this should tell us if there was a connection problem
				echo "Error connecting to server: " . $e->getMessage();
				die;
			}
			echo "Connection succeeded. <br />";
			echo "Connecting to database Order Entry...<br />";
			$conn->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try{
				//if executing a query, NO USER ENTERED fields should be in query string!	
				$conn->query("USE devantebatts_OrderEntry");
			} catch(PDOException $e) { 
				echo "Error connecting to database: " . $e->getMessage();
				die;
			}
			echo "Connection to OrderEntry database succeeded. <br />";
			echo "preparing SQL statement/ <br />";
			//NO VARIABLES ALLOWED IN SQL
			//ALL USER ENTERED VALUES are going to be parameters -> variable names that start with a colon
			$SQL = "UPDATE Customers SET UserID=:UserID, FirstName=:FirstName, LastName=:LastName, Adress1=:Adress1, City=:City, State=:State, Zip=:Zip";
			$SQL .= "WHERE UserID=:UserID;";
			echo "this is the SQL Statement: " . $SQL . "<br />";
			echo "Preparing to add record. <br />";

			try{
				$sth = $conn->prepare($SQL);
                $sth->bindParam(":UserID", $UserID);
                $sth->bindParam(":FirstName", $FirstName);
                $sth->bindParam(":LastName", $LastName);
                $sth->bindParam(":Adress1", $$Adress1);
                $sth->bindParam(":City", $City);
                $sth->bindParam(":State", $State);
                $sth->bindParam(":Zip", $Zip);
                $sth->execute();
			} catch(PDOException $e) { 
				echo "Error adding recipe record: " . $e->getMessage();
				die;
			}
			echo "Record added to database. <br />";
			die;
		}
		
//Echo Record in Form 
		echo "Connecting to database server.<br />";
		try {
			//variable stores the connection -> $conn
			//PDO is a php data object -> helps prevent SQL injection
			//host = Database server host name
			//username = name of READ ONLY user
			//password = that user's password
			$conn = new PDO("mysql:host=db78a.pair.com","1033971_3_r","VECzD4Ln");
		} catch(PDOException $e) { //this should tell us if there was a connection problem
			echo "Error connecting to server: " . $e->getMessage();
			die;
		}
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connection to server succeeded.<br />";
		echo "Connecting to database OrderEntry...<br />";
		try {
			//if executing a query, NO USER ENTERED fields should be in query string!
			$conn->query("USE devantebatts_OrderEntry;");
		} catch (PDOException $e) {
			echo "Error connecting to database: " . $e->getMessage();
			die;
		}
		echo "Connection to OrderEntry database succeeded.<br />";
		//SQL statement will have user-entered data, so BIND needed
		$SQL = "SELECT UserID, FirstName, LastName, Adress1, City";
		$SQL .= ", State, Zip FROM Customers WHERE UserID=:UserID;";
		try {
			$sth = $conn->prepare($SQL);
			$sth->bindParam(":UserID", $UserID);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error selecting customer records: " . $e->getMessage();
			die;
		}
		echo "Query executed successfully. <br />";
		//is there one record in the set?
		if($sth->rowCount()!=1) {
			echo "Error. No records were returned or more than one record was returned.<br />";
			$validform = false;
		} else {
			echo $sth->rowCount() . " records returned.<br />";
			$result = $sth->fetch();
			$UserID = $result['UserID'];
			$FirstName = $result['FirstName'];
			$LastName = $result['LastName'];
			$Adress1 = $result['Adress1'];
			$City = $result['City'];
			$State = $result['State'];
			$Zip = $result['Zip'];
		}
		//$result is an array that holds the dataset

	
if ($validform==false) {
	echo "Data was invalid. Please contact technical support.<br /><br />";
} else {
	echo "User wants to update recipe with UserID=". $UserID ."<br />";
}
?>
<html>
<body>
<b>Customer Entry</b>
<form action="entercustomer.php" method="post">
    
    UserID: <?php echo $UserID; ?><input type="hidden" name="UserID" value="<?php echo $UserID; ?>">
    <span style="color: red;"><?php echo $useriderrormessage; ?></span><br />

    First Name: <input type="text" name="FirstName" value="<?php echo $FirstName; ?>">
    <span style="color: red;"><?php echo $firstnameerrormessage; ?></span><br />

    Last Name: <input type="text" name="LastName" value="<?php echo $LastName; ?>">
    <span style="color: red;"><?php echo $lastnameerrormessage; ?></span><br />

    Address: <input type="text" name="Adress1" value="<?php echo $Adress1; ?>">
    <span style="color: red;"><?php echo $addresserrormessage; ?></span><br />

    City: <input type="text" name="City" value="<?php echo $City; ?>"> 
    <span style="color: red;"><?php echo $cityerrormessage; ?></span><br />

    State: <input type="text" name="State" value="<?php echo $State; ?>"> 
    <span style="color: red;"><?php echo $stateerrormessage; ?></span><br />

    Zip Code:<input type="text" name="Zip" value="<?php echo $Zip; ?>"> 
    <span style="color: red;"><?php echo $ziperrormessage; ?></span><br />

    <input type="submit">

</form>
</body>
</html>
