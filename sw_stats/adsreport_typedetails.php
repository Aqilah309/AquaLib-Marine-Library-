<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Type Details";

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
		
		
		//list input years -start
		echo "<table class=yellowHeader300 style='width:450px;'><tr><td>";
			$query_year = "select distinct SUBSTRING(39inputdate,1,4) AS inputyear from eg_item order by inputyear";
			$result_year = mysqli_query($GLOBALS["conn"],$query_year);
			echo "<strong>Select year</strong> : ";
						
			echo "<select name=\"inputyear\" ONCHANGE=\"location = this.options[this.selectedIndex].value;\">";     
			echo "<option value='adsreport_typedetails.php?inputyear=".date('Y')."&type=$typeid&typetext=$typetext'>-Current Year-</option>";               
			while ($myrow_year = mysqli_fetch_array($result_year))
			{
				$inputyear = $myrow_year["inputyear"];
								
				echo "<option value=\"adsreport_typedetails.php?inputyear=$inputyear&type=$typeid&typetext=$typetext\"";
				if ((isset($_GET["inputyear"]) && $_GET["inputyear"] == $inputyear) || (!isset($_GET["inputyear"]) && $inputyear == date('Y')))
					{echo " selected";}
				echo ">$inputyear</option>";
			}                                                                 
			echo "</select>";								
		echo "</td></tr></table>"; 
		//list input years -end		
	
		echo "<table class=yellowHeader300 style='width:450px;'>";
			echo "<tr><td><strong>$typetext Statistics</strong> : ";
		echo "</td></tr></table>";	
												
		echo "<table class=whiteHeader300 style='width:450px;'>";										
			echo "<tr><td>Month</td><td width=80>Total Index</td><td width=80>Total Index (Cumulative)</td></tr>";
																							
			$total_counter = 0;
			for ($counter = 1; $counter <= 12; $counter += 1) 								
			{
				if (isset($_GET["inputyear"]) && is_numeric($_GET["inputyear"]))
					{$inputyear = $_GET["inputyear"];}
				else
					{$inputyear = date('Y');}
				
				if	($counter <= 9)
					{$query_total = "select count(id) as totalid from eg_item where 38typeid='$typeid' and 39inputdate like '$inputyear-0$counter%'";}
				else
					{$query_total = "select count(id) as totalid from eg_item where 38typeid='$typeid' and 39inputdate like '$inputyear-$counter%'";}
					
				$result_total = mysqli_query($GLOBALS["conn"],$query_total);
				$myrow_total = mysqli_fetch_array($result_total);
				$num_results_affected = $myrow_total["totalid"];
				
				echo "<tr class=yellowHover>";																									
					echo "<td>$counter/$inputyear</td>";			
					$total_counter = $total_counter + $num_results_affected;				
					echo "<td>$num_results_affected</td><td>$total_counter</td>";
				echo "</tr>";
			}	
				
			echo "<tr rowspan='2'><td colspan=3>*Please take note that, the total cumulative does not represent total index in the system. It only represent total for the current selected year.</td></tr>";
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