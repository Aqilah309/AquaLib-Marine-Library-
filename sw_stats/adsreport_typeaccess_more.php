<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Type Access Details - More";

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
			<tr class=yellowHeaderCenter><td><strong>Access details for <?php echo $_GET['acstat'];?></strong> :</td></tr>
		</table>
		
		<table class=whiteHeaderNoCenter>
			<tr class=whiteHeaderNoCenter><td style='width:50;'></td><td style='width:100;'>Doc ID</td><td>IP</td><td>Title</td></tr>
			<?php
				$n = 1;
				$acstat = $_GET["acstat"];		

				$param = "%".$acstat."%";
				$stmt_fsb = $new_conn->prepare("select eg_item_access.eg_item_id as eg_item_id, eg_item_access.39ipaddr as 39ipaddr from eg_item_access,eg_item where eg_item.38typeid=? and eg_item.id=eg_item_access.eg_item_id and eg_item_access.39logdate like ?");
				$stmt_fsb->bind_param("is",$_GET["type"],$param);//s string
				$stmt_fsb->execute();
				$result_fsb = $stmt_fsb->get_result();

				while($myrow_fsb = $result_fsb->fetch_assoc())
					{
						$id_fsb = $myrow_fsb["eg_item_id"];
						$ipaddr_fsb = $myrow_fsb["39ipaddr"];
						
						$query_title = "select 38title from eg_item where id='$id_fsb'";
						$result_title = mysqli_query($GLOBALS["conn"],$query_title);
						$myrow_title = mysqli_fetch_array($result_title);								
							$title = $myrow_title["38title"];

						echo "<tr class=yellowHover><td>$n</td><td>$id_fsb</td><td>$ipaddr_fsb</td><td>$title</td></tr>";
						
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