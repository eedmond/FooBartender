<?php

include "master/master_head.php";

if (isset($_GET['opened'])) {


	echo '<h3>Thank you for ordering!</h3><center>';

	$text = exec("functions/order \"".$_GET['drink']."\"", $alltext);
	echo '<p>', $text, '</p>';
	echo '<p>', implode($alltext), '</p>';
	echo '<p>', $_GET['drink'],'</p>';
	echo '<a href="index.php">Go Back</a>';

}else {
	
	$drink = $_POST['drink'];
?>
	<h3>Thank you for ordering!</h3>
	<center>
	<p>Please wait while your order is being processed.</p>
	<p>Your <?php echo $drink; ?> will be ready soon. Our little elves are on the job.</p>

	<script language='javascript'>
		location.href="<?php echo $_SERVER['SCRIPT_NAME'], '?', $_SERVER['QUERY_STRING'], "&opened=0&drink=", $drink; ?>";
	</script>
	 
<?php
}

include "master/master_foot.php";

?>
