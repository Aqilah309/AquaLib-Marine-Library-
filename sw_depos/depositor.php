<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset_depo.php';
	include '../core.php'; 
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Depositor Account";
?>

<html lang='en'>

<head>	
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>	

	<?php include 'navbar_depo.php';?>

	<hr>

	<?php
		$query_Name = "select fullname,emailaddress,phonenum from eg_auth_depo where useridentity='".$_SESSION['useridentity']."'";
		$result_Name = mysqli_query($GLOBALS["conn"],$query_Name);
		$myrow_Name = mysqli_fetch_array($result_Name);

		echo "<table style='width:100%;background-color:lightyellow;'>";	
			echo "<tr style='text-align:left;vertical-align:top;background-color:lightyellow;'>";
				echo "<td>Welcome back, ".$myrow_Name["fullname"]." (".$_SESSION['useridentity'].").<br/><span style='color:green;'>".$myrow_Name["emailaddress"]." / ".$myrow_Name["phonenum"]."</span></td>";
			echo "</tr>";
		echo "</table>";
	?>

	<?php

		if (isset($_GET['del']) && is_numeric($_GET['del']))
		{
			$stmt_del = $new_conn->prepare("select id,year,timestamp from eg_item_depo where id=? and inputby='".$_SESSION['useridentity']."' and itemstatus not like 'A%'");
			$stmt_del->bind_param("i",$_GET['del']);
			$stmt_del->execute();
			$result_del = $stmt_del->get_result();
			$myrow_del= $result_del->fetch_assoc();

			mysqli_query($GLOBALS["conn"],"delete from eg_item_depo where id='".$myrow_del["id"]."' and inputby='".$_SESSION['useridentity']."' and itemstatus not like 'A%'");

			if (file_exists("../$system_dfile_directory/".$myrow_del["year"]."/".$myrow_del["id"].""."_".$myrow_del["timestamp"].".pdf")) {unlink("../$system_dfile_directory/".$myrow_del["year"]."/".$myrow_del["id"].""."_".$myrow_del["timestamp"].".pdf");}
			if (file_exists("../$system_pfile_directory/".$myrow_del["year"]."/".$myrow_del["id"].""."_".$myrow_del["timestamp"].".pdf")) {unlink("../$system_pfile_directory/".$myrow_del["year"]."/".$myrow_del["id"].""."_".$myrow_del["timestamp"].".pdf");}

			echo "<script>location.replace('depositor.php');</script>";
		}

	?>

	<?php
		$query_fdb = "select SQL_CALC_FOUND_ROWS * from eg_item_depo where inputby='".$_SESSION['useridentity']."' order by id";
		$result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);

		echo "<table class=whiteHeaderNoCenter>";	
			echo "<tr style='text-align:center;vertical-align:top;background-color:lightgrey;'>";
				echo "<td colspan=2>Author</td>";
				if ($allow_declaration_submission) {echo "<td width=15%>Submission Declaration</td>";}
				echo "<td width=15%>Full Text</td>";
				echo "<td>Current Status</td>";
				echo "<td>Options</td>";
			echo "</tr>";
																		
			$n = 1;
			while ($myrow_fdb = mysqli_fetch_array($result_fdb))
			{
				$id = $myrow_fdb["id"];
				$authorname = stripslashes($myrow_fdb["29authorname"]);
				$titlestatement = stripslashes($myrow_fdb["29titlestatement"]);
				$dissertation_note_b = $myrow_fdb["29dissertation_note_b"];
				$lastupdated = date('d M Y H:i:s', $myrow_fdb["29lastupdated"]);
				$year = $myrow_fdb["year"];
				$timestamp = $myrow_fdb["timestamp"];
				$itemstatus = $myrow_fdb["itemstatus"];

				if ($myrow_fdb['29dfile'] == 'YES' && file_exists("../$system_dfile_directory/$year/$id"."_".$timestamp.".pdf")) {
					$dfilelink = "<a target='blank' href='depodoc.php?t=d&docid=$id'>Click</a>";
				}
				else {
					$dfilelink = "N/A";
				}

				if ($myrow_fdb['29pfile'] == 'YES' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")) {
					$pfilelink = "<a target='blank' href='depodoc.php?t=p&docid=$id'>Click</a>";
				}
				else {
					$pfilelink = "N/A";
				}
					
				echo "<tr class=yellowHover style='text-align:center;'>";
					echo "<td style='vertical-align:top;'>$n</td>";

					echo "<td style='text-align:left;vertical-align:top;'>$titlestatement<br/><font color=green>$dissertation_note_b</font><br/><font size=1>Last updated: <em>$lastupdated</em></font></td>";

					if ($itemstatus == 'ACCEPTED')	
					{
						if ($allow_declaration_submission) {
							echo "<td>$dfilelink</td>";
						}

						echo "<td>$pfilelink</td>";
					}
					else if ($itemstatus == 'ARCHIVEDP')
					{
						echo "<td><em>Declaration archived</em></td>";
						echo "<td><em>Full text archived</em></td>";
					}
					else
					{
						echo "<td>$dfilelink</td>";
						echo "<td>$pfilelink</td>";
					}

					echo "<td>";
						echo getDepoStatus($itemstatus);
						if (substr($itemstatus,0,1) == 'A') {
							echo "<br><a href='depoacceptance.php?id=$id' target='_blank'>View/print acceptance letter</a>";
						}
					echo "</td>";	
							
					echo "<td>";
						if (substr($itemstatus,0,1) != 'A')	{
							echo "<a href='deporeg.php?upd=$id'>Edit</a> | <a href='depositor.php?del=$id' onclick=\"return confirm('Are you sure want to delete this entry?')\">Delete</a>";
						}
						else {
							echo "<img src='../sw_images/icon-tick.png' width=24>";
						}
					echo "</td>";
				echo "</tr>";
				$n=$n+1;
			}
		echo "</table>";
	?>
	
	<hr>
			
	<?php include '../sw_includes/footer.php';?>
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>