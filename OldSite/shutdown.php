<?php
	echo exec("whoami");
	echo "Shutting down...";
	echo exec("sudo ./shutdown", $empty, $rtn);
	//echo exec("sudo shutdown -h now", $empty, $rtn);
	//echo exec("./order", $empty, $rtn);
	/*echo "Return value is: ". $rtn . " and output is:\n";
	foreach($empty as $val)
	{
		echo($val . "\n");
	}*/
	
 ?>
