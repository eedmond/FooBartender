<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
?>
<?php //include "login.php"; ?>
<?php //include "login_head.php"; ?>
<?php 
	require_once(dirname(__FILE__) . "/Utilities/Database.php");
	$database = new Database(); 
?>
<!DOCTYPE HTML>
<!--
	Tessellate 1.0 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>FooBartender: Admin Portal</title>
		<link rel="icon" type="image/png" href="images/nonalcoholic.png"/>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="css/font/GoogleFonts.css" rel="stylesheet" type="text/css" />
		<![if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]>
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>
		<script src="js/adminPortalJS.js"></script>
		<script src="js/tableGuiJS.js"></script>

		<!-- bxSlider Javascript file -->
		<script src="jquery.bxslider/jquery.bxslider.min.js"></script>
		<!-- bxSlider CSS file -->
		<link href="jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />

		<link href="css/adminPortal.css" rel="stylesheet" />
		<link href="css/tableGui.css" rel="stylesheet" />
  <!--script src="js/jquery-1.10.2.js"></script-->
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
	</head>
	<body>
		<!-- Main -->
			<section id="Main" class="main">
				<header>
					<div class="container">
						<h2>Welcome to the FooBartender: Admin Portal</h2>
					</div>
				</header>
				<div class="content dark style1 featured">
					<div class="container">
						<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/Tablethumb.png" alt="Mixed Drink Image" width=150 height=150>
									<header>
										<h3><a href="#TableSetup" class="button scrolly">Table Setup</a></h3>
									</header>
								<br><br>
								</section>
							</div>
							<div class="6u">
								<section>
									<img src="images/Categories/nonalcoholic.png" alt="Shot Image" width=150 height=150>
									<header>
										<h3><a href="#ManageDrinks" class="button scrolly">Manage Drinks</a></h3>
									</header>
									<br><br>
								</section>
							</div>
						</div>
						<div class="column">
							<div class="row">
							<div class="6u">
								<section>
									<img src="images/Categories/Queue.png" alt="Non-Alcoholic Image" width=175 height=175>
									<header>
										<h3><a href="#ManageQueue" class="button scrolly">Manage Queue</a></h3>
									</header>
								</section>
							</div><br>
							<div class="6u">
								<section>
									<img src="images/Categories/error.png" alt="Custom Drink" width=150 height=150>
									<header>
										<h3><a href="#ManageErrors" class="button scrolly">Manage Errors</a></h3>
									</header>
								</section>
							</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<!-- Table Setup -->
			<section id="TableSetup" class="main">
				<header>
					<div class="container">
						<h2>Table Setup</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Setup which drinks are on the table and where</div>
						<div id="setVolumeDebug"></div>
					</div>
					<br/>
					<div class="header" align="center">
						<u>The Single Table</u>
					</div>
					<div>
						<div class="table_c3 table_first">Station</div>
						<div class="table_c1">Name</div>
						<div class="table_c4">Volume</div>
						<div class="table_c3">Edit</div>
					</div>
					<?php
						$results = $database->StartQuery()
							->select('station', 'name', 'volume')
							->from(Database::SingleTable, 'u')
							->where('station > -1')
							->orderBy('station')
							->execute()
							->fetchAll();
						
						//TODO: sort $results
							
						foreach ($results as $row)
						{
					?>
							<div>
								<div class="table_c3 table_first"><?php echo $row['station']; ?></div>
								<div class="table_c1"><?php echo $row['name']; ?></div>
								<div class="table_c4">
									<span class="swapOn"><?php echo $row['volume']; ?></span>
									<input class="swapOff table_textbox" type="text" style="display: none;"/>
								</div>
								<div class="table_c3">
									<a class="button table_button swapOn" onclick="javascript:swapIn(this)">Edit</a>
									<a class="button table_button swapOff" onclick="javascript:updateStationVolume(this)" style="display: none;" onclick="">Done</a>
								</div>
							</div>
					<?php
						}
					?>
					
					<?php
						require(dirname(__FILE__) . "/TableGui/TableGui.php");
					?>
					
					
					<div class="container">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
			
		<!-- Manage Drinks -->
			<section id="ManageDrinks" class="main">
				<header>
					<div class="container">
						<h2>Manage Drinks</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Create or delete drinks from the database</div><br>
						<form id="SQLform" name="SQLform" action="/Utilities/SQLCommand.php" method="POST">
							<div align="center"><input type="text" size="40" name="command" value="Custom SQL Command" /><input type="submit" value="Execute Command" style="border:none; background-color:transparent; color:white;"></div><br>
						</form>
					</div>
					<div class="container small">
						<form id="newDrinkForm" method="POST" action="insertMixed.php">
							<div class="row">
								<div class="12u" align="center">
									<ul class="actions">
										<li><input type="text" id="drinkName" class="button" name="drinkName" size="10" value="Drink Name"/></li>
										<li><input type="submit" class="button" value="Submit Drink"/></li>
										<li><input type="reset" class="button alt" value="Clear Order" onclick="javascript:onClearClick();" /></li>
									</ul>
								</div>
							</div>
						<?php
							require(dirname(__FILE__) . '/customDrinkMenu.php');
						?>
						</form>
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>
		<!-- Manage Queue -->
			<section id="ManageQueue" class="main">
				<header>
					<div class="container">
						<h2>Manage Queue</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">View and erase contents of the queue</div>
					</div>
					<div id="clearQueueButton" align="center">
						<a class="button" onclick="javascript:clearEntireQueue();" style="margin-bottom: 10px;">Clear Entire Queue</a>
					</div>
					<div id="clearQueueDebug"></div>
					
					<!-- Table Headers -->
					<div>
						<div class="table_c1 table_first">Station</div>
						<div class="table_c2">Order String</div>
						<div class="table_c3">Delete</div>
					</div>
						
					<?php
						if (!isset($database))
						{
							$database = new Database();
						}
						
						$results = $database->StartQuery()
							->select('station', 'orderString')
							->from(Database::QueueTable, 'u')
							->orderBy('station')
							->execute()
							->fetchAll();
							
						foreach ($results as $row)
						{
					?>
							<div>
								<div class="table_c1 table_first">
									<?php echo $row['station'] ?>
								</div>
								<div class="table_c2">
									<?php echo $row['orderString'] ?>
								</div>
								<div class="table_c3" style="padding: 0px;">
									<a class="button table_button" 
										onclick="javascript:clearFromQueue( <?php echo $row['station']; ?> )">X</a>
								</div>
							</div>
					<?php
						}
					?>
					<div class="container">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>
					</div>
				</div>
			</section>


		<!-- Manage Errors -->
			<section id="ManageErrors" class="main">
				<header>
					<div class="container">
						<h2>Manage Errors</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="header">
						<div align="center">Halt all processes</div>
					</div>
					<div class="container small">
						<div class="row">
							<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
								<li><a href="#TableSetup" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"></a></li>
								<li><a href="#ManageDrinks" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"></a></li>
								<li><a href="#ManageQueue" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"></a></li>
								<li><a href="#ManageErrors" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"></a></li>
							</ul>
						</div>	
					</div>
				</div>
			</section>
			
		
		<!-- Footer -->
			<section id="footer">
				<a href="index.php?logout=true" class="button scrolly">Logout</a>
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
