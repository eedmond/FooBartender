<html>
<head>
	<link rel="stylesheet"  type="text/css" href="pretty.css" />
</head>
<body>
	<h1>FooBartender</h1>
	<p>The official test: is PHP actually loaded on this website?</p>
	<?php 
	echo "Available drinks:"; 
	?>
	<?php echo exec("./order"); ?>
</body>
</html>
