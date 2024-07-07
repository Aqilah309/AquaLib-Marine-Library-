<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	
	$get_id_det = mysqli_real_escape_string($GLOBALS["conn"],$_GET["det"]);

	if (is_numeric($get_id_det))
	{
		$query_ft = "select 41pdfattach_fulltext from eg_item where id='$get_id_det'";		
		$result_ft = mysqli_query($GLOBALS["conn"],$query_ft);
		$myrow_ft = mysqli_fetch_array($result_ft);

		echo $myrow_ft["41pdfattach_fulltext"];		
	}		
	else
	{
		echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>Invalid Request.</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div>";
		mysqli_close($GLOBALS["conn"]);exit;
	}
?>