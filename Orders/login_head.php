<?php
	if (!isset($_SESSION))
	{
		session_start();
		session_regenerate_id();
		$_SESSION['login'] = false;
		$_SESSION['login_try'] = false;
	}
	
	if (isset($_GET["logout"]))
	{
		$_SESSION['login'] = false;
		$_SESSION['login_try'] = false;
		session_destroy();
	}
?>
