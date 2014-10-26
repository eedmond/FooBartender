<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	require_once(dirname(__FILE__).'/Orders/login_head.php');
	require_once(dirname(__FILE__).'/indexFunctions.php');
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Welcome to the FooBartender</title>
		<link rel="icon" type="image/png" href="images/nonalcoholic.png"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<![if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]>
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>
		<script src="js/indexJS.js"></script>
		<?php MobileOnlyIncludes(); ?>

		<!-- bxSlider Javascript file -->
		<script src="jquery.bxslider/jquery.bxslider.min.js"></script>
		<!-- bxSlider CSS file -->
		<link href="jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />

		<!--script src="js/jquery-1.10.2.js"></script-->
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
	</head>
		<ul id="NavTool" class="navbar">
			<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
			<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
			<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
			<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
			<li><a href="#Feedback" class="scrolly fa solo"><img src="images/Categories/comments.png" alt="Comments Image"><span>Dribbble</span></a></li>
		</ul>
	<body>

		<!-- Header -->
		<section id="header" class="dark">
			<header>
				<h1>Welcome to the FooBartender</h1>
				<p>Designed and built by Michael Cardoso,<br/>Jonathan Soifer, Eric Edmond, and Ian Blaauw</p>
			</header>
			<footer>
				<a href="#order" class="button scrolly">Order a Drink</a>
			</footer>
		</section>
			
		<!-- Order -->
		<section id="order" class="main">
			<header>
				<div class="container">
					<h2>Choose the type of drink you would like to order: </h2>
				</div>
			</header>
			<div class="content dark style1 featured">
				<div class="container">
					<div class="row">
						<div class="6u">
							<section>
								<img src="images/Categories/mixeddrink.png" alt="Mixed Drink Image" width=150 height=150>
								<header>
									<h3><a href="#Mixed" class="button scrolly">Mixed Drink</a></h3>
								</header>
							<br><br>
							</section>
						</div>
						<div class="6u">
							<section>
								<img src="images/Categories/shot.png" alt="Shot Image" width=150 height=150>
								<header>
									<h3><a href="#Shots" class="button scrolly">Shot</a></h3>
								</header>
								<br><br>
							</section>
						</div>
					</div>
					<div class="column">
						<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image" width=175 height=175>
									<header>
										<h3><a href="#Non_Alcoholic" class="button scrolly">Non-Alcoholic</a></h3>
									</header>
								</section>
							</div><br>
							<div class="6u">
								<section>
									<img src="images/Categories/customdrink.png" alt="Custom Drink" width=150 height=150>
									<header>
										<h3><a href="#Custom" class="button scrolly">Custom</a></h3>
									</header>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
			
		<!-- Mixed Drinks -->
		<section id="Mixed" class="main">
			<header>
				<div class="container">
					<h2>Mixed Drinks</h2>
				</div>
			</header>
			<div class="content dark style2">
				<div class="container">
					<div class="row">
						<div class="4u">
							<section>
								<h3>A splash of naughty, a shot of nice, these mixed drinks are just for you!</h3>
							</section>
							<div class="hidden drinkDesc" id="MixedDesc">Some test Text</div>
							<section class="hidden drinkButton" id="OrderMixed">
								<footer>
									Drink amount:
									<select id="drinkAmount" name="drinkAmount" value="Full">
										<option value="Full">Full</option>
										<option value="Half">Half</option>
										<option value="Taste">Taste</option>
									</select><br><br>
									<a onclick="javascript:submitDrink('mixed')" class="button">Submit Order</a>
								</footer>
							</section>
						</div>
						<div class="8u">
							<div class="slider" id="mixedDrinkSlider"></div>
						</div>
						<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
							<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
							<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
							<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
							<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
			
		<!-- Shots -->
		<section id="Shots" class="main">
			<header>
				<div class="container">
					<h2>Shots</h2>
				</div>
			</header>
			<div class="content dark style2">
				<div class="container">
					<div class="row">
						<div class="4u">
							<section>
								<h3>Straight, no chaser.</h3>
							<div class="hidden drinkDesc" id="ShotDesc">Some test Text</div>
							<section class="hidden drinkButton" id="OrderShot">
								<footer>
									<a onclick="javascript:submitShot()" class="button">Submit Order</a>
								</footer>
							</section>
							</section>
						</div>
						<div class="8u">
							<div class="slider" id="shotSlider"></div>
						</div>
						<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
							<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
							<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
							<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
							<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<!-- Basic Elements -->
		<section id="Non_Alcoholic" class="main">
			<header>
				<div class="container">
					<h2>Non-Alcoholic</h2>
				</div>
			</header>
			<div class="content dark style2">
				<div class="container">
					<div class="row">
						<div class="4u">
							<section>
								<h3>For the light of stomach</h3>
							</section>
							<div class="hidden drinkDesc" id="NonAlcoholicDesc">Some Test Text</div>
							<section class="hidden drinkButton" id="OrderNonAlcoholic">
								<footer>
									<a onclick="javascript:submitDrink('nonAlcoholic')" class="button">Submit Order</a>
								</footer>
							</section>
						</div>
						<div class="8u">
							<div class="slider" id="nonAlcoholicSlider">
							</div>
						</div>
						<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
							<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
							<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
							<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
							<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<!-- Custom -->
		<section id="Custom" class="main">
			<header>
				<div class="container">
					<h2>Custom Drink</h2>
				</div>
			</header>
			<div class="content dark style2">
				<div class="header">
					<div align="center">All the power you could ever ask for is right at your fingertips</div><br>
				</div>
				<div class="container small">
					<form id="customForm" method="post" action="Orders/newOrder.php?orderType=Custom&drinkName=Custom">
						<div class="row">
							<div class="12u" align="center">
								<ul class="actions">
									<li><input type="submit" class="button" value="Order Custom Drink" onclick="javascript:FreeSession();" /></li>
									<li><input type="reset" class="button alt" value="Clear Order" onclick="javascript:onClearClick();" /></li>
								</ul>
							</div>
						</div>
					<?php require(dirname(__FILE__).'/customDrinkMenu.php');?>
					</form>
					<div class="row">
						<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
							<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
							<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
							<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
							<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
						</ul>
					</div>	
				</div>	
			</div>
		</section>
			
		<!-- Feedback -->
		<section id="Feedback" class="main">
			<header>
				<div class="container">
					<h2>How are we doing?</h2>
				</div>
			</header>
			<div class="content dark style2">
				<div class="header">
					<div align="center">Use to form below to give us any feedback or suggestions!</div><br><br>
				</div>
				<div class="container small">
					<form method="post" action="mailCustom.php">
						<div class="row half">
							<div class="6u"><input type="text" class="text" placeholder="Your Name" name="Name" /></div>
							<div class="6u"><input type="text" class="text" placeholder="Your Email" name="Email" /></div>
						</div>
						<div class="row half">
							<div class="12u"><textarea name="message" placeholder="Message" name="Message"></textarea></div>
						</div>
						<div class="row">
							<div class="12u">
								<ul class="actions">
									<li><input type="submit" class="button" value="Send Message" /></li>
									<li><input type="reset" class="button alt" value="Clear Form" /></li>
								</ul>
							</div>
						</div>
					</form>
					<div class="row">
						<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
							<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
							<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
							<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
							<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</section>
			
		<!-- Footer -->
		<section id="footer">
			<ul class="icons">
				<li><a href="http://twitter.com/justsixguys/" class="fa fa-twitter solo"><span>Twitter</span></a></li>
				<li><a href="https://www.facebook.com/foobartender/" class="fa fa-facebook solo"><span>Facebook</span></a></li>
				<li><a href="About/about.html" class="fa fa-dribbble solo"><span>Dribbble</span></a></li>
				<li><a href="http://html5up.net/" class="fa fa-github solo"><span>GitHub</span></a></li>
				<li><a href="OldSite/AdminPortal.php" class="fa fa-gears"></a></li>
				<li><a href="adminPortal.php" class="fa fa-headphones"></a></li>
			</ul>
			<div class="copyright">
				<ul class="menu">
					<li>&copy; Triom Productions, All rights reserved.</li>
					<li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
					<li>Images: Mike Cardoso</li>
					<li>Special thanks to Ian Blaauw</li>
				</ul>
			</div>
		</section>

	</body>
</html>
