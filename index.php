

<?php

$page = $_SERVER['PHP_SELF'];
$sec = "15";
header("Refresh: $sec; url=$page");

$title = "Welcome to the FooBartender!";

include "master/master_head.php";
?>
<p id="demo"></p>

			<h2>Tonight's Drink Options Are:</h2>

            <center>
				<h3>
					<form id="mainDropDown" action="order.php" method="post">
					<select id="drink" name="drink" onchange="showCustom();">
						<option value=0>Please Select Your Drink</option>
						<?php include "pullFromMixed.php" ?>
					</select>
					<br/>
					<input type="submit" text="Order" />
					</form>
				</h3>

           </center>

<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
function turnOff()
{
$("#demo").html(Date());
      $.get("shutdown.php");
        return true;
}

function showCustom()
{
	var text = document.getElementById("drink").value;
	if (text == "custom") {	
             alert("Are you sure you want a custom drink?");
		location.href = "CustomDrink.php";
	}
}
</script>
<?php include "master/master_foot.php"; ?>
