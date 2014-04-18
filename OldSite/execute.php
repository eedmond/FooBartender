<?php
//if (!session_start())
//	echo "Error setting up the session.";
//if (!isset($_SESSION["order"]) || !$_SESSION["order"])
//{
	echo "Trying database...\n";
	try
	{
		$dbConn = new PDO("sqlite:/home/pi/testdb.db","", "");
		foreach($dbConn->query("SELECT * from single") as $row)
			print_r($row);
	}
	catch (PDOException $ex)
	{
		echo "Fatal error connecting to the database!". $ex->getMessage(),"<br/>";
	}
	//if (!$database)
	//	die("Error connecting to the database!");
	
	//$_SESSION["order"] = TRUE;

	//sqlite_close($database);
	
/*}
else
{
	session_destroy();
}*/
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="pretty.css" />
</head>
<body>
	<p>Thank you <?php echo $_POST["name"] ?>, for using the FooBartender. Your drink will be available momentarily.</p>
	<p>You ordered: <?php echo $_POST["drink"] ?></p>
	<a href="order.php">Click here</a> to order another drink.
</body>
</html>
