<?php
	session_start();
	session_unset();
	$_SESSION['orderStatus'] = 'free_to_order';
?>
