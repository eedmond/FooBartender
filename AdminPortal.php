<?php include "Login.php"; ?>
<?php include "master/master_head.php"; ?>
<h5><a href="shutdown.php">Shut down</a></h5>
		<h3>Welcome to the FooBartender: Administrator Portal</h3>
		<h2>Please Initialize The System...</h2>
<?php
		if (isset($_GET['update']))
			echo '<p style="text-align: center;">System has successfully been updated.</p>';
?>		

		<form method="post" action="UpdateAdmin.php">
			<?php include "pullFromSingle.php" ?>

			<input type="submit" id="Submit" value="Submit" style="font-size:150%; margin-top:15px;" />
		</form>
		<br><br>
		<input type="button" onClick="javascript:clearQueue()" id="ClearQueue" value="*Clear Queue*" style="background-color:red; color:white;  margin-top;20px;" />
<?php include "DisplayQueue.php"; ?>
<input type="button" onClick="javascript:insertSingle();" value="Insert Into Single" />
<br>
<p id="CustomDrink">
<input type="button" onClick="javascript:addDrink();" value="Insert a New Drink" />
<br>
<input type="button" onClick="javascript:clearDrink();" value="Clear Current Drink" />
<br>
<?php include "CreateMixedDrink.php"; ?>
</p>
<script type="text/javascript">
drinkArray = new Array();
var counter = 0;

function clearAll() {
	drinkArray = new Array();
}

function clearQueue() {
	$.get("ClearQueue.php");
	alert("CLEARING THAT QUEUE YOU KNOW WHATSUP WHATSUP");
}

function insertSingle() {
	var name = prompt("Name of the drink: ");

	$.get("InsertSingle.php?drinkName=" + name);
	alert("Inserting " + name + " into singles database");
}

function addDrink() {
	document.getElementById("CustomDrink")[2].style.display = "block";
	alert("text");
}

function clearDrink() {
	counter = 0;
	clearAll();
	location.reload();
}

function submitMixed() {
	if (drinkArray.length < 1) {
		alert("Error: no drink has been made");
		return;
	}
	var name = prompt("Name of the drink: ");
	// get stuff from textboxes
	//$.get("InsertMixed.php?drinkName=" + name + "?numDrinks=" + drinkArray.length());
	alert("Added " + name + " to the mixed drink database");
}
</script>

<?php include "master/master_foot.php" ?>
