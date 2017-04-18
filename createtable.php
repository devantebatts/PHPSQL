<?php
		echo "Connecting to database server.<br />";
		try {
			//variable stores the connection -> $conn
			//PDO is a php data object -> helps prevent SQL injection
			//host = Database server host name
			//username = name of FULL CONTROL user
			//password = that user's password
			$conn = new PDO("mysql:host=db78a.pair.com","1033971_3","tETKuYXU");
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
		echo "Connection to recipes database succeeded.<br />";
		//SQL statement no user-entered data, so bind NOT needed
		$SQL = "CREATE TABLE Customers (UserID int";
		$SQL .= ", FirstName varchar(30)";
		$SQL .= ", LastName varchar(30)";
		$SQL .= ", Adress1 varchar(50)";
		$SQL .= ", City varchar(50)";
		$SQL .= ", State varchar(2)";
		$SQL .= ", Zip varchar(5)";
		$SQL .= ", CONSTRAINT useridPK PRIMARY KEY (userid)";
		$SQL .= ");";
		try {
			$sth = $conn->prepare($SQL);
			$sth->execute();
		} catch (PDOException $e) {
			echo "Error creating table: " . $e->getMessage();
			die;
		}
?>