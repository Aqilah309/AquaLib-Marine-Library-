<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include 'sw_includes/access_isset_guest.php';
	include 'core.php'; 
	include 'sw_includes/functions.php';	
	$thisPageTitle = "My Account";
?>

<?php
	if (isset($_GET['rb']) && is_numeric($_GET['rb']))
	{
		$stmt_chargeid = $new_conn->prepare("select id from eg_item_charge where 39patron=? and 38accessnum=? and 40dc='NO'");
			$stmt_chargeid->bind_param("si", $_SESSION['username_guest'], $_GET['rb']);
			$stmt_chargeid->execute();
			$stmt_chargeid->store_result();
			$stmt_chargeid->bind_result($id_charge);
			$stmt_chargeid->fetch();
			$stmt_chargeid->close();

		if ($id_charge != '') 
		{
			$stmt_update = $new_conn->prepare("update eg_item_charge set 40dc='DC', 40dc_on='".time()."', 40dc_by=? where 39patron=? and 38accessnum=? and 40dc != 'DC'");
			$stmt_update->bind_param("ssi", $_SESSION["username_guest"], $_SESSION['username_guest'], $_GET['rb']);
			$stmt_update->execute();
			$stmt_update->close();
		}
	}
?>

<html lang='en'>

<head>		
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>
	
	<?php 
		include 'sw_includes/navbar_guest.php'; 
		if (isset($_GET['u']) && $_GET['u'] == 'g') {$append_g = "&u=g";}
		else {$append_g = '';}
	?>
			
	<br/>
	
	<table class=whiteHeader>
		<tr class=yellowHeaderCenter>
			<td>Bookmarked items history 
				<?php
					if (isset($_GET['s']) && $_GET['s'] == 'all') {echo "[<a href='usr.php?s=no$append_g'>Show current bookmarks</a>]";}
					else {echo "[<a href='usr.php?s=all$append_g'>Show all</a>]";}
				?>
			</td>

		</tr>
		<tr>
			<td>					
				<table class=whiteHeader>
				<tr class=whiteHeaderCenter>
					<td colspan=2 style='text-align:left;'>Title</td>
					<td>Last Bookmarked</td>
					<td>Option</td>
				</tr>
				<?php
					
					if (isset($_GET['s']) && $_GET['s'] == 'all') {$query_itemcharged = "select * from eg_item_charge where 39patron='".$_SESSION['username_guest']."' order by 39charged_on desc";}				
					else {$query_itemcharged = "select * from eg_item_charge where 39patron='".$_SESSION['username_guest']."' and 40dc='NO' order by 39charged_on desc";}				
					
					$result_itemcharged = mysqli_query($GLOBALS["conn"],$query_itemcharged);
					
					$n = 1;
					$totalunpaid = 0.00;
					while ($myrow_itemcharged = mysqli_fetch_array($result_itemcharged))
						{
							$id = $myrow_itemcharged["id"];
							$patron = $myrow_itemcharged["39patron"];
							$accessnum = $myrow_itemcharged["38accessnum"];
							$charged_on = $myrow_itemcharged["39charged_on"];
							$duedate_mp = $myrow_itemcharged["39duedate"];
							$dc_on = $myrow_itemcharged["40dc_on"];
							$dc = $myrow_itemcharged["40dc"];												
															
							echo "<tr class=yellowHover><td>$n</td>";
							echo "<td style='text-align:left;'>";
								if (accessnumToID($accessnum) != '') {echo "<a href='detailsg.php?det=".accessnumToID($accessnum)."&bk=1'>".getTitle($accessnum)."</a>";}
								else {echo "<em>Item deleted</em>";}
							echo "</td>";
							echo "<td>".date('D, Y-m-d h:i:s a',$charged_on)."</td>";
							echo "<td>";
								if (getDischargeStatus($_SESSION['username_guest'],$accessnum) == 'NO') {echo "[<a href='usr.php?rb=$accessnum"."$append_g' onclick=\"return confirm('Are you sure to remove this bookmark?')\">Remove Bookmark</a>]";}
								else {echo "Unbookmarked on ".date('D, Y-m-d h:i:s a',$dc_on);}
							echo "</td>";
							echo "</tr>";		
							$n = $n + 1;
						}
				?>
				
				</table>				
			</td>
		</tr>
	</table>

	<br/>
	
	<?php include './sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>