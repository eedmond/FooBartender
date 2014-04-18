<!DOCTYPE HTML>
<!--
	Tessellate 1.0 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Welcome to the FooBartender</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="js/modernizr.custom.79639.js"></script>
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>

<!-- jQuery library (served from Google) -->
<!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script-->
<!-- bxSlider Javascript file -->
<script src="jquery.bxslider/jquery.bxslider.min.js"></script>
<!-- bxSlider CSS file -->
<link href="jquery.bxslider/jquery.bxslider.css" rel="stylesheet" />

  <!--script src="js/jquery-1.10.2.js"></script-->
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-wide.css" />
		</noscript>
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
	</head>

				<ul id="NavTool" class="navbar">
					<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
					<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
					<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
					<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/custom.png" alt="Custom Image"><span>Dribbble</span></a></li>
					<li><a href="#Feedback" class="scrolly fa solo"><img src="images/Categories/comments.png" alt="Custom Image"><span>Dribbble</span></a></li>
					
				</ul>
	<body>

		<!-- Header -->
			<section id="header" class="dark">
				<header>
					<h1>Welcome to the FooBartender</h1>
					<p>Designed and built by Michael Cardoso, Eric Edmond, and Jonathan Soifer</p>
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
									<!--<p>A mix of alcoholic and non-alcoholic drinks. Everything from your standard Rum and Coke to more exotic flavors.</p>-->
								<br><br>
								</section>
							</div>
							<div class="6u">
								<section>
									<img src="images/Categories/shot.png" alt="Shot Image" width=150 height=150>
									<header>
										<h3><a href="#Shots" class="button scrolly">Shot</a></h3>
									</header>
									<!--<p>Just a single shot of alcohol. No chaser necessary.</p>-->
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
										<h3><a href="#Non_Alcoholic" class="button scrolly">Mocktails</a></h3>
									</header>
									<!--<p>Juices, carbonated drinks, they're all here.</p>-->
								</section>
							</div><br>
							<div class="6u">
								<section>
									<img src="images/Categories/custom.png" alt="Custom Drink" width=150 height=150>
									<header>
										<h3><a href="#Custom" class="button scrolly">Custom</a></h3>
									</header>
									<!--<p>Turn your dreams into reality. Triom Productions is not liable for any damages caused by custom drink orders.</p>-->
								</section>
							</div>
							</div>
						</div>
						<div class="row">
							<div class="12u">
								<img src="images/Categories/comments.png" alt="Comments" width=150 height=150>
								<footer>
									<h3><a href="#Feedback" class="button scrolly">Leave a Comment</a></h3>
								</footer>
							</div>
						</div>
					</div>
				</div>
			</section>

		<!-- Mixed -->
<script>
$(document).ready(function(){

$.ajax('fetchDrinks.php', 
{
  success: function(data) {
  	$('#slider1').html(data);
  },
  async: false
}
);


    $(window).scroll(function(){
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
		return;
        var posFromTop = $(window).scrollTop();

        if(posFromTop+1 > $('#Mixed').offset().top){
		if (!$('#NavTool').is(":visible"))
			$('#NavTool').fadeIn();
        } else {
		if ($('#NavTool').is(":visible"))
			$('#NavTool').fadeOut();
        }
    });

  $('.slider1').bxSlider({
    slideWidth: 1000,
    minSlides: 1,
    maxSlides: 3,
    slideMargin: 10,
	infiniteLoop: false
  });

	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
	{
		$('.bx-prev').hide();
		$('.bx-next').hide();
		$('.mobilenav').show();
	}

  
	$(".mixdesc").mouseenter(function(){
		$(this).stop(true, true);
		$("#MixedDesc").stop(true, true);
		if ($(".image.selected").hasClass("selected")) return;
		$(this).fadeTo("slow", 1);
		$("#MixedDesc").text($(this).attr('name'));
		$("#MixedDesc").fadeIn("slow");
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) )
			$(this).trigger("click");
	});
	$(".mixdesc").mouseleave(function() {
		$(this).stop(true, true);
		$("#MixedDesc").stop(true, true);
		if ($(".image.selected").hasClass("selected")) return;
		$(this).fadeTo("slow", 0.5);
            		$("#MixedDesc").fadeOut(300);
	});
	$(".mixdesc").click(function() {
		$(this).stop(true, true);
		if ($(this).hasClass("selected"))
		{
			$(this).removeClass("selected");
			$("#OrderMixed").fadeOut("slow");
		}
		else
		{
			if ($(".image.selected").hasClass("selected"))
			{
				$(".image.selected").fadeTo("slow", 0.5);
				$(".image.selected").removeClass("selected");
			}
			$(this).fadeTo("slow", 1);
			$(this).addClass("selected");
			$("#MixedDesc").text($(this).attr('name'));
			$("#OrderMixed").fadeIn("slow");
		}
	});

});

</script>
<script>
function submitMixed()
{
	var mixedName = $(".image.selected");
	$("body").fadeOut(1000);
	location.href = "newOrder.php?drinkName=" + mixedName.attr('name');
}
</script>

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
								<div class="hidden" id="MixedDesc">Some test Text</div>
								<section class="hidden" id="OrderMixed">
									<footer>
										<a onclick="javascript:submitMixed()" class="button">Submit Order</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="slider1" id="slider1">
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u" style="z-index: 1;"><a class="image full mixdesc" name="Screwdriver"><img src="images/Drinks/adiosmotherfucker.jpg" alt="yh" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Rum and Coke"><img src="images/Drinks/jollyrancher.jpg" alt="g" /></a></div>
										</div>						
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Adios Motherfucker"><img src="images/Drinks/bahamamama.jpg" alt="trg" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Sex on the Beach"><img src="images/Drinks/creamsicle.jpg" alt="s" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Jolly Rancher"><img src="images/Drinks/rumandcoke.jpg" alt="tgbr" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Salty Dog"><img src="images/Drinks/saltydog.jpg" alt="rfgvdsv" /></a></div>
										</div>
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink585.html" class="image full"><img src="images/Drinks/screwdriver.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink5669.html" class="image full"><img src="images/Drinks/sexonthebeach.jpg" alt="" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink735.html" class="image full"><img src="images/Drinks/whiterussian.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink583.html" class="image full"><img src="images/Drinks/zombie.jpg" alt="" /></a></div>
										</div>
									</div> 
								</div>
							</div>
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
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
									<footer>
										<a href="#second" class="button scrolly">Submit Order</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="slider1" id="slider1">
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u" style="z-index: 1;"><a class="image full mixdesc" name="Screwdriver"><img src="images/Drinks/shotdefault.jpg" alt="yh" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Rum and Coke"><img src="images/Drinks/shotdefault.jpg" alt="g" /></a></div>
										</div>						
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Adios Motherfucker"><img src="images/Drinks/shotdefault.jpg" alt="trg" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Sex on the Beach"><img src="images/Drinks/shotdefault.jpg" alt="s" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Jolly Rancher"><img src="images/Drinks/shotdefault.jpg" alt="tgbr" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Salty Dog"><img src="images/Drinks/shotdefault.jpg" alt="rfgvdsv" /></a></div>
										</div>
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink585.html" class="image full"><img src="images/Drinks/shotdefault.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink5669.html" class="image full"><img src="images/Drinks/shotdefault.jpg" alt="" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink735.html" class="image full"><img src="images/Drinks/shotdefault.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink583.html" class="image full"><img src="images/Drinks/shotdefault.jpg" alt="" /></a></div>
										</div>
									</div> 
								</div>
							</div>
							<div class="center">
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</section>

		<!-- Basic Elements -->
			<section id="Non_Alcoholic" class="main">
				<header>
					<div class="container">
						<h2>Mocktails</h2>
					</div>
				</header>
				<div class="content dark style2">
					<div class="container">
						<div class="row">
							<div class="4u">
								<section>
									<h3>For the light of stomach</h3>
									<footer>
										<a href="#second" class="button scrolly">Submit Order</a>
									</footer>
								</section>
							</div>
							<div class="8u">
								<div class="slider1" id="slider1">
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u" style="z-index: 1;"><a class="image full mixdesc" name="Screwdriver"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="yh" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Rum and Coke"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="g" /></a></div>
										</div>						
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Adios Motherfucker"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="trg" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Sex on the Beach"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="s" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a class="image full mixdesc" name="Jolly Rancher"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="tgbr" /></a></div>
											<div class="6u"><a class="image full mixdesc" name="Salty Dog"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="rfgvdsv" /></a></div>
										</div>
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink585.html" class="image full"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink5669.html" class="image full"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="" /></a></div>
										</div>
									</div>
									<div class="8u">
										<div class="row no-collapse">
											<div class="6u"><a href="http://www.drinksmixer.com/drink735.html" class="image full"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="" /></a></div>
											<div class="6u"><a href="http://www.drinksmixer.com/drink583.html" class="image full"><img src="images/Drinks/nonalcoholicdefault.jpg" alt="" /></a></div>
										</div>
									</div> 
								</div>
							</div>
							<div class="center">
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
								</ul>
							</div>
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
					<div class="container">
						<!--<div class="wrapper-demo">
							<div id="dd" class="wrapper-dropdown-3" tabindex="1">
								<span>Transport</span>
								<ul class="dropdown">
									<li><a href="#">Classic Mail</a></li>
									<li><a href="#">UPS Delivery</a></li>
								</ul>
							</div>
						</div>-->
					</div>
					<div class="container">
					<?php
						$db = new PDO('sqlite:FB.db');
						$getNames = $db->query("SELECT * FROM single ORDER BY name ASC");
						$queryResult = $getNames->fetchAll();

						for ($count=0; $count < 10; $count++) {
						echo 'Ingredient: ';
						echo '<div class="dropdown">';
						echo '<select name="text', $count, '" class="dropdown-select"><option value=0>Empty</option>';
						foreach ($queryResult as $row) {
							echo '<option value="', $row['name'], '">', $row['name'], '</option>';
						}
						echo '</select>';
						echo '</div>';
						echo 'Parts <input type="number" style="width: 100px; height: 30px; font-size: 12pt"  value=0 name="vol', $count, '"><br>';
						}
					?>
					</div>
			</section>

			<!--<script type="text/javascript">
		
				function DropDown(el) {
					this.dd = el;
					this.placeholder = this.dd.children('span');
					this.opts = this.dd.find('ul.dropdown > li');
					this.val = '';
					this.index = -1;
					this.initEvents();
				}
				DropDown.prototype = {
					initEvents : function() {
						var obj = this;
	
						obj.dd.on('click', function(event){
							$(this).toggleClass('active');
							return false;
						});

						obj.opts.on('click', function() {
							var opt = $(this);
							obj.val = opt.text();
							obj.index = opt.index();
							obj.placeholder.text(obj.val);
						});
					},
					getValue : function() {
						return this.val;
					},
					getIndex : function() {
						return this.index;
					}
				}

				$(function() {
					var dd = new DropDown( $('#dd') );
					$(document).click(function() {
						$('.wrapper-dropdown-3').removeClass('active');
					});
				});
			</script>-->

			
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
							<div class="center">
								<ul class="mobilenav" style="padding-left:50%" id="mobilenav">
									<li><a href="#Mixed" class="scrolly fa solo"><img src="images/Categories/mixeddrink.png" alt="Mixed Image"><span>Twitter</span></a></li>
									<li><a href="#Shots" class="scrolly fa solo"><img src="images/Categories/shot.png" alt="Shot Image"><span>Facebook</span></a></li>
									<li><a href="#Non_Alcoholic" class="scrolly fa solo"><img src="images/Categories/nonalcoholic.png" alt="Non-Alcoholic Image"><span>Google+</span></a></li>
									<li><a href="#Custom" class="scrolly fa solo"><img src="images/Categories/customdrink.png" alt="Custom Image"><span>Dribbble</span></a></li>
								</ul>
							</div>
					</div>
				</div>
			</section>
			
		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li><a href="http://twitter.come/justsixguys/" class="fa fa-twitter solo"><span>Twitter</span></a></li>
					<li><a href="https://www.facebook.com/foobartender/" class="fa fa-facebook solo"><span>Facebook</span></a></li>
					<li><a href="http://html5up.net/" class="fa fa-dribbble solo"><span>Dribbble</span></a></li>
					<li><a href="http://html5up.net/" class="fa fa-github solo"><span>GitHub</span></a></li>
					<li><a href="OldSite/AdminPortal.php" class="fa fa-gear solo"><span>Admin Portal</span></a></li>
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
