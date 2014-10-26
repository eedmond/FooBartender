<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	require_once(dirname(__FILE__) . '/../Utilities/Order.php');
	require_once(dirname(__FILE__) . '/newOrderFunctions.php');
	
	session_start();
	$drinkName = $_GET['drinkName'];
?>
<head>
	<title>Thank you for ordering!</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keyword" content="" />
	<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
	<script src="../js/jquery.min.js"></script>
	<script src="../js/skel.min.js"></script>
	<script src="../js/init.js"></script>
	<script src="../js/newOrderJS.js"></script>
	<link rel="stylesheet" href="../css/skel-noscript.css" />
	<link rel="stylesheet" href="../css/style.css" />
</head>
<body>
	<!-- Main Screen -->
		<section id="header" class="dark">
			<header>
				<h1>Thank you for ordering a <?php echo GetDrinkName() ?></h1>
			<?php
			//If it is the first time around
			if (IsFreeToOrder())
			{
				$_SESSION['post-data'] = $_POST;
				$_SESSION['orderStatus'] = 'about_to_order';
			?>
				<p>Please wait while your order is being processed.</p><br>
				<p>Think about it. There's millions of calculations I'm processing.</p>
				<script language="javascript">
					location.href="<?php echo $_SERVER['SCRIPT_NAME'], '?', $_SERVER['QUERY_STRING'] ?>";
				</script>
			<?php
			}
			//If it is the second (or more) time around
			else
			{
				$text = GetOrderResult();
			?>
				<p> <?php echo $text; ?> </p><br>
				<footer><a href="../index.php" class="button scrolly">Back to FooBartender</a></footer>
			<?php
				if (IsRatableDrink($drinkName))
				{ 
			?>
					<div id="rateSection">
						<h1>Rate this drink!</h1><br>
						<input type="number" class="number" id="rating" value="0" min="0" max="10">/10
						<input type="button" class="button" id="rate" name="rate" value="Rate" alt="Rate">
					</div>
			<?php
				}
			}
			?>
			</header>
		</section>
</body>
