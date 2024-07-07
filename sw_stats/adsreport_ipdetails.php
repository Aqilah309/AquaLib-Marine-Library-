<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "IP Search Log Report";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<br/>

	<div style="text-align:center">

	<?php
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {$currentPage = $_GET['page'];}
		else {$currentPage = 1;}
		include '../sw_includes/paging-p1.php';
			
		echo "<table class=whiteHeaderNoCenter style='width:480'>";
			echo "<tr class=yellowHeaderCenter><td colspan=4><strong>IP search log sort by : </strong>";		
				echo " <select name=\"sortby\" ONCHANGE=\"location = this.options[this.selectedIndex].value;\">";
					echo "<option value=\"adsreport_ipdetails.php?page=1&sortby=ip\"";
					if (isset($_GET["sortby"]) && $_GET["sortby"] == 'ip') 
						{
							echo " selected";
							$sortby = '38ipaddr';
							$sortbyf = 'ip';
						}
					echo ">IP</option>";
					echo "<option value=\"adsreport_ipdetails.php?page=1&sortby=hits\"";
					if (!isset($_GET["sortby"]) || $_GET["sortby"] == 'hits')
						{
							echo " selected";
							$sortby = 'total1c';
							$sortbyf = 'hits';
						}
					echo " >Hits</option>";                                                                
				echo "</select>";    
			echo "</td></tr>";
		
			$query_iplog = "select count(distinct id, substring_index(38ipaddr,'.',3)) as total1c, substring_index(38ipaddr,'.',3) as 38ipaddr from eg_userlog_det group by substring_index(38ipaddr,'.',3) order by $sortby desc LIMIT $offset, $rowsPerPage";
			$result_iplog = mysqli_query($GLOBALS["conn"],$query_iplog);
													
			$query_count = "select count(distinct substring_index(38ipaddr,'.',3)) as total from eg_userlog_det";		
			$result_count = mysqli_query($GLOBALS["conn"],$query_count);
			
			$paging_type = 2;
			include '../sw_includes/paging-p2.php';

			echo "<tr class=whiteHeaderNoCenter><td width=50><strong></strong></td><td width=150><strong>IP Address</strong></td><td width=80><strong>Frequency</strong></td><td><strong>Last logged</strong></td></tr>";
														
			$n = $offset + 1;		
			while ($myrow_iplog = mysqli_fetch_array($result_iplog))
			{			
				$ipaddr = $myrow_iplog["38ipaddr"];			
				$total = $myrow_iplog["total1c"];
						
				$query_lastlog = "select 38logdate as last1d from eg_userlog_det where substring_index(38ipaddr,'.',3)='$ipaddr' ORDER BY id DESC LIMIT 1 ";
				$result_lastlog = mysqli_query($GLOBALS["conn"],$query_lastlog);
				$myrow_lastlog = mysqli_fetch_array($result_lastlog);
						
				echo "<tr class=yellowHover><td><strong>$n</strong></td><td>$ipaddr.*</td><td>$total</td><td>".$myrow_lastlog["last1d"]."</td></tr>";
																												
				$n = $n +1 ;
			}
		echo "</table>";
		 
		$appendpaging = "&sortby=$sortbyf";
		include '../sw_includes/paging-p3.php'; 
											
	?>
		
		<br/>
		<br/><a class='sButton' href='adsreport.php?toggle=3'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>	

	</div>	
	
	<hr>
			
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>