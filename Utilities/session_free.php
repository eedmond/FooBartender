<?php
	session_start();
	$_SESSION['orderStatus'] = 'free_to_order';
	unset($_SESSION['drinkName']);
?>
