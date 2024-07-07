<?php

session_start();
include 'core.php';

if (isset($_GET['t']) && !ctype_alpha($_GET['t']))
{
	echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>Resource time out.</h2><em>sWADAH Response Code</em></div>";
	mysqli_close($GLOBALS["conn"]); exit;
}

if (!isset($_GET['id']))
{
	echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>Resource time out.</h2><em>sWADAH Response Code</em></div>";
	mysqli_close($GLOBALS["conn"]); exit;
}

if (ctype_alnum($_GET['id'])) {$id = $_GET['id'];}
else
{
	echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>Resource time out.</h2><em>sWADAH Response Code</em></div>";
	mysqli_close($GLOBALS["conn"]); exit(); 
}

// Set the maximum number of downloads (actually, the number of page loads)
$maxdownloads = $max_download_allowed;

// Set the key's viable duration in seconds (86400 seconds = 24 hours)
$maxtime = $max_time_link_availability;

// Get the key, timestamp, and number of downloads from the database
$query = sprintf("SELECT * FROM eg_downloadkey WHERE uniqueid= '%s'", mysqli_real_escape_string($GLOBALS["conn"],$id));		
$result = mysqli_query($GLOBALS["conn"],$query);
$row = mysqli_fetch_array($result);
if (!$row) 
{ 
	echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>LINK EXPIRED</strong></span><h2>Invalid download link.</h2><em>sWADAH Response Code</em></div>";
}
else
{
	//check the duration since last download link generated and when the user access selected doc
	$timecheck = date('U') - $row['timestamped'];
	
	if ($timecheck >= $maxtime) {
		echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>LINK EXPIRED</strong></span><h2>Exceeded time allotted.</h2><em>sWADAH Response Code</em></div><br />";
	}
	else
	{
		$downloads = $row['downloads'];
		$downloads += 1;
		
		if ($downloads > $maxdownloads) {
			echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>LINK EXPIRED</strong></span><h2>Exceeded allowed downloads.</h2><em>sWADAH Response Code</em></div><br />";
		}
		else
		{
		
			$sql = sprintf("UPDATE eg_downloadkey SET downloads = '".$downloads."' WHERE uniqueid= '%s'", mysqli_real_escape_string($GLOBALS["conn"],$id));
			$incrementdownloads = mysqli_query($GLOBALS["conn"],$sql);
				
			ob_start();

			//download : application/octet-stream, inline : application/pdf
			if ($_GET['t'] == 'p')
			{
				$mm_type="application/pdf";
				$file = $row['pdocs'];
				$filename = "guest.pdf"; 
				if (isset($_SESSION['fromapp']) &&  $_SESSION['fromapp'])
					{mysqli_query($GLOBALS["conn"],"insert into eg_item_download values(DEFAULT,".$row['eg_item_id'].",'".session_id()."','".date("D d/m/Y")."','".$_SERVER["REMOTE_ADDR"]."','app')");}
				else
					{mysqli_query($GLOBALS["conn"],"insert into eg_item_download values(DEFAULT,".$row['eg_item_id'].",'".session_id()."','".date("D d/m/Y")."','".$_SERVER["REMOTE_ADDR"]."','web')");}
			}
			else if ($_GET['t'] == 'd')
			{
				$mm_type="application/pdf";
				$file = $row['docs'];
				$filename = "full.pdf"; 
				if (isset($_SESSION['fromapp']) &&  $_SESSION['fromapp'])
					{mysqli_query($GLOBALS["conn"],"insert into eg_item_download values(DEFAULT,".$row['eg_item_id'].",'".session_id()."','".date("D d/m/Y")."','".$_SERVER["REMOTE_ADDR"]."','app')");}
				else
					{mysqli_query($GLOBALS["conn"],"insert into eg_item_download values(DEFAULT,".$row['eg_item_id'].",'".session_id()."','".date("D d/m/Y")."','".$_SERVER["REMOTE_ADDR"]."','web')");}
			}
			else if ($_GET['t'] == 'a')
			{
				$mm_type="image/jpeg";
				$file = $row['albums'];
				$filename = "image.jpg"; 
			}
			else
			{
				echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID DOWNLOAD</strong></span><h2>Action not permitted.</h2><em>sWADAH Response Code</em></div>";
				exit;
			}
			
			header("Cache-Control: public, must-revalidate");
			header("Pragma: no-cache");
			header("Content-Type: " . $mm_type);
			header("Content-Length: " .(string)(filesize($file)) );
			header('Content-Disposition: inline; filename="'.$filename.'"');//can be set to attachment or inline
			header("Content-Transfer-Encoding: binary\n"); 
			ob_end_clean();
			readfile($file);		
		}
	}
}
?>