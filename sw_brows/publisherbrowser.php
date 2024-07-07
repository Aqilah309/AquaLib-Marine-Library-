<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "$publisher_as Browser";

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
			<tr class=yellowHeaderCenter><td colspan=2><strong><?php echo $publisher_as;?> Browser</strong><br/></td></tr>
			<tr>
				<?php
					
					$query_pub = "select 43pubid, 43acronym, 43publisher, 43count, 43lastcount_timestamp from eg_publisher";						
					$result_pub = mysqli_query($GLOBALS["conn"],$query_pub);
					$num_results_affected = mysqli_num_rows($result_pub);

					$n=1;
					while ($row_pub = mysqli_fetch_array($result_pub))
						{
							$id = $row_pub['43pubid'];
							$acronym = $row_pub['43acronym'];
							$publisher = $row_pub['43publisher'];
							$count = $row_pub['43count'];
							$lastcount_timestamp = $row_pub['43lastcount_timestamp'];

							if ($n == 1) {
								echo "<td style='text-align:left;vertical-align:top;'>";
							}
							
							echo "<a class=thisonly href='publisherbrowser_details.php?subacr=$id'>$publisher</a>";
							
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
										url: '../sw_tools/ajax_loadpublishercount.php?p=$publisher&pid=$id',
										success: function(data){				
											$('#loadpubcount$id').html(data);
										}
									})
								});
								</script>
								";
								echo " (<span id='loadpubcount$id'></span>)</a>";
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