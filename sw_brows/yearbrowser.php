<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Year Browser";

	check_is_blocked("../$blocked_file_location/","../");
?>

<html lang='en'>

<head>	
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>

	<?php include '../sw_includes/loggedinfo.php'; ?>	

	<hr>
	
	<div style='text-align:center;'>
		<table class=whiteHeaderNoCenter>
			<tr class=yellowHeaderCenter><td colspan=2><strong>Year Browser</strong><br/></td></tr>
			<tr>
				<?php
					
					$query_yr = "select distinct(trim(38publication_c)) as year from eg_item2 order by year desc";						
					$result_yr = mysqli_query($GLOBALS["conn"],$query_yr);
					$num_results_affected = mysqli_num_rows($result_yr);
				
					$n=1;
					while ($row_yr = mysqli_fetch_array($result_yr))
						{
							$year = $row_yr['year'];
							
							if (is_numeric($year))
							{
								if ($n == 1) {
									echo "<td style='text-align:left;vertical-align:top;'>";
								}
								
								echo "<a class=thisonly href='yearbrowser_details.php?subacr=$year'>$year</a>";

								$stmt_findyear = $GLOBALS["new_conn"]->prepare("select id,43count,43lastcount_timestamp from eg_stat_year where id=?");
								$stmt_findyear->bind_param("i",$year);
								$stmt_findyear->execute();
								$stmt_findyear->store_result();
								$stmt_findyear->bind_result($found_year,$count,$lastcount_timestamp);//bind result from select statement
								$stmt_findyear->fetch();
								$stmt_findyear->close();

								$diff = time() - $lastcount_timestamp;
								if ($diff <= 86400 && $item_count_generator == 'daily')
								{
									echo " ($count)</a>";
								}
								else
								{
									echo "
									<script>
									$(document).ready(function(){		
										$.ajax({
											url: '../sw_tools/ajax_loadyearcount.php?y=$year',
											success: function(data){				
												$('#loadyearcount$year').html(data);
											}
										})
									});
									</script>
									";
									echo " (<span id='loadyearcount$year'></span>)</a>";
								}
								echo "<br/><br/>";	
								
								if ($n == ceil($num_results_affected/2)) {
									echo "</td><td style='text-align:left;vertical-align:top;'>";									
								}
								if ($n == $num_results_affected) {
									echo "</td>";	
								}
								$n=$n+1;		
							}							
						}
				?>
			</tr>
		</table>		
		<table class=whiteHeaderNoCenter><tr><td><em>Last update: <?php echo date('d M Y H:i:s',$lastcount_timestamp);?></em></td></tr></table>
	</div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>