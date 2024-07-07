<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	ini_set('max_execution_time',180);
	include '../sw_includes/functions.php';
	$thisPageTitle = "Report Details";
	$_SESSION['whichbrowser'] = "rep";
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
					
	<hr>

	<div style="text-align:center">
		<?php
								
				if ($_SESSION['editmode'] == 'SUPER' && $_GET['inf'] != '')
					{
						$get_inf = $_GET["inf"]; 
						$get_infname = $_GET["infname"];						
						$getappend = "&inf=$get_inf&infname=$get_infname";
					}
				else
					{$get_inf = $_SESSION["username"];}
				
				if (isset($_GET['inputyear'])) {$get_inputyear = $_GET["inputyear"];}
																															
				//list input years -start
				echo "<table class=yellowHeader><tr><td>";
					$query_inputyear = "select distinct SUBSTRING(39inputdate,1,4) AS inputyear from eg_item order by inputyear";
					$result_inputyear = mysqli_query($GLOBALS["conn"],$query_inputyear);
					echo "<strong>Select year</strong> :";
								
					echo " <select name=\"inputyear\" ONCHANGE=\"location = this.options[this.selectedIndex].value;\">";                    
					while ($myrow_inputyear = mysqli_fetch_array($result_inputyear))
					{
						$inputyear = $myrow_inputyear["inputyear"];														
						echo "<option value=\"adsreport_details.php?";
						echo "inputyear=$inputyear$getappend\"";
						if (isset($_GET["inputyear"]) && $_GET["inputyear"] == $inputyear) 
							{echo " selected";}
						echo ">$inputyear</option>";
					} 
					echo "<option value=\"adsreport_details.php?inputyear=All$getappend\"";
					if ((isset($_GET["inputyear"]) && $_GET["inputyear"] == 'All') || !isset($_GET["inputyear"]))
						{echo " selected";}
					echo " >All</option>";																						
					echo "</select>";
				echo "</td></tr></table>"; 
				//list input years -end			
										
				if (isset($_GET['inputyear']) && $_GET["inputyear"] <> 'All')
				{
					$param = $get_inputyear."%";
					$stmt_inputby= $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_item where 39inputby=? and 39inputdate like ? order by id desc");
					$stmt_inputby->bind_param("ss",$get_inf,$param);
				}
				else
				{
					$stmt_inputby= $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_item where 39inputby=? order by id desc");
					$stmt_inputby->bind_param("s",$get_inf);
				}

				$stmt_inputby->execute();
				$result_inputby = $stmt_inputby->get_result();
				$num_results_affected = $result_inputby->num_rows;
	
				echo "<table class=yellowHeader><tr><td>";
					echo "<strong>User Statistics : </strong>$get_infname ($get_inf)";
					echo "<strong><br/>Total input ";
						if (isset($_GET["inputyear"]) && $_GET["inputyear"] <> 'All'){ echo "for year ".$_GET["inputyear"];}
					echo " :</strong> $num_results_affected";
				echo "</td></tr></table>";

				//months calculation
				if (isset($_GET["inputyear"]) && $_GET["inputyear"] != 'All')
				{
					$month_array = array();
					$pages_array = array();
					
					for ($counter=1;$counter<=12;$counter+=1)
					{
						if ($counter <= 9) {$counter_text = '0'.$counter;}
						else {$counter_text = $counter;}

						$get_monthcount = $get_inputyear.'-'.$counter_text;
						
						$param = $get_monthcount."%";
						$stmt_ministat = $new_conn->prepare("select count(*) as total3 from eg_item where 39inputby=? and 39inputdate like ?");
						$stmt_ministat->bind_param("ss",$get_inf,$param);
						$stmt_ministat->execute();
						$stmt_ministat->bind_result($month_array[]);
						$stmt_ministat->fetch();				
						$stmt_ministat->close();

						//beta -- counted pdf pages --start	
						if ($usePdfInfo)
						{						
							$this_count = 0;
							
							$param = $get_monthcount."%";
							$stmt_countpages= $new_conn->prepare("select id,39inputdate,41instimestamp,51_pagecount from eg_item where 39inputby=? and 39inputdate like ?");
							$stmt_countpages->bind_param("ss",$get_inf,$param);
							$stmt_countpages->execute();
							$result_countpages = $stmt_countpages->get_result();
							
							while($myrow_countpages = $result_countpages->fetch_assoc())
							{
								$dir_year = substr($myrow_countpages["39inputdate"],0,4);
								$id_counted = $myrow_countpages["id"];
								$timestamp_counted = $myrow_countpages["41instimestamp"];
								$page_count = $myrow_countpages["51_pagecount"];

								if ($page_count == 0 && $usePdfInfo)
								{
									$page_count = getPDFPages2($appendroot,"../$system_docs_directory/$dir_year/$id_counted"."_"."$timestamp_counted.pdf");
									mysqli_query($GLOBALS["conn"],"update eg_item set 51_pagecount=$page_count where id=$id_counted");
								}
							
								$this_count = $this_count + $page_count;
							}
							$pages_array[] = $this_count;								
						}	
						else
							{$pages_array[] = null;}
						//beta -- counted pdf pages --end				
					}
					
					if ($_SESSION['editmode'] == 'SUPER')
						{$userappend = "&user=$get_inf";}
						
					echo "<table class=yellowHeader>";			
						echo "<tr style='background-color:#FFEE96'>";
							echo "<td><strong>Jan:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=01&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[0]</a><br/>Pages: <span style='color:magenta;'>$pages_array[0]</span></td>";
							echo "<td><strong>Feb:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=02&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[1]</a><br/>Pages: <span style='color:magenta;'>$pages_array[1]</span></td>";
							echo "<td><strong>Mac:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=03&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[2]</a><br/>Pages: <span style='color:magenta;'>$pages_array[2]</span></td>";
							echo "<td><strong>Apr:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=04&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[3]</a><br/>Pages: <span style='color:magenta;'>$pages_array[3]</span></td>";
							echo "<td><strong>May:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=05&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[4]</a><br/>Pages: <span style='color:magenta;'>$pages_array[4]</span></td>";
							echo "<td><strong>Jun:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=06&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[5]</a><br/>Pages: <span style='color:magenta;'>$pages_array[5]</span></td>";
							echo "</tr>";
					
							echo "<tr style='background-color:#FFEE96'>";
							echo "<td><strong>Jul:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=07&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[6]</a><br/>Pages: <span style='color:magenta;'>$pages_array[6]</span></td>";
							echo "<td><strong>Aug:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=08&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[7]</a><br/>Pages: <span style='color:magenta;'>$pages_array[7]</span></td>";
							echo "<td><strong>Sep:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=09&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[8]</a><br/>Pages: <span style='color:magenta;'>$pages_array[8]</span></td>";
							echo "<td><strong>Oct:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=10&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[9]</a><br/>Pages: <span style='color:magenta;'>$pages_array[9]</span></td>";
							echo "<td><strong>Nov:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=11&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[10]</a><br/>Pages: <span style='color:magenta;'>$pages_array[10]</span></td>";
							echo "<td><strong>Dec:</strong> <br/>Title: <a href='adsreport_details_pop.php?month=12&year=".$_GET["inputyear"]."$userappend' onclick='return openPopup(this.href,390,400);'>$month_array[11]</a><br/>Pages: <span style='color:magenta;'>$pages_array[11]</span></td>";
						echo "</tr>";
					echo "</table>";
					
					unset($month_array);
				}									
				
				echo "<table class=whiteHeader>";										
				echo "<tr style='text-decoration:underline;'><td width=25></td><td width=60>Title</td><td width=140>Type</td><td width=80>Input date</td></tr>";
					$n = 1;
														
					while($myrow_inputby = $result_inputby->fetch_assoc())
					{																	
						$query_type = "select 38type from eg_item_type where 38typeid = '".$myrow_inputby["38typeid"]."'";
						$result_type = mysqli_query($GLOBALS["conn"],$query_type);
						$myrow_type = mysqli_fetch_array($result_type);
													
						echo "<tr class=yellowHover>";						
							echo "<td>$n</td><td style='text-align:left;'><a href='../details.php?det=".$myrow_inputby["id"]."'>".$myrow_inputby["38title"]."</a></td>";
							echo "<td>".$myrow_type["38type"]."</td><td>".$myrow_inputby["39inputdate"]."</td>";
						echo "</tr>";
																		
						$n = $n +1 ;
					}
				echo "</table>";	
				
		?>
	</div>
		
	<br/><br/>
	
	<?php if ($_SESSION['editmode'] == 'SUPER') { ?>
		<div style="text-align:center"><a class='sButton' href='adsreport.php'><span class='fas fa-arrow-circle-left'></span> Back to report page</a></div>
	<?php }?>
	
	<br/><hr>
		
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>