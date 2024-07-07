<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Manage User Deposit";

	if (isset($_GET['scstr'])) 
	{
		$_SESSION['sqlappend'] = 'nil';
	}
	else
	{
		if (isset($_GET['v']) && $_GET['v'] == 'approved') {$_SESSION['sqlappend'] = "approved";}
		else if (isset($_GET['v']) && $_GET['v'] == 'repod') {$_SESSION['sqlappend'] = "repod";}
		else if (isset($_GET['v']) && $_GET['v'] == 'rejected') {$_SESSION['sqlappend'] = "rejected";}
		else if (isset($_GET['v']) && $_GET['v'] == 'entry') {$_SESSION['sqlappend'] = "entry";}
	}

?>

<html lang='en'>

<head>		
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>	
	<?php include '../sw_includes/navbar.php';?>

	<?php
	if (isset($_GET["del"]) && $_GET["del"] <> NULL && is_numeric($_GET["del"]))// if reject and delete
	{
		$get_id_del = $_GET["del"];
		
		$stmt_del = $new_conn->prepare("delete from eg_item_depo where id=?");
			$stmt_del->bind_param("i", $get_id_del);
			$stmt_del->execute();
			$stmt_del->close();
	}
	?>

	<hr>

	<table class=whiteHeaderNoCenter style='width:100%;'>
	<tr style='text-align:center;'>
		<td>
			<form  action="depoadmin.php" method="get" enctype="multipart/form-data" style="margin:auto;max-width:100%">	
				Search: 
				<br/><input type="text" placeholder="Enter ID" name="scstr" style='width:50%;font-size:14px' maxlength="255" value="<?php if (isset($_GET['scstr'])) {echo strip_tags(mysqli_real_escape_string($GLOBALS["conn"],$_GET['scstr']));}?>"/>
				<input type="submit" class="form-submit-button" name="s" value="Search" />
			</form>
		</td>
	</tr>
	</table>

	<br/>

	<table class=whiteHeaderNoCenter style='width:100%;'>
	<tr style='text-align:center;'>
		<td>
			View: <a style='color:blue;' href='depoadmin.php?v=approved'>Approved</a> | <a style='color:green;' href='depoadmin.php?v=repod'>Live</a> | <a style='color:red;' href='depoadmin.php?v=rejected'>Rejected</a> | <a style='color:orange;' href='depoadmin.php?v=entry'>Entry</a><br/><br/>
			Showing: 
			<?php
				if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'approved') {echo "<span style='color:blue;'>Approved</span>";}
				else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'repod') {echo "<span style='color:green;'>Live in Repository</span> [<a target='_blank' href='depoliveclear.php'>Clear storage for this status</a>]";}
				else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'rejected') {echo "<span style='color:red;'>Rejected</span>";}
				else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'entry') {echo "<span style='color:orange;'>Entry/Re-entry</span>";}
			?>
		</td>
	</tr>
	</table>

	<?php
		echo "<table class=whiteHeaderNoCenter>";	

			$sqlappend = "where itemstatus != ''";//default
			if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'approved') {$sqlappend = "where itemstatus like 'AC%'";}
			else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'repod') {$sqlappend = "where itemstatus like 'AR%'";}
			else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'rejected') {$sqlappend = "where itemstatus like 'R_%'";}
			else if (isset($_SESSION['sqlappend']) && $_SESSION['sqlappend'] == 'entry') {$sqlappend = "where (itemstatus like 'E%' or itemstatus like 'UPD%')";}

			$sqlappend_tosearch = "";//default
			if (isset($_GET['scstr'])) {$sqlappend_tosearch = " and inputby='".strip_tags(mysqli_real_escape_string($GLOBALS["conn"],$_GET['scstr']))."'";}

			//will be parameterized later
			$query_deposit = "select SQL_CALC_FOUND_ROWS * from eg_item_depo $sqlappend $sqlappend_tosearch order by id";
			$result_deposit = mysqli_query($GLOBALS["conn"],$query_deposit);
			
			echo "<tr class=yellowHeaderCenter>";
				echo "<td width=50% colspan=2>Submission</td>";
				echo "<td width=10%>Publisher</td>";
				echo "<td width=10%>Date submitted / last updated</td>";
				echo "<td width=10%>Current Status</td>";	
				echo "<td width=10%>Storage Usage</td>";	
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
			
				echo "<tr class=yellowHover style='text-align:center;'>";
					echo "<td style='vertical-align:top;'>$n</td>";

					echo "<td style='text-align:left;vertical-align:top;'>";
						echo "<font color=blue>$inputbyName ($inputby)</font>
						<br/>$titlestatement
						<br/><font color=green>$dissertation_note_b</font>
						<br/>[<a href='depodet.php?id=$id'>View Detail and Set Status</a>]";
						if (isset($_GET['v']) && $_GET['v'] == 'rejected' && $inputbyName == '') {
							echo " [<a href='depoadmin.php?v=rejected&del=$id' onclick=\"return confirm('Are you sure to delete the item: $titlestatement ?');\">Delete</a>]";
						}
					echo "</td>";

					echo "<td>$publication_b</td>";

					echo "<td>".date('Y-m-d H:i:s',$timestamp)." / ".date('Y-m-d H:i:s',$lastupdated)."</td>";
					
					echo "<td>".getDepoStatus($itemstatus)."</td>";

					echo "<td>";
						if ($depositornot == 'YES' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")) {
							echo formatBytes(filesize("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf"));
						}
						else {
							echo "-";
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