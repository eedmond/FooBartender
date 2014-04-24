<?php

session_start();
if(!isset($_SESSION['drinkName']) || !isset($_POST['number']))
{
	return;
}
	$drinkName = "";
	$rating = 0;
	$db = new PDO('sqlite:FB.db');
	$query = "";
	
	$drinkName = $_SESSION['drinkName'];
	$rating = $_POST['number'];

	$query = "UPDATE mixed SET rating = rating + " . $rating . ' WHERE name="' . $drinkName . '"';
	$db->query($query);

	$query = 'UPDATE mixed SET numRatings = numRatings + 1 WHERE name="' . $drinkName . '"';
	$db->query($query);

	unset($db);
?>
