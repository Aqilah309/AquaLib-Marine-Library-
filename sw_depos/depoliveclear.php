<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Clear User Deposit";
	
	if (isset($_GET['pro']) && $_GET['pro'] == 'ceed')
	{
		echo "<div style='text-align:center;'>";
			$query_deposit_del = "select * from eg_item_depo where itemstatus like 'AR%' order by id";
			$result_deposit_del = mysqli_query($GLOBALS["conn"],$query_deposit_del);
			while ($myrow_deposit_del = mysqli_fetch_array($result_deposit_del))
				{
					$id = $myrow_deposit_del["id"];
					$lastupdated = $myrow_deposit_del["29lastupdated"];
					$depositornot = $myrow_deposit_del['29pfile'];
					$year = $myrow_deposit_del["year"];
					$timestamp = $myrow_deposit_del["timestamp"];
					
					if ($depositornot == 'YES' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")) {
						unlink("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf");
					}
				}			
			echo "<script>alert('Operation done.');</script>";
			echo "<br/><br/>Status: <span style='color:green;'>All selected item has been deleted.</span><br/><div style='margin-top:30px;'>[<a href='javascript:window.close();'>Close This Window</a>]</div>";
		echo "</div>";
		mysqli_close($GLOBALS["conn"]); exit();
	}
	else if (isset($_GET['pro']) && $_GET['pro'] != 'ceed')
	{
		echo "<script>alert('Illegal operation.');window.close();</script>";
		mysqli_close($GLOBALS["conn"]); exit();
	}

?>

<html lang='en'>

<head>		
	<title><?php echo $thisPageTitle;?></title>
</head>

<body>	
	
	<table style='width:100%;'>
	<tr style='text-align:center;'>
		<td>
			Showing: <span style='color:green;'>Live in Repository</span>
		</td>
	</tr>
	</table>

	<?php
		echo "<table width=100% border=1>";	
				
			$query_deposit = "select * from eg_item_depo where itemstatus like 'AR%' order by id";
			$result_deposit = mysqli_query($GLOBALS["conn"],$query_deposit);
			
			echo "<tr'>";
				echo "<td width=50% colspan=2>Submission</td>";
				echo "<td width=10%>Degree Type</td>";
				echo "<td width=10%>Publisher</td>";
				echo "<td width=10%>Last updated</td>";
				echo "<td width=10%>Full Text</td>";	
			echo "</tr>";

			$n = 1;
			while ($myrow_deposit = mysqli_fetch_array($result_deposit))
			{
				$id = $myrow_deposit["id"];
				$authorname = $myrow_deposit["29authorname"];
				$titlestatement = $myrow_deposit["29titlestatement"];
				$dissertation_note_b = $myrow_deposit["29dissertation_note_b"];
				$publication_b = $myrow_deposit["29publication_b"];
				$lastupdated = $myrow_deposit["29lastupdated"];
				$depositornot = $myrow_deposit['29pfile'];
				$year = $myrow_deposit["year"];
				$timestamp = $myrow_deposit["timestamp"];
				$itemstatus = $myrow_deposit["itemstatus"];
				$inputby = $myrow_deposit["inputby"];
				$inputbyName = getFullNameFromUserIdentity($myrow_deposit["inputby"]);
				
				if ($depositornot == 'YES' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf"))
				{
					echo "<tr>";

						echo "<td style='vertical-align:top;'>$n</td>";

						echo "<td style='text-align:left;'>$inputbyName ($inputby) - $titlestatement</td>";

						echo "<td>$dissertation_note_b</td>";

						echo "<td>$publication_b</td>";

						echo "<td>".date('Y-m-d H:i:s',$lastupdated)."</td>";
						
						echo "<td>";
								echo "<a href='../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf' target='_blank'>View</a> ";
								echo "(".formatBytes(filesize("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")).")";						
						echo "</td>";

					echo "</tr>";
					$n=$n+1;
				}				
			}

		echo "</table>";
	?>
	
	<table style='width:100%;margin-top:30px'>
	<tr style='text-align:center;'>
		<td>
			Option: [<a href='depoliveclear.php?pro=ceed' onclick="return confirm('Are you sure to continue with this operation ?');">Delete All and Clear Storage</a>] [<a href='javascript:window.close();'>Cancel and Close This Window</a>]
		</td>
	</tr>
	</table>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>