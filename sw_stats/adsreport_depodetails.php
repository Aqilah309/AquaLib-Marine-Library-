<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Deposit Activity Report";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>				

	<br/>

	<div style="text-align:center">
	
		<?php			
			
			$hitsDate = $_GET['hitsDate'];			

			//get a list of result with the wildcard and number of result affected at the same time
			$param = "%".$hitsDate."%";
			$stmt_fsb = $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_item_depo where DATE_FORMAT(FROM_UNIXTIME(timestamp), '%d/%m/%Y') like ? order by timestamp desc");
			$stmt_fsb->bind_param("s", $param);//s string
			$stmt_fsb->execute();
			$result_fsb = $stmt_fsb->get_result();
			$num_results_affected = $result_fsb->num_rows;
			
			echo "<table class=whiteHeaderNoCenter>";
				echo "<tr class=yellowHeaderCenter><td colspan=5>Total deposit : <strong>$num_results_affected</strong> for <strong>$hitsDate</strong></td></tr>";
				echo "<tr class=whiteHeaderNoCenter style='text-decoration:underline;'><td></td><td><strong>Item</strong></td><td><strong>Depositor</strong></td><td><strong>Deposit Date</strong></td><td><strong>Current Status</strong></td></tr>";
																
				$n = 1;
				
				while($myrow_fsb = $result_fsb->fetch_assoc())
				{
					echo "<tr class=yellowHover>";
					
					$id_fsb = $myrow_fsb["id"];

					$query_title = "select 29titlestatement as title,inputby,timestamp,itemstatus from eg_item_depo where id = '$id_fsb'";
					$result_title = mysqli_query($GLOBALS["conn"],$query_title);
					$myrow_title = mysqli_fetch_array($result_title);
					
					$title_fsb = $myrow_title['title'];						
					$inputby_fsb = $myrow_title['inputby'];			
					$submitted_fsb = date('d/m/Y H:i:s',$myrow_fsb["timestamp"]);		
					$itemstatus_fsb = $myrow_title['itemstatus'];										
					
					echo "<td>$n</td><td style='text-align:left;'>$title_fsb</td><td>$inputby_fsb</td><td>$submitted_fsb</td><td>$itemstatus_fsb</td></tr>";
																														
					$n = $n +1 ;
				}
			echo "</table>";			
															
		?>
		
		<br/>
		<a class='sButton' href='adsreport.php?toggle=5'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>				
	
	</div>	
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>