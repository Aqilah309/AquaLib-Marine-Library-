<?php
	if ($restriction_for_adminPage)
	{
		function exitCode()
		{
			echo "<script>alert('Your IP is ".$_SERVER["REMOTE_ADDR"].". are blocked from accessing this page.');</script>";
			echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div>";
			exit;
		}
		
		$ip = getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');

		$canproceed = false;

		foreach($allowed_ip as $value){
			if (preg_match("/$value/i", $ip) === 1) {
				$canproceed = true;
				break;
			}
		}

		if (!$canproceed) {
			exitCode();
		}
	}
?>