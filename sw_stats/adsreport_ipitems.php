<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle  = "Item Per IP Access Log";

	$id = $_GET['det'];
	if (!is_numeric($id)) 
	{
		echo "<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>INVALID ACCCESS</strong></span><h2>Invalid parameter detected.</h2><em>sWADAH Response Code</em></div>";
		mysqli_close($GLOBALS["conn"]); exit();		
	}
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<br/>

	<div style="text-align:center">
		<?php
				
				if (isset($_GET['page']) && is_numeric($_GET['page'])) {$currentPage = $_GET['page'];}
				else {$currentPage = 1;}
				include '../sw_includes/paging-p1.php';
								
				$query_ipitem = "select SQL_CALC_FOUND_ROWS 39ipaddr, 39logdate from eg_item_access where eg_item_id=$id order by id desc LIMIT $offset, $rowsPerPage";
				$result_ipitem = mysqli_query($GLOBALS["conn"],$query_ipitem);
				
				include '../sw_includes/paging-p2.php';																
		
				echo "<table class=whiteHeader>";
					echo "<tr class=yellowHeaderCenter><td colspan=3><strong>Item per IP Access Log </strong>";
					echo " for item ID: <span style='color:red;'>$id</span>";
					echo "<br/>Recorded accessed: <span style='color:red;'>$num_results_affected_paging</span>";
					echo "</td></tr>";
					echo "<tr class=whiteHeaderCenter><td width=30><strong></strong></td><td width=150><strong>IP Address</strong></td><td><strong>Accessed on</strong></td></tr>";
																	
					$n = $offset + 1;											
					while ($myrow_ipitem = mysqli_fetch_array($result_ipitem))
					{
						echo "<tr class='yellowHover'><td><strong>$n</strong></td><td>".$myrow_ipitem["39ipaddr"]."</td><td>".$myrow_ipitem["39logdate"]."</td></tr>";
						$n = $n +1 ;
					}
				echo "</table>";
				
				$appendpaging = "&det=".$_GET["det"];
				include '../sw_includes/paging-p3.php'; 						
		?>
	</div>	

	<br/>
	<div style="text-align:center"><a class='sButtonRed' href='javascript:window.close();'><span class='fas fa-window-close'></span> Close</a></div>
	<br/>

	<hr>
	
	<?php 
		include '../sw_includes/footer.php';
	?>
		
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>