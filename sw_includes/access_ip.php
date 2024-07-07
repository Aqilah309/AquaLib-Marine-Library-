<?php
	
	function exitCode()
	{
		echo "<script>alert('Your IP is ".$_SERVER["REMOTE_ADDR"].". are blocked from accessing this page.');</script>";
		echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\" />";
		exit;
	}
	
	if ($ip_restriction_enabled)
	{
		$thruFlag = 'no';
		
		$queryT = "select ipaddress from eg_auth_ip";
		$resultT = mysqli_query($GLOBALS["conn"],$queryT);
			
		while ($myrow = mysqli_fetch_array($resultT))
		{
			$addressip = $myrow["ipaddress"];
				$segmenip = explode(".",$addressip);
		
			$userSegmenip = explode(".",$_SERVER["REMOTE_ADDR"]);
		
			if (count($segmenip) == 4)
			{
				if ((isset($userSegmenip[0]) && ($userSegmenip[0] == $segmenip[0])) && (isset($userSegmenip[1]) && ($userSegmenip[1] == $segmenip[1])) &&  (isset($userSegmenip[2]) && ($userSegmenip[2] == $segmenip[2])))
				{
					if (isset($userSegmenip[3]) && ($userSegmenip[3] == $segmenip[3]))
					{
						$thruFlag = 'yes';
						break;
					}
					else
					{
						$thruFlag = 'no';
						break;
					}						
				}
			}
		
			else if (count($segmenip) == 3)
			{
				if ((isset($userSegmenip[0]) && ($userSegmenip[0] == $segmenip[0])) && (isset($userSegmenip[1]) && ($userSegmenip[1] == $segmenip[1])))
				{
					if (isset($userSegmenip[2]) && ($userSegmenip[2] == $segmenip[2]))
					{
						$thruFlag = 'yes';
						break;
					}
					else
					{
						$thruFlag = 'no';
						break;
					}
				}
			}
		
			else if (count($segmenip) == 2)
			{
				if (isset($userSegmenip[0]) && ($userSegmenip[0] == $segmenip[0]))
				{
					if (isset($userSegmenip[1]) && ($userSegmenip[1] == $segmenip[1]))
					{
						$thruFlag = 'yes';
						break;
					}
					else
					{
						$thruFlag = 'no';
						break;
					}
				}
			}
		
			else if (count($segmenip) == 1)
			{
				if (isset($userSegmenip[0]) && ($userSegmenip[0] == $segmenip[0]))
				{
					$thruFlag = 'yes';
					break;
				}
				else
				{
					$thruFlag = 'no';
					break;
				}
			}		
		}//while
		
		if ($_SERVER["REMOTE_ADDR"] == '::1' || $_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
			$thruFlag = 'yes';//hardcoded to 127.0.0.1 ipv6
		}
		
		if ($thruFlag == 'no') {
			exitCode();
		}
	}
	else {
		$thruFlag = 'yes';
	}
?>