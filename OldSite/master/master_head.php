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
<!DOCTYPE html>
<html>
	<head>
		<link href="stylesheet.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery.min.js" ></script>
	</head>
	<body>
<?php 
	if (isset($_SESSION["login"]) && $_SESSION["login"] == true)
	{
?>
	      <h4>
		<a href="../index.html?logout=true">Log Out</a>
	      </h4>
<?php
	}

	if (isset($title))
	{
?>
		<h1><?php echo $title ?></h1>
<?php } ?>
