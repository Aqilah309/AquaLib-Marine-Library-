<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php';
	include '../sw_includes/functions.php';
	$thisPageTitle = "$subject_heading_as Browser";

	check_is_blocked("../$blocked_file_location/","../");
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>

	<div style='text-align:center;'>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>	

	<hr>
	
		<table class=whiteHeaderNoCenter>
			<tr class=yellowHeaderCenter><td colspan=2><strong><?php echo $subject_heading_as;?> Browser</strong><br/></td></tr>
			<tr>
				<?php
					
					$query_subject = "select 43subjectid, 43acronym, 43subject, 43count, 43lastcount_timestamp from eg_subjectheading order by 43subject";						
					$result_subject = mysqli_query($GLOBALS["conn"],$query_subject);
					$num_results_affected = mysqli_num_rows($result_subject);

					$n=1;
					while ($row_subject = mysqli_fetch_array($result_subject))
						{
							$id = $row_subject['43subjectid'];
							$acronym = $row_subject['43acronym'];
							$subject = $row_subject['43subject'];
							$count = $row_subject['43count'];
							$lastcount_timestamp = $row_subject['43lastcount_timestamp'];

							if ($n == 1) {
								echo "<td style='text-align:left;vertical-align:top;'>";
							}

							echo "<a class=thisonly href='subjectbrowser_details.php?subacr=$id'>$subject</a>";
							
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
										url: '../sw_tools/ajax_loadsubjectcount.php?a=$acronym&s=$subject&sid=$id',
										success: function(data){				
											$('#loadsubjectcount$id').html(data);
										}
									})
								});
								</script>
								";
							
								echo " (<span id='loadsubjectcount$id'></span>)</a>";
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