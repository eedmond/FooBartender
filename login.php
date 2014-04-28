<?php

function getAuth($stuff)
{
    header('WWW-Authenticate: Basic realm="' . $stuff . '"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication failed. Please reload and try again.';
    die();
}

if (!isset($_SESSION))
	session_start();


if (!isset($_SESSION['login']) || !isset($_SESSION['login_try']))
{
	//session_regenerate_id();
	$_SESSION['login'] = false;
	$_SESSION['login_try'] = true;
	getAuth("Please enter your username and password");
}

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']))
{
	$username = $_SERVER['PHP_AUTH_USER'];
	$password = $_SERVER['PHP_AUTH_PW'];
}
else
{
	$username = "";
	$password = "";
}



if ($_SESSION['login'] == false && $_SESSION['login_try'] == false)
{
	$_SESSION['login_try'] = true;
	getAuth("Please enter your username and password");
}
else if ($_SESSION['login'] == false && $_SESSION['login_try'] == true)
{
	if ($username == "pi" && $password == "1335e")
	{
		$_SESSION['login_try'] = false;
		$_SESSION['login'] = true;
	}
	else
	{
		getAuth("Incorrect username or password");
	}
}
else if ($_SESSION['login'] == true && $_SESSION['login_try'] == true) //Should never happen...
{
	echo 'Fatal error with authentication.';
	$_SESSION['login'] = false;
	$_SESSION['login_try'] = true;
	getAuth("A fatal error occured. Please reauthenticate");
}

?>
