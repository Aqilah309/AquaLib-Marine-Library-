<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';
	$thisPageTitle = "Type Access Details";

	$typeid = $_GET["type"];	
	$typetext = strip_tags(mysqli_real_escape_string($GLOBALS["conn"],$_GET["typetext"]));

	if (!is_numeric($typeid)) 
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
		<?php
				//input years listing
				echo "<table class=yellowHeader300 style='width:450px;'><tr class=yellowHeaderCenter><td>";
					$query_year = "select distinct SUBSTRING(39logdate,11,4) AS inputyear from eg_item_access order by inputyear";
					$result_year = mysqli_query($GLOBALS["conn"],$query_year);
					echo "<strong>Select year</strong> : ";
								
					echo "<select name=\"inputyear\" ONCHANGE=\"location = this.options[this.selectedIndex].value;\">";    
					echo "<option value='adsreport_typeaccess.php?inputyear=".date('Y')."&type=$typeid&typetext=$typetext'>-Current Year-</option>";                
					while ($myrow_year = mysqli_fetch_array($result_year))
					{
						$inputyear = $myrow_year["inputyear"];
										
						echo "<option value=\"adsreport_typeaccess.php?inputyear=$inputyear&type=$typeid&typetext=$typetext\"";
						if ((isset($_GET["inputyear"]) && $_GET["inputyear"] == $inputyear) || (!isset($_GET["inputyear"]) && $inputyear == date('Y')))
							{echo " selected";}
						echo ">$inputyear</option>";
					}                                                                 
					echo "</select>";					
				echo "</td></tr></table>"; 

				echo "<table class=yellowHeader300 style='width:450px;'>";
					echo "<tr class=yellowHeaderCenter><td><strong>$typetext access statistics</strong> : </td></tr>";
				echo "</table>";	
														
				echo "<table class=whiteHeader300 style='width:450px;'>";										
					echo "<tr><td width=80>Month</td><td width=80>Session</td><td width=80>Access</td>";
						if ($system_function != "photo")
							{echo "<td width=80>Download (Web)</td><td width=80>Download (App)</td>";}
					echo "</tr>";
					
					$total_t_access = 0;
					$total_t_session = 0;
					$total_d_web = 0;
					$total_d_app = 0;
					for ( $counter = 1; $counter <= 12; $counter += 1) 								
					{
							if (isset($_GET["inputyear"]) && is_numeric($_GET["inputyear"]))
								{$inputyear = $_GET["inputyear"];}
							else
								{$inputyear = date('Y');}
							
							if	($counter <= 9) {$counterc = "0$counter";}
							else {$counterc = "$counter";}

							$query_session = "select eg_item_access.39ipaddr as aip, SUBSTRING_INDEX(eg_item_access.39logdate,' ', 2) as adate, count(eg_item_access.id) as totalid 
							from eg_item_access,eg_item 
							where eg_item.38typeid='$typeid' and eg_item_access.eg_item_id=eg_item.id and eg_item_access.39logdate like '%$counterc/$inputyear%' 
							group by aip, adate";
							$result_session = mysqli_query($GLOBALS["conn"],$query_session);
							$rowcount_affected_session = mysqli_num_rows($result_session);

							$query_access = "select count(eg_item_access.id) as totalid from eg_item_access,eg_item where eg_item.38typeid='$typeid' and eg_item_access.eg_item_id=eg_item.id and eg_item_access.39logdate like '%$counterc/$inputyear%'";
							$result_access = mysqli_query($GLOBALS["conn"],$query_access);
							$myrow_access = mysqli_fetch_array($result_access);
							$num_results_affected_access = $myrow_access["totalid"];

							$queryf_web = "select count(eg_item_download.id) as totalid from eg_item_download,eg_item 
							where eg_item.38typeid='$typeid' and eg_item_download.eg_item_id=eg_item.id and eg_item_download.39logdate like '%/$counterc/$inputyear%' and eg_item_download.39from like 'web'";
							$resultf_web = mysqli_query($GLOBALS["conn"],$queryf_web);
							$myrowf_web = mysqli_fetch_array($resultf_web);
							$num_results_affected_web = $myrowf_web["totalid"];
					
							$queryf_app = "select count(eg_item_download.id) as totalid from eg_item_download,eg_item 
							where eg_item.38typeid='$typeid' and eg_item_download.eg_item_id=eg_item.id and eg_item_download.39logdate like '%/$counterc/$inputyear%' and eg_item_download.39from like 'app'";
							$resultf_app = mysqli_query($GLOBALS["conn"],$queryf_app);
							$myrowf_app = mysqli_fetch_array($resultf_app);
							$num_results_affected_app = $myrowf_app["totalid"];
							
							echo "<tr class=yellowHover>";																									
								echo "<td>$counter/$inputyear</td>";		
								echo "<td><a href='adsreport_typesession_more.php?type=$typeid&acstat=/$counterc/$inputyear'>$rowcount_affected_session</a></td>";						
								echo "<td><a href='adsreport_typeaccess_more.php?type=$typeid&acstat=/$counterc/$inputyear'>$num_results_affected_access</a></td>";
								if ($system_function != "photo")
								{
									echo "<td><a href='adsreport_typedownload_more.php?type=$typeid&acstat=/$counterc/$inputyear&from=web'>$num_results_affected_web</a></td>";
									echo "<td><a href='adsreport_typedownload_more.php?type=$typeid&acstat=/$counterc/$inputyear&from=app'>$num_results_affected_app</a></td>";
								}
							echo "</tr>";
							$total_t_access = $total_t_access + $num_results_affected_access;
							$total_t_session = $total_t_session + $rowcount_affected_session;
							$total_d_web = $total_d_web + $num_results_affected_web;
							$total_d_app = $total_d_app + $num_results_affected_app;
					}		
					echo "<tr>
						<td style='color:green;'>TOTAL</td>
						<td style='color:green;'>$total_t_session</td>
						<td style='color:green;'>$total_t_access</td>";
						if ($system_function != "photo") {
						echo "
						<td style='color:green;'>$total_d_web</td>
						<td style='color:green;'>$total_d_app</td>";
						}
					echo "</tr>";
				echo "</table>";
		?>

		<br/><br/>
		
		<a class='sButton' href='adsreport.php?toggle=2'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>		
	</div>
	
	<hr>
		
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>