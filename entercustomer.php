<?php
$validform = true;
$emptyform = true;

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


//Validating Form
if ($emptyform == true) {
	$firstnameerrormessage = '';
	$lastnameerrormessage = '';
	$addresserrormessage = '';
	$cityerrormessage = '';
	$stateerrormessage = '';
	$ziperrormessage = '';
} else {
	if ($validform==true) {
		echo "All data was valid.<br />";
		echo "Connecting to database server.<br />";
		try {
			$conn = new PDO("mysql:host=db78a.pair.com","1033971_3_w","RtrjqQQu");
		} catch(PDOException $e) { //this should tell us if there was a connection problem
			echo "Error connecting to server: " . $e->getMessage();
			die;
		}
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Connection to server succeeded.<br />";
		echo "Connecting to database Order Entry...<br />";
		try {
			$conn->query("USE devantebatts_OrderEntry;");
		} catch (PDOException $e) {
			echo "Error connecting to database: " . $e->getMessage();
			die;
		}
		echo "Connection to Customers database succeeded.<br />";

        
        //User ID Assignment
		do {
			$proposedID = mt_rand(1,2147482647);
			$SQL = "SELECT UserID FROM Customers WHERE UserID=:proposedID;";
			try {
				$sth = $conn->prepare($SQL);
				$sth->bindParam(":proposedID", $proposedID);
				$sth->execute();
			} catch (PDOException $e) {
				echo "Error selecting customer records: " . $e->getMessage();
				die;
			}
			echo "Query executed successfully. <br />";
			//are there records in the set?
			if($sth->rowCount()==0) {
				echo "No records returned, can use proposedID.<br />";
				$UserID = $proposedID;
			} else {
				echo $sth->rowCount() . " records returned. Try again.<br />";
			}
		} while ($sth->rowCount()>0);		
		///////////end of UserID assignment
		
		echo "Preparing SQL statement.<br />";
		//NO VARIABLES ALLOWED IN SQL
		$SQL = "INSERT INTO Customers(UserID, FirstName, LastName, Adress1, City, State, Zip)";
		//ALL USER ENTERED VALUES are going to be parameters -> variable names that start with a colon
		$SQL .= " VALUES (:UserID, :FirstName, :LastName, :Adress1, :City, :State, :Zip);";
		echo "This is the SQL statement: " . $SQL . "<br />";
		echo "Preparing to add Customer record. <br />";
		try {
			$sth = $conn->prepare($SQL);
            $sth->bindParam(":UserID", $UserID);
			$sth->bindParam(":FirstName", $FirstName);
			$sth->bindParam(":LastName", $LastName);
			$sth->bindParam(":Adress1", $$Adress1);
			$sth->bindParam(":City", $City);
			$sth->bindParam(":State", $State);
			$sth->bindParam(":Zip", $Zip);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error adding Customer record: " . $e->getMessage();
			die;
		}
		echo "Record added to database. <br />";
		Header("Location: entercustomer.php");
	}
}
?>
<html>
<body>
<b>Customer Entry</b>
<form action="entercustomer.php" method="post">

First Name: <input type="text" name="FirstName" value="<?php echo $FirstName; ?>">
<span style="color: red;"><?php echo $firstnameerrormessage; ?></span><br />

Last Name: <input type="text" name="LastName" value="<?php echo $LastName; ?>">
<span style="color: red;"><?php echo $lastnameerrormessage; ?></span><br />

Address: <input type="text" name="Adress1" value="<?php echo $Adress1; ?>">
<span style="color: red;"><?php echo $addresserrormessage; ?></span>
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
