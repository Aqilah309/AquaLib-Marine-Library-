<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Bookmarking Activity Report";
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
			$stmt_fsb = $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_item_charge where DATE_FORMAT(FROM_UNIXTIME(39charged_on), '%d/%m/%Y') like ? order by 39charged_on desc");
			$stmt_fsb->bind_param("s", $param);
			$stmt_fsb->execute();
			$result_fsb = $stmt_fsb->get_result();
			$num_results_affected = $result_fsb->num_rows;
	
			echo "<table class=whiteHeaderNoCenter>";
				echo "<tr class=yellowHeaderCenter><td colspan=4>Total recorded bookmarks : <strong>$num_results_affected</strong> for <strong>$hitsDate</strong></td></tr>";
				echo "<tr class=whiteHeaderNoCenter style='text-decoration:underline;'><td></td><td><strong>Item</strong></td><td><strong>Transaction Info</strong></td><td><strong>Transaction Date</strong></td></tr>";
																
				$n = 1;
				
				while($myrow_fsb = $result_fsb->fetch_assoc())
				{
					echo "<tr class=yellowHover>";
					
					$id_fsb = $myrow_fsb["id"];
					
					$accession_fsb = $myrow_fsb["38accessnum"];
						$query_title = "select 38title from eg_item where 38accessnum = '$accession_fsb'";
						$result_title = mysqli_query($GLOBALS["conn"],$query_title);
						$myrow_title = mysqli_fetch_array($result_title);
						if (isset($myrow_title['38title'])) {$title = $myrow_title['38title'];} else {$title = 'N/A';}
						
					$patron_fsb = $myrow_fsb["39patron"];
						$query_name = "select username, name from eg_auth where username = '$patron_fsb'";
						$result_name = mysqli_query($GLOBALS["conn"],$query_name);
						$myrow_name = mysqli_fetch_array($result_name);
						if (isset($myrow_name["name"])) {$fullname = $myrow_name["name"];} else {$fullname = 'N/A';}
						if (isset($myrow_name["username"])) {$username = $myrow_name["username"];} else {$username = 'N/A';}

					$chargedon_fsb = date('d/m/Y H:i:s',$myrow_fsb["39charged_on"]);											
					
					echo "<td>$n</td><td style='text-align:left;'>$title</td><td>$fullname ($username)</td><td>$chargedon_fsb</td></tr>";
																														
					$n = $n +1 ;
				}
			echo "</table>";			
															
		?>
		
		<br/>
		<a class='sButton' href='adsreport.php?toggle=4'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>				
	
	</div>	
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>