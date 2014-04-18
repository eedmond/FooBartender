<?php
	$to = "triomproductions@gmail.com";
	$subject = $_POST["Name"] . " wants to make a new drink";
	$subMess = $_POST["message"];
	$message = wordwrap($subMess, 70);
	$headers = "From: bartender@foobartender.local\n"; 
	if (!mail('triomproductions@gmail.com', 'hello', 'Yo'))
		echo "<script type='text/javascript'>alert('NEWP');</script>";
	else
		echo "<script type='text/javascript'>alert('" . $message . "');</script>";
?>
<html>
	<head>
		<title>Thank you for your feedback!</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keyword" content="" />
		<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>

		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet href="css/style-wide.css" />
		</noscript>
	</head>

	<body>

	<script type="text/javascript">
		$(document).ready(function() {
			$("body").css("display", "none");
			$("body").fadeIn(500);
		});

		function fadeout() {
			$("body").fadeOut(500);
		}
	</script>
			<section id = "header" class="dark">
				<h3>Thank you for submitting your custom drink. We will get back to you on that.</h3>
				<footer>
					<a href="index.php#order" onclick="javascript:fadeout()" class="button scrolly">Back to FooBartender</a>
				</footer>
			</section>
</body>
</html>
