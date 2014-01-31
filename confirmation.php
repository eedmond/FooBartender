<?php
if (!session_start())
	echo "Error setting up the session.";
if (!isset($_SESSION["order"]) || !$_SESSION["order"])
{
	$_SESSION["order"] = TRUE;
	$drinkFile = fopen("drink.txt","a");
	if ($drinkFile == FALSE)
		echo "Error opening the drinks file.\n";
	//echo fgets($drinkFile);
	$data = array($_POST["name"], $_POST["drink"]);
	echo var_dump($data) . "\n";
	if (!fputcsv($drinkFile, $data))
		echo "Fatal error ordering your drink.";
	fclose($data);
}
else
{
	session_destroy();
}
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
