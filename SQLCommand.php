<?php
	$db = new PDO('sqlite:FB.db');
	$query = $_POST['command'];
	$db->query($query);
	unset($db);
?>
