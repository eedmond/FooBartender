<?php

	function MobileOnlyIncludes()
	{
		include_once(dirname(__FILE__).'/Mobile-Detect/Mobile_Detect.php');
		
		$mobileDetect = new Mobile_Detect();
		
		if ($mobileDetect->isMobile())
		{
			echo '<script src="jQuery-Impromptu/src/jquery-impromptu.js"></script>';
			echo '<link href="jQuery-Impromptu/src/jquery-impromptu.css" rel="stylesheet" />';
		}
	}
?>