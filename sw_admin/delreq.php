<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php';include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle  = "Delete Request";	
	$_SESSION['whichbrowser'] = "delreq";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
		
	<hr>
		
	<div style='text-align:center'>
		<?php
																							
			$query_del = "select * from eg_item where 39proposedelete = 'TRUE' order by id desc";
			$result_del = mysqli_query($GLOBALS["conn"],$query_del);		
		
			$row_del = mysqli_fetch_row(mysqli_query($GLOBALS["conn"],"SELECT FOUND_ROWS()"));
			$num_results_affected_del = $row_del[0];										

			echo "<table class=yellowHeader><tr><td>";
				echo "<strong>Delete Request List</strong> : ";
				echo "$num_results_affected_del <em>record(s) found.</em>";
			echo "</td></tr></table>";
			
			echo "<table class=whiteHeaderNoCenter>";										
				echo "<tr class=whiteHeaderNoCenter style='text-decoration:underline;'><td width=45></td><td width=60%>Title</td><td>Reason</td></tr>";
																				
				$n = 1;
										
				while ($myrow_del = mysqli_fetch_array($result_del))
				{
					$id = $myrow_del["id"];
					$titlestatement = $myrow_del["38title"];
					$typestatement = $myrow_del["38typeid"];
					$authorname = $myrow_del["38author"];
					$proposedeleteby = $myrow_del["39proposedeleteby"];
					$proposedelete_reason = $myrow_del["39proposedelete_reason"];

					echo "<tr class=yellowHover>";					
						echo "<td style='text-align:center;'>$n</td>";
						echo "<td style='text-align:left;vertical-align:top;'>";
							echo "<a href='../details.php?det=$id'>$titlestatement</a><br/>";
							if ($authorname != '') {
								echo "<strong>Author</strong>: $authorname<br/>";
							}
							echo "<strong>Type</strong>: ".getTypeNameFromID($typestatement);	
							echo "<br/>Delete request by:";
							echo "<span style='font-size:12px;color:green;'>".namePatronfromUsername($proposedeleteby)."</span>";						
						echo "</td>";
						echo "<td style='text-align:left;vertical-align:top;'>";						
							echo "<font color=red>".strip_tags($proposedelete_reason)."</font>";
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