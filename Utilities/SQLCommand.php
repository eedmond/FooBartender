<?php
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	
	try
	{
	require_once(dirname(__FILE__) . '/Database.php');

	$database = new Database();
	$query = $_POST['command'];
	$database->ExecuteSql($query);
?>
<script language="javascript">
	history.go(-1);
</script>
<?php
	}
	catch(Exception $ex)
	{
?>
	<p>There was an error executing the sql command: <?php echo $_POST['command'] ?> </p>
	<p><?php echo $ex->getMessage(); ?></p>
	<a onclick="javascript:history.go(-1)">Go back</a>
<?php
	}
?>

