<?php include "master/master_head.php"; ?>

<script type="text/javascript">
	drinkArray = new Array();

	function addToDrinkArray(id) {
		drinkArray.push(id);
	}
	
	function removeFromDrinkArray() {
		drinkArray.pop();
	}
</script>

<h2>Welcome to the FooBartender: Custom Drink Maker</h2>
<h3>How many drinks will be in your custom beverage?</h3>
<div id="CustomDrink">
<center>
	<input type="submit" value="Add a Drink" onClick="javascript:addDrink()">
	<br><br>
	<input type="submit" value="Clear Current Drink" onClick="javascript:clearDrink()">
	<br><br>
<script type="text/javascript">
function addDrink() {
	var newInput = '<div id="DrinkDropDown">Drink To Add: <?php include "singleDropDown.php"; ?></div>';
	document.getElementById('CustomDrink').innerHTML += newInput;
	addToDrinkArray(newInput);
}

function clearDrink() {
	location.reload();	
}
</script>
</center>
</div>
<center>
<input type="submit" value="Submit Custom Order">
<br><br>
<input type="submit" value="Cancel Custom Order" onClick="javascript:history.go(-1)">
</center>
<?php include "master/master_foot.php"; ?>
