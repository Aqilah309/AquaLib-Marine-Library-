<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/access_super.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Add Type";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>	
	
	<hr>
	
	<?php		
		
		if (isset($_GET["del"]))
		{
			$get_id_del = mysqli_real_escape_string($GLOBALS["conn"], $_GET["del"]);

			$stmt_del = $new_conn->prepare("delete from eg_item_type where 38typeid = ?");
			$stmt_del->bind_param("i", $get_id_del);
			$stmt_del->execute();
			$stmt_del->close();
		}
		
		if (isset($_REQUEST["submitted"]) && $proceedAfterToken)
		{
			$type1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["type1"]),'min');
			
			if (isset($_POST["default1"]) && mysqli_real_escape_string($GLOBALS["conn"],$_POST["default1"]) == 'TRUE') {$default1 = 'TRUE';}
			else {$default1 = 'FALSE';}
			
			if ($_REQUEST["submitted"] == "Insert")
			{
				$stmt_count = $new_conn->prepare("select count(*) from eg_item_type where 38type = ?");
				$stmt_count->bind_param("s", $type1);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected_type);
				$stmt_count->fetch();
				$stmt_count->close();
				
				if ($num_results_affected_type == 0)
				{
					if (!empty($type1))
					{
						if ($default1 == 'TRUE') 
							{
								$stmt_update = $new_conn->prepare("update eg_item_type set 38default='FALSE'");
								$stmt_update->execute();
								$stmt_update->close();
							}
						$stmt_insert = $new_conn->prepare("insert into eg_item_type values(DEFAULT,?,?,null)");
						$stmt_insert->bind_param("ss", $type1, $default1);
						$stmt_insert->execute();
						$stmt_insert->close();
						echo "<script>window.alert('The type $type1 has been inputed into the database.');</script>";	
					}
					
					else {
						echo "<script>window.alert('Your input has been cancelled.Check if any field(s) left emptied before posting.');</script>";		
					}
				}
				
				else if ($num_results_affected_type >= 1) {
					echo "<script>window.alert('Your input has been cancelled. Duplicate TYPE detected.');</script>";			
				}
			}	
			else if ($_REQUEST["submitted"] == "Update")
			{
				$id1 = $_POST["id1"];

				if (!empty($type1))
				{					
					if ($default1 == 'TRUE') 
					{
						$stmt_update = $new_conn->prepare("update eg_item_type set 38default='FALSE'");
						$stmt_update->execute();
						$stmt_update->close();
					}
					$stmt_update = $new_conn->prepare("update eg_item_type set 38type=?, 38default=? where 38typeid=?");
					$stmt_update->bind_param("ssi", $type1, $default1,$id1);
					$stmt_update->execute();
					$stmt_update->close();
					echo "<script>window.alert('The record has been updated.');</script>";
				}
				else {
					echo "<script>window.alert('Error. Please make sure there were no empty field(s).<br/>The record has been restored to it original state.');</script>";		
				}
			}
		}

		if (isset($_GET["edt"]) && is_numeric($_GET["edt"]))
		{
			$get_id_upd = mysqli_real_escape_string($GLOBALS["conn"], $_GET["edt"]);

			$stmt3 = $new_conn->prepare("select 38typeid, 38type, 38default from eg_item_type where 38typeid = ?");
			$stmt3->bind_param("i", $get_id_upd);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($id3, $type3, $default3);
			$stmt3->fetch();
			$stmt3->close();
		}
		
	?>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td colspan=2><strong>Type Addition :</strong></td></tr>
		<tr class=greyHeaderCenter><td colspan=2><br/>
		<form action="addtype.php" method="post" enctype="multipart/form-data">
				<strong>Type Name: </strong>
				<br/><input type="text" name="type1" style="width:50%;" maxlength="150" value="<?php if (isset($type3)) {echo $type3;} ?>"/>
				<br/><input type="checkbox" name='default1' value='TRUE' <?php if (isset($default3) && $default3 == 'TRUE') {echo 'checked';}?>> Set as default
				
				<input type="hidden" name="id1" value="<?php if (isset($id3)) {echo $id3;} ?>" />
				<input type="hidden" name="submitted" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />				
				
				<br/><br/>
				<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
				<input type="submit" name="submit_button" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" /> 
				<input type="button" value="Cancel" onclick="location.href='addtype.php';">
		</form>		
		</td></tr>
	</table>
	
	<br/><br/>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td colspan=3><strong>Type Listing and Controls :</strong></td></tr>
		<tr class=whiteHeaderCenterUnderline>
			<td style='width:5%;'>ID</td>
			<td style='text-align:left;'>Description</td>
			<td style='width:150;'>Options</td>
		</tr>
		<?php		
			$n = 1;

			$stmt_fdb = $new_conn->prepare("select 38typeid, 38type, 38default from eg_item_type");
			$stmt_fdb->execute();
			$result_fdb = $stmt_fdb->get_result();
			while($myrow_fdb = $result_fdb->fetch_assoc())			
			{
				$type_fdb = $myrow_fdb["38type"];
				$typeid_fdb = $myrow_fdb["38typeid"];
				$default_fdb = $myrow_fdb["38default"];

				if ($default_fdb == 'TRUE') {$defaultmarker = "<sup style='color:green;'>DEFAULT</sup>";}
				else {$defaultmarker = "";}
				
				echo "<tr class=yellowHover>";
					echo "<td>$n</td>";
					echo "<td style='text-align:left;'>$type_fdb $defaultmarker</td>";
					echo "<td>";
						echo "<a title='Delete this record' href='addtype.php?del=$typeid_fdb' onclick=\"return confirm('Are you sure ? You are advisable to change all items based on this type to the other type before proceeding.');\"><img src='../sw_images/delete.gif'></a> ";
						echo "<a title='Update this record' href='addtype.php?edt=$typeid_fdb'><img src='../sw_images/pencil.gif'></a>";
					echo "</td>";
				echo "</tr>";
				$n = $n + 1;
			}		
			$stmt_fdb->close();
		?>
	</table>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>