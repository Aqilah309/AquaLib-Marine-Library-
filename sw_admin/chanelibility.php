<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_super.php';	
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	$thisPageTitle = "Change Eligibility";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>	
	
	<hr>
	
	<?php	    
		
		if (isset($_REQUEST['submitted'] ) && $_REQUEST['submitted'] == 'Enforce')
		{
			if ($_REQUEST['usertype'] != '')
			{
				$usertype = mysqli_real_escape_string($GLOBALS["conn"],$_POST["usertype"]);
				$usertypedesc = mysqli_real_escape_string($GLOBALS["conn"],$_POST["usertypedesc"]);
				$max_loanitem = mysqli_real_escape_string($GLOBALS["conn"],$_POST["max_loanitem"]);
				
				$stmt_count = $new_conn->prepare("select id from eg_auth_eligibility where usertype=?");
				$stmt_count->bind_param("s", $usertype);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected);
				$stmt_count->fetch();
				$stmt_count->close();
				
				if ($num_results_affected == 0)
				{				
					$stmt_insert = $new_conn->prepare("insert into eg_auth_eligibility values(DEFAULT,?,?,?)");
					$stmt_insert->bind_param("ssi", $usertype, $usertypedesc, $max_loanitem);
					$stmt_insert->execute();
					$stmt_insert->close();

					echo "<script>window.alert('Record has been inputted into the database.');</script>";
				}						

				else if ($num_results_affected == 1) {
					echo "<script>window.alert('Duplicate user type detected.');</script>";
				}
			}
			else {
				echo "<script>window.alert('User type cannot be empty.');</script>";
			}
		}
		
		if (isset($_REQUEST['submitted'] ) && $_REQUEST['submitted'] == "Update")
		{
			if ($_REQUEST['usertype'] != '')
			{
				$usertype = $_POST["usertype"];
				$usertypedesc = $_POST["usertypedesc"];
				$max_loanitem = $_POST["max_loanitem"];
				
				$stmt_update = $new_conn->prepare("update eg_auth_eligibility set usertype=?, usertypedesc=?, max_loanitem=? where id=?");
				$stmt_update->bind_param("ssii", $usertype, $usertypedesc, $max_loanitem, $_POST['aid']);
				$stmt_update->execute();
				$stmt_update->close();
				
				echo "<script>window.alert('User type has been updated.');</script>";	
			}
			else {
				echo "<script>window.alert('User type cannot be empty.');</script>";
			}
		}		
		
		if (isset($_GET['aid']) && is_numeric($_GET['aid']))
		{
			$stmt3 = $new_conn->prepare("select usertype,usertypedesc,max_loanitem from eg_auth_eligibility where id=?");
			$stmt3->bind_param("i", $_GET['aid']);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($usertypeA, $usertypedescA , $max_loanitemA);
			$stmt3->fetch();
			$stmt3->close();
		}
		
	?>
	
	<?php if (isset($_GET['aid'])) {?>
		<table class=whiteHeader>
			<tr class=yellowHeaderCenter><td><strong>Eligibility status </strong></td></tr>		
			<tr class=greyHeaderCenter><td style='width:370;'><br/>	
			<form action="chanelibility.php" method="post" enctype="multipart/form-data">
				<table style='margin-left:auto;margin-right:auto;'>
					<tr>
					<td><strong>User Type: </strong></td>
					<td><input type="text" <?php if (isset($usertypeA) && (($usertypeA == 'SUPER') || ($usertypeA == 'STAFF') || ($usertypeA == 'FALSE'))) {echo "readonly=readonly";}?> name="usertype" size="35" maxlength="70" value="<?php if (isset($usertypeA)) {echo $usertypeA;}?>"/></td>
					</tr>

					<tr>
					<td><strong>User Type Description:</strong></td>
					<td><input type="text" name="usertypedesc" size="35" maxlength="70" value="<?php if (isset($usertypedescA)) {echo $usertypedescA;}?>"/></td>
					</tr>

					<tr>
					<td><strong>Total number of allowed bookmarked items: </strong></td>
					<td><input type="text" name="max_loanitem" size="35" maxlength="70" value="<?php if (isset($max_loanitemA) && $max_loanitemA != '') {echo $max_loanitemA;} else {echo "0";}?>"/></td>
					</tr>
				
					<tr><td colspan='2' style='text-align:center;'><br/>
						<?php
						if (!isset($_GET['aid']))
							{
								echo "<input type='submit' name='submitted' value='Enforce' /> ";
								echo "<input type='button' name='reset' value='Reset' onclick=\"window.location='chanelibility.php';\"/>";
							}
						else
							{
								echo "<input type='hidden' name='aid' value='".$_GET['aid']."' />";	
								echo "<input type='submit' name='submitted' value='Update' /> ";	
								echo "<input type='button' name='reset' value='Cancel' onclick=\"window.location='chanelibility.php';\"/>";
							}
						?>					
					</td></tr>
				</table>
			</form>	
			</td></tr>	
		</table><br/><br/>
	<?php }?>

	<table style='width:100%;' class=whiteHeader>
		<tr class=yellowHeaderCenter><td colspan=6><strong>Type Listing and Controls :</strong></td></tr>
		<tr class=whiteHeaderCenter style='text-decoration:underline;'>
			<td style='width:25;'></td>
			<td style='width:35%;'>User Type</td>
			<td style='width:20%;'>User Type Description</td>
			<td>Max Bookmarked Item</td>
			<td>Options</td>
		</tr>

		<?php
			$query_fdb = "select * from eg_auth_eligibility";
			$result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);
			$n = 1;
			while ($myrow_fdb = mysqli_fetch_array($result_fdb))
				{
					$id_fdb = $myrow_fdb["id"];
					$usertype_fdb = $myrow_fdb["usertype"];
					$usertypedesc_fdb = $myrow_fdb["usertypedesc"];
					$max_loanitem_fdb = $myrow_fdb["max_loanitem"];
					
					echo "<tr class=yellowHover>";
						echo "<td>$n</td>";
						echo "<td>$usertype_fdb</td>";
						echo "<td>$usertypedesc_fdb</td>";
						echo "<td>$max_loanitem_fdb</td>";
						echo "<td><a title='Edit this eligibility' href='chanelibility.php?aid=$id_fdb'><img src='../sw_images/pencil.gif'></a></td>";
					echo "</tr>";
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