<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Type Download Details - More";

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
		<tr class=yellowHeaderCenter><td><strong>Download details for <?php echo $_GET['acstat'];?></strong> :
		</td></tr></table>
		
		<table class=whiteHeaderNoCenter>
			<tr class=whiteHeaderNoCenter><td style='width:50;'></td><td style='width:100;'>Doc ID</td><td>IP</td><td>Title</td></tr>
			<?php
				$n=1;
				$acstat = $_GET["acstat"];																											
				
				$param = "%".$acstat."%";
				$stmt_fsb = $new_conn->prepare("
				select eg_item_download.eg_item_id as eg_item_id, eg_item_download.39ipaddr as 39ipaddr 
					from eg_item_download,eg_item 
					where eg_item.38typeid=? 
					and eg_item.id=eg_item_download.eg_item_id 
					and eg_item_download.39logdate like ? 
					and eg_item_download.39from like ?
				");
				$stmt_fsb->bind_param("iss",$_GET["type"],$param,$_GET['from']);
				$stmt_fsb->execute();
				$result_fsb = $stmt_fsb->get_result();
				while($myrow = $result_fsb->fetch_assoc())
					{
						$id = $myrow["eg_item_id"];
						$ipaddr = $myrow["39ipaddr"];
						
						$query_title = "select 38title from eg_item where id='$id'";
						$result_title = mysqli_query($GLOBALS["conn"],$query_title);
						$myrow_title = mysqli_fetch_array($result_title);								
							$title = $myrow_title["38title"];
							echo "<tr class=yellowHover><td>$n</td><td>$id</td><td>$ipaddr</td><td>$title</td></tr>";
						
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