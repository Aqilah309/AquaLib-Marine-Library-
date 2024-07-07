<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php';include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle  = "Embargoed List";	
	$_SESSION['whichbrowser'] = "embargo";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
		
	<hr>
		
	<div style='text-align:center'>
		<?php
																							
			$query_emb = "select * from eg_item where 38status = 'EMBARGO' order by id desc";
			$result_emb = mysqli_query($GLOBALS["conn"],$query_emb);		
		
			$row_emb = mysqli_fetch_row(mysqli_query($GLOBALS["conn"],"SELECT FOUND_ROWS()"));
			$num_results_affected = $row_emb[0];										

			echo "<table class=yellowHeader><tr><td>";
				echo "<strong>Embargoed List</strong> : ";
				echo "$num_results_affected <em>record(s) found.</em>";
			echo "</td></tr></table>";
			
			echo "<table class=whiteHeaderNoCenter>";										
				echo "<tr class=whiteHeaderNoCenter style='text-decoration:underline;'><td width=45></td><td width=60%>Title</td><td>Duration</td></tr>";
																				
				$n = 1;
										
				while ($myrow_emb = mysqli_fetch_array($result_emb))
				{
					$id = $myrow_emb["id"];
					$titlestatement = $myrow_emb["38title"];
					$typestatement = $myrow_emb["38typeid"];
					$authorname = $myrow_emb["38author"];
					$embargo_timestamp = $myrow_emb["51_embargo_timestamp"];

					$status5_embargo_indicator = date("d M Y",$embargo_timestamp)." - ".date("d M Y",$embargo_timestamp+($embargoed_duration*86400));

					echo "<tr class=yellowHover>";					
						echo "<td style='text-align:center;'>$n</td>";
						echo "<td style='text-align:left;vertical-align:top;'>";
							echo "<a href='../details.php?det=$id'>$titlestatement</a><br/>";
							if ($authorname != '') {
								echo "<strong>Author</strong>: $authorname<br/>";
							}
							echo "<strong>Type</strong>: ".getTypeNameFromID($typestatement);						
						echo "</td>";
						echo "<td style='text-align:left;vertical-align:top;'>";						
							echo $status5_embargo_indicator;
						echo "</td>";
					echo "</tr>";
																	
					$n = $n +1 ;
				}
			echo "</table>";											
		?> 
	</div>

	<hr>
		
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>