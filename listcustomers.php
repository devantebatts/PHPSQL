<form method="get" action="listcustomers.php">
Enter the beginning ZIP: 
<input type="text" name="BegZip"><br/>
Enter the ending ZIP:
<input type="text" name="EndZip"><br/>
<input type="submit">
</form>
<?php
	$BegZip = htmlentities($_GET["BegZip"]);
	$EndZip = htmlentities($_GET["EndZip"]);
	echo "Trying to return records with category between ". $BegZip ." and ". $EndZip .".<br/>";
	
		echo "Connecting to database server.<br />";
		try {
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
		//SQL statement WILL have any user-entered data, so BIND needed
		$SQL = "SELECT UserID, LastName, Zip FROM Customers";
		$SQL .= " WHERE Zip >= :BegZip AND Zip <= :EndZip;";
		try {
			$sth = $conn->prepare($SQL);
			$sth->bindParam(":BegZip", $BegZip); 
			$sth->bindParam(":EndZip", $EndZip);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error selecting Customer records: " . $e->getMessage();
			die;
		}
		echo "Query executed successfully. <br />";
		//are there records in the set?
		if($sth->rowCount()==0) {
			echo "No records returned.<br />";
			die;
		} else {
			echo $sth->rowCount() . " records returned.<br /><br />";
		}
		//$result is an array that holds the dataset
        
        //table headers 
        echo"<table border='1'><th>Last Name</th><th>ZipCode</th><th>Delete?</th><th>Modify</th>";
		while($result = $sth->fetch()) {
			//in an array, refer to column with string inside brackets ['rid']
			echo "<tr><td>" . $result['LastName'] . "";
			echo "<td>" . $result['Zip'] . "";
			echo "<td><a href='deletecustomer.php?UserID=".$result['UserID']."'>Delete User </a>";
			echo "<td><a href='updatecustomer.php?UserID=".$result['UserID']."'>Modify User </a>";
            echo "</tr>";
		}
		
?>







