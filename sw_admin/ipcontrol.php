<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_super.php';	
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "IP Address Control";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>	
	
	<hr>
	
	<?php	    
		
		if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'Enforce' && $proceedAfterToken)
		{
			if ($_REQUEST['ipaddress'] != '')
			{
				$ipaddress = just_clean(mysqli_real_escape_string($GLOBALS["conn"], $_POST["ipaddress"]),'min');
				
				$stmt_count = $new_conn->prepare("select count(id) as totalcount from eg_auth_ip where ipaddress=?");
				$stmt_count->bind_param("s", $ipaddress);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected);
				$stmt_count->fetch();
				$stmt_count->close();
				
				if ($num_results_affected == 0)
				{				
					$stmt_insert = $new_conn->prepare("insert into eg_auth_ip values(DEFAULT,?)");
					$stmt_insert->bind_param("s", $ipaddress);
					$stmt_insert->execute();
					$stmt_insert->close();
					echo "<script>window.alert('Record has been inputted into the database.');</script>";
				}						

				else if ($num_results_affected == 1) {
					echo "<script>window.alert('Duplicate IP detected.');</script>";
				}
			}
			else {
				echo "<script>window.alert('IP address cannot be empty.');</script>";
			}
		}
		
		if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == "Update" && $proceedAfterToken)
		{
			if ($_REQUEST['ipaddress'] != '')
			{
				$ipaddress = mysqli_real_escape_string($GLOBALS["conn"], $_POST["ipaddress"]);

				$stmt_update = $new_conn->prepare("update eg_auth_ip set ipaddress=? where id=?");
				$stmt_update->bind_param("si", $ipaddress, $_POST['aid']);
				$stmt_update->execute();
				$stmt_update->close();		
			}
			else {
				echo "<script>window.alert('IP address cannot be empty.');</script>";
			}
		}		

		if (isset($_GET["del"]) && $_GET["del"] <> NULL && is_numeric($_GET["del"]))
		{
			$get_id_del = $_GET["del"];

			$stmt_del = $new_conn->prepare("delete from eg_auth_ip where id=?");
			$stmt_del->bind_param("i", $get_id_del);
			$stmt_del->execute();
			$stmt_del->close();
		}
		
		$ipaddressA = "";
		if (isset($_GET['aid']) && is_numeric($_GET['aid']))
		{
			$stmt3 = $new_conn->prepare("select ipaddress from eg_auth_ip where id = ?");
			$stmt3->bind_param("i", $_GET['aid']);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($ipaddressA);
			$stmt3->fetch();
			$stmt3->close();
		}
		
	?>
	
	<table class=whiteHeader>
		<tr class=yellowHeaderCenter>
			<td>
				<strong>IP Control </strong><br/><br/>
				<div style='color:blue;'><strong>E.g. :</strong><br/>
				<em>192.168.1.1 (for a specific IP) OR<br/>143.123.78 (for all in range 143.123.78.x) OR<br/> 12.128 (for all in range 12.128.x.x)</em></div>
			</td>
		</tr>
		
		<tr class=greyHeaderCenter><td style='width:370;'><br/>	
		<form action="ipcontrol.php" method="post" enctype="multipart/form-data">
			<table style='margin-left:auto;margin-right:auto;'>
				<tr>
				<td><strong>IP Address: </strong></td>
				<td><input type="text" name="ipaddress" size="35" maxlength="70" value="<?php echo $ipaddressA;?>"/></td>
				</tr>

				<tr><td colspan='2' style='text-align:center;'><br/>
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
					<?php
						if (isset($_GET['aid']) && is_numeric($_GET['aid']))
						{
							echo "<input type='hidden' name='aid' value='".$_GET['aid']."' />";	
							echo "<input type='submit' name='submitted' value='Update' /> ";	
							echo "<input type='button' name='reset' value='Cancel' onclick=\"window.location='ipcontrol.php';\"/>";
						}
						else
						{
							echo "<input type='submit' name=\"submitted\" value=\"Enforce\" /> ";
							echo "<input type='button' name=\"reset\" value=\"Reset\" onclick=\"window.location='ipcontrol.php';\"/>";
						}					
					?>
					
				</td></tr>
			</table>
		</form>	
		</td></tr>	
	</table>
		
	<br/><br/>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td colspan=3><strong>IP Listing Allow List (allowed for access to guest search and details pages):</strong></td></tr>
		<tr class=whiteHeaderCenterUnderline>
			<td></td>
			<td style='text-align:left;'>IP Address</td>
			<td>Options</td>
		</tr>
		<?php
			$query_fdb = "select * from eg_auth_ip";
			$result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);
			$n = 1;
			while ($myrow_fdb = mysqli_fetch_array($result_fdb))
				{
					$id_fdb = $myrow_fdb["id"];
					$ipaddress_fdb = $myrow_fdb["ipaddress"];

					echo "<tr class=yellowHover>";
					echo "<td width=25>$n</td>";
					echo "<td style='text-align:left;'>$ipaddress_fdb</td>";
					echo "<td width=150>";
						echo "<a title='Edit this eligibility' href='ipcontrol.php?aid=$id_fdb'><img src='../sw_images/pencil.gif'></a>";
						echo "<a title='Delete this record' href='ipcontrol.php?del=$id_fdb' onclick=\"return confirm('Are you sure ?');\"><img src='../sw_images/delete.gif'></a> ";						
					echo "</td></tr>";
					$n = $n + 1;
				}
		?>
	</table>
	
	<br/>
	
	<div style='text-align:center;'><a class='sButton' href='../sw_admin/chanuser.php'><span class='fas fa-arrow-circle-left'></span> Back to user account page</a></div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>