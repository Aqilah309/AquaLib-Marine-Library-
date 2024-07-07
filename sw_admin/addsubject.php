<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';
	include '../sw_includes/access_super.php';
	$thisPageTitle = "$subject_heading_as Listing";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>	
	
	<?php include '../sw_includes/loggedinfo.php';?>
	
	<hr>
		
	<?php			
			
		if (isset($_GET["del"]))
		{
			$get_id_del = mysqli_real_escape_string($GLOBALS["conn"], $_GET["del"]);

			$stmt_del = $new_conn->prepare("delete from eg_subjectheading where 43subjectid = ?");
			$stmt_del->bind_param("i", $get_id_del);
			$stmt_del->execute();
			$stmt_del->close();
		}
				
		if (isset($_REQUEST["submitted"]) && $proceedAfterToken)
		{
			$subject1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["subject1"]),'min');
			$acronym1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["acronym1"]),'min');		
			
			if ($_REQUEST["submitted"] == "Insert")
			{
				$stmt_count = $new_conn->prepare("select count(*) from eg_subjectheading where 43acronym = ?");
				$stmt_count->bind_param("s", $acronym1);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected_subject);
				$stmt_count->fetch();
				$stmt_count->close();
				
				if ($num_results_affected_subject == 0)
				{
					if (!empty($acronym1) && (!empty($subject1)))
					{
						$stmt_insert = $new_conn->prepare("insert into eg_subjectheading values(DEFAULT,?,?,DEFAULT,DEFAULT)");
						$stmt_insert->bind_param("ss", $acronym1, $subject1);
						$stmt_insert->execute();
						$stmt_insert->close();

						echo "<script>window.alert('The item $subject1 ($acronym1) has been inputed into the database.');</script>";
					}
					
					else {
						echo "<script>window.alert('Your input has been cancelled.Check if any field(s) left emptied before posting.');</script>";						
					}
				}
				
				else if ($num_results_affected_subject >= 1) {
					echo "<script>window.alert('Your input has been cancelled. Duplicate $subject_heading_as detected.');</script>";	
				}
			}	

			else if ($_REQUEST["submitted"] == "Update")
			{
				$id1 = $_POST["id1"];

				if (!empty($subject1) && !empty($acronym1))
				{
					$stmt_update = $new_conn->prepare("update eg_subjectheading set 43subject=?, 43acronym=? where 43subjectid=?");
					$stmt_update->bind_param("ssi", $subject1, $acronym1, $id1);
					$stmt_update->execute();
					$stmt_update->close();
					echo "<script>window.alert('The record has been updated.');</script>";
				}
				else {
					echo "<script>window.alert('Error. Please make sure there were no empty field(s). The record has been restored to it original state.');</script>";	
				}
			}
		}

		if (isset($_GET["edt"]))
		{
			$get_id_upd = mysqli_real_escape_string($GLOBALS["conn"], $_GET["edt"]);

			$stmt3 = $new_conn->prepare("select 43subjectid, 43subject, 43acronym from eg_subjectheading where 43subjectid = ?");
			$stmt3->bind_param("i", $get_id_upd);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($id3, $subject3, $acronym3);
			$stmt3->fetch();
			$stmt3->close();
		}
	
	?>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td><strong><?php echo $subject_heading_as;?> Addition</strong></td></tr>
		<tr class=greyHeaderCenter><td colspan=2><br/>
			<form action="addsubject.php" method="post" enctype="multipart/form-data">
				<strong><?php echo $subject_heading_as;?> : </strong>
				<br/><input type="text" name="subject1" style="width:50%" maxlength="255" value="<?php if (isset($subject3)) {echo $subject3;} ?>"/>
				
				<br/><br/>
				<strong><?php echo $subject_heading_as;?> Code : </strong>
				<br/><input type="text" name="acronym1" style="width:50%" maxlength="50" value="<?php if (isset($acronym3)) {echo $acronym3;} ?>"/>
				
				<input type="hidden" name="id1" value="<?php if (isset($id3)) {echo $id3;} ?>" />
				<input type="hidden" name="submitted" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />	

				<br/><br/>
				<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
				<input type="submit" name="submit_button" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" /> 
				<input type="button" value="Cancel" onclick="location.href='addsubject.php';">
			</form>			
		</td>
		</tr>			
	</table>
	
	<br/><br/>

	<table class=whiteHeader>
		<tr class=yellowHeaderCenter>
			<td colspan=4><strong><?php echo $subject_heading_as;?> Controls :</strong></td>
		</tr>
		
		<tr class=whiteHeaderCenterUnderline>
			<td style='width:5%;'></td>
			<td style='width:150;'><?php echo $subject_heading_as;?> Code</td>
			<td style='text-align:left;'><?php echo $subject_heading_as;?></td>			
			<td style='width:150;'>Options</td>
		</tr>
		
		<?php			
			$n=1;
			$stmt_fdb = $new_conn->prepare("select 43subjectid, 43acronym, 43subject from eg_subjectheading order by 43acronym");
			$stmt_fdb->execute();
			$result_fdb = $stmt_fdb->get_result();
			while($myrow_fdb = $result_fdb->fetch_assoc())	
				{
					$subjectid_fdb = $myrow_fdb["43subjectid"];
					$acronym_fdb = $myrow_fdb["43acronym"];
					$subject_fdb = $myrow_fdb["43subject"];
					
					echo "<tr class=yellowHover>";
						echo "<td>$n</td>";
						echo "<td>$acronym_fdb</td>";
						echo "<td style='text-align:left;'>$subject_fdb</td>";						
						echo "<td>";
								echo "<a title='Delete this record' href='addsubject.php?del=$subjectid_fdb' onclick=\"return confirm('Are you sure ? You are advisable to change all items based on this $subject_heading_as to the another before proceeding.');\"><img src='../sw_images/delete.gif'></a> ";
								echo "<a title='Update this record' href='addsubject.php?edt=$subjectid_fdb'><img src='../sw_images/pencil.gif'></a>";
						echo "</td>";
					echo "</tr>";
					
					$n = $n + 1;
				}
		?>
	</table>	

	<hr>

	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>