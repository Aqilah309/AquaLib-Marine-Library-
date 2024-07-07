<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Search Hits Report";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>				
	
	<br/>
	
	<div style="text-align:center">
	
		<?php
		
			$hitsDate = $_GET['hitsDate'];			
			
			$param = "%".$hitsDate."%";
			$stmt_ip = $new_conn->prepare("select count(distinct id, substring_index(38ipaddr,'.',3)) as total_ip, substring_index(38ipaddr,'.',3) as 38ipaddr from eg_userlog_det where 38logdate like ? group by substring_index(38ipaddr,'.',3) order by total_ip desc");
			$stmt_ip->bind_param("s", $param);//s string
			$stmt_ip->execute();
			$result_ip = $stmt_ip->get_result();
			
			$tdIP = 1;
			
			echo "<table class=yellowHeader><tr>";
				echo "<td colspan=6>Search hits statistics per IP for <strong>$hitsDate</strong></td></tr>";
				echo "<tr style='background-color:#FFEE96'>";
				while($myrow_IP = $result_ip->fetch_assoc())
				{
					$ipaddr = $myrow_IP["38ipaddr"];
					$total_ip = $myrow_IP["total_ip"];
					
					echo "<td><div style='text-align:center;font-size:12px;'>$ipaddr.* : $total_ip hits</div></td>";
					
					if ($tdIP % 6 == 0)
						{echo "</tr><tr style='background-color:#FFEE96'>";}
					$tdIP++;				
				}
			echo "</tr></table>";							

			$param = "%".$hitsDate."%";
			$stmt_fsb = $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_userlog_det where 38logdate like ? order by id");
			$stmt_fsb->bind_param("s", $param);//s string
			$stmt_fsb->execute();
			$result_fsb = $stmt_fsb->get_result();
			$num_results_affected = $result_fsb->num_rows;
			
			echo "<br/>";
			echo "<table class=whiteHeaderNoCenter>";
				echo "<tr class=yellowHeaderCenter><td colspan=4>Total recorded search hits : <strong>$num_results_affected</strong> for <strong>$hitsDate</strong></td></tr>";
				echo "<tr class=whiteHeaderNoCenterUnderline><td></td><td><strong>Terms</strong></td><td><strong>Lodged detail</strong></td><td><strong>IP Address</strong></td></tr>";
																
				$n = 1;				

				while($myrow_fsb = $result_fsb->fetch_assoc())
				{
					
					$id_fsb = $myrow_fsb["id"];
					$keyword_fsb = $myrow_fsb["38keyword"];
					$datelog_fsb = $myrow_fsb["38logdate"];							
					$ipaddr_fsb = $myrow_fsb["38ipaddr"];
					
					echo "<tr class=yellowHover><td>$n</td><td>$keyword_fsb</td><td>$datelog_fsb</td><td>$ipaddr_fsb</td></tr>";
																														
					$n = $n +1 ;
				}
			echo "</table>";
										
		?>
		
		<br/>
		<a class='sButton' href='adsreport.php?toggle=3'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>			
	
	</div>	
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>