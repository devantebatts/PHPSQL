<?php
	$validform = true;
	$UserID = $_GET['UserID'];
	echo "The user entered UserID: ". $UserID ."<br />";
	
	//validate RID
	if ($UserID=='') {
		$validform = false;
	} else if (!is_numeric($UserID)){
		$validform = false;
	}


//Confirming Deletion
$confirm = $_GET['confirm'];
	if ($confirm=='yes') {
		//delete the record
		echo "Going to delete UserID: ". $UserID . "<br />";
		echo "Connecting to database server.<br />";
		try {
			$conn = new PDO("mysql:host=db78a.pair.com","1033971_3_w","RtrjqQQu");
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
		//SQL statement HAS user-entered data, so BIND needed
		$SQL = "DELETE FROM Customers WHERE UserID=:UserID;";
		try {
			$sth = $conn->prepare($SQL);
			$sth->bindParam(":UserID", $UserID);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error deleting recipe record: " . $e->getMessage();
			die;
		}
		echo "Customer ". $UserID ." deleted. <br />";
		echo "<a href='listcustomers.php'>Return to customer list.</a><br />";
		die;
	}

		
		
	if ($validform==false){
		echo "Data was invalid. Please contact technical support.";
        
	} else{
		echo "User wants to delete customer with UserID=". $UserID ."<br />";
		echo "Are you sure you want to delete Customer #". $UserID ."?<br />";
		echo "<a href='deletecustomer.php?UserID=". $UserID ."&confirm=yes'>YES</a> | ";
		echo "<a href='listcustomers.php'>NO</a>";
	}
?>