<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Type Session Details - More";

	if (!is_numeric($_GET["type"])) 
	{
		echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>Invalid parameter detected.</h2><em>sWADAH Response Code</em></div>";
		mysqli_close($GLOBALS["conn"]); exit();		
	}
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>
	
	<div style="text-align:center">						
		<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td><strong>Access details for <?php echo $_GET['acstat'];?></strong> :
		</td></tr></table>
		
		<table class=whiteHeaderNoCenter>
			<tr class=whiteHeaderNoCenter><td style='width:50;'></td><td>IP Address / Session</td><td>Date</td><td>Total access</td></tr>
			<?php
				$n = 1;
				$acstat = $_GET["acstat"];		

				$param = "%".$acstat."%";
				$stmt_fsb = $new_conn->prepare("
				select eg_item_access.39ipaddr as aip, SUBSTRING_INDEX(eg_item_access.39logdate,' ', 2) as adate, count(eg_item_access.id) as totalid 
							from eg_item_access,eg_item 
							where eg_item.38typeid=? and eg_item_access.eg_item_id=eg_item.id and eg_item_access.39logdate like ? 
							group by aip, adate
				");
				$stmt_fsb->bind_param("is",$_GET["type"],$param);
				$stmt_fsb->execute();
				$result_fsb = $stmt_fsb->get_result();
				while($myrow_fsb = $result_fsb->fetch_assoc())				
					{
						$aip_fsb = $myrow_fsb["aip"];
						$adate_fsb = $myrow_fsb["adate"];
						$totalid_fsb = $myrow_fsb["totalid"];
					
						echo "<tr class=yellowHover><td>$n</td><td>$aip_fsb</td><td>$adate_fsb</td><td>$totalid_fsb</td></tr>";
						
						$n=$n+1;
					}		
			?>
		</table>

		<br/><br/>
		<a class='sButton' href='javascript:history.go(-1)'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a>	
	</div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>