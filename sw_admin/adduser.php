<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';
	$thisPageTitle = "Add User";
	
	//routing - experimental
	//route check before entering this page
	//route1 index2, route2 chanuser, route3 if route1 and route2 were followed
	if ((isset($_SESSION['route1']) && $_SESSION['route1'] == '1') && (isset($_SESSION['route2']) && $_SESSION['route2'] == '2')) {
		$_SESSION['route3'] = '3';
	}
	else { 
		$_SESSION['route3'] = '0';
	}

	if ($_SESSION['route3'] != '3') 
		{
			//immediately block user from any future usage
			mysqli_query($GLOBALS["conn"],"update eg_auth set num_attempt=$default_num_attempt_login where username='".$_SESSION['username']."'");
			header("Location: ../index.php?log=out");
			exit;
		}
	
	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
		
	<hr>
	
	<?php					
    
		if (isset($_REQUEST["submitted"]) && $proceedAfterToken)
		{
			$staffid1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["staffid1"]),'min');
			$fullname1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["fullname1"]),'min');
			$division1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["division1"]),'min');
			$usertype1 = just_clean(mysqli_real_escape_string($GLOBALS["conn"],$_POST["usertype1"]),'min');

			if ($_REQUEST["submitted"] == "Insert")
			{
				$stmt_count = $new_conn->prepare("select count(*) from eg_auth where username = ?");
				$stmt_count->bind_param("s", $staffid1);
				$stmt_count->execute();
				$stmt_count->bind_result($num_results_affected_username);
				$stmt_count->fetch();
				$stmt_count->close();
				
				if ($num_results_affected_username == 0)
				{
						if (!empty($staffid1) && !empty($fullname1) && !empty($division1) && !empty($usertype1))
						{
							$stmt_insert = $new_conn->prepare("insert into eg_auth values(DEFAULT,?,AES_ENCRYPT('$default_create_password','$password_aes_key'),?,?,?,'','OFF',0,DEFAULT,DEFAULT)");
							$stmt_insert->bind_param("ssss", $staffid1, $usertype1, $fullname1, $division1);
							$stmt_insert->execute();
							$stmt_insert->close();
							echo "<script>window.alert('User $fullname1 has been input into the database. Password has been set to $default_create_password');</script>";
						}
						
						else {
							echo "<script>window.alert('Your input has been cancelled. Check if any field(s) left emptied before posting.');</script>";								
						}
				}						

				else if ($num_results_affected_username >= 1) {
					echo "<script>window.alert(\"Your input has been cancelled. Duplicate field value detected.\");</script>";
				}
			}
			else if ($_REQUEST["submitted"] == "Update")
			{
				$id1 = $_POST["id1"];
			
				if (!empty($fullname1) && !empty($staffid1) && !empty($division1))
				{
					$stmt_update = $new_conn->prepare("update eg_auth set name=?, username=?, division=?, usertype=? where id=?");
					$stmt_update->bind_param("ssssi", $fullname1, $staffid1, $division1, $usertype1, $id1);
					$stmt_update->execute();
					$stmt_update->close();
					echo "<script>window.alert('The record has been updated.');</script>";				
				}
				else {
					echo "<script>window.alert('Error. Please make sure there were no empty field(s).<br/>The record has been restored to it original state.');</script>";
				}
			}
		}

		if (isset($_GET["edt"]) && $_GET["edt"] <> NULL && is_numeric($_GET["edt"]))
		{
			$get_id_upd = mysqli_real_escape_string($GLOBALS["conn"], $_GET["edt"]);

			$stmt3 = $new_conn->prepare("select id,username,usertype,division,name from eg_auth where id = ?");
			$stmt3->bind_param("i", $get_id_upd);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($id3,$username3,$usertype3,$division3,$name3);
			$stmt3->fetch();
			$stmt3->close();
		}
		
	?>
	
	<?php if (!isset($_REQUEST["submitted"]) || $_REQUEST["submitted"] != "Update") { ?>
		<table class=whiteHeader>
			<tr class=yellowHeaderCenter><td><strong>Add new user </strong></td></tr>
			<tr class=greyHeaderCenter><td style='width:370;'><br/>		
			<form action="adduser.php" method="post" enctype="multipart/form-data">
				<table style='margin-left:auto;margin-right:auto;'>								
					<tr>
					<td><strong>IC/ID </strong></td>
					<td style='text-align:left;'><input type="text" name="staffid1" size="40" maxlength="255" <?php if (isset($username3)) {echo "readonly=readonly";} ?> value="<?php if (isset($username3)) {echo $username3;} ?>"/></td>
					</tr>
				
					<tr>
					<td><strong>Full Name </strong></td>
					<td style='text-align:left;'><input type="text" name="fullname1" size="40" maxlength="255" value="<?php if (isset($name3)) {echo $name3;} ?>"/></td>
					</tr>
										
					<tr>
					<td><strong>Address </strong></td>
					<td style='text-align:left;'><textarea name="division1" cols="39" rows="5"><?php if (isset($division3)) {echo $division3;} ?></textarea></td>
					</tr>

					<tr>
					<td><strong>User Level </strong></td>
					<td style='text-align:left;'><select name="usertype1">									
					<?php
						$queryB = "select * from eg_auth_eligibility";
						$resultB = mysqli_query($GLOBALS["conn"],$queryB);
					
						while ($myrowB = mysqli_fetch_array($resultB))
							{
								$usertypeB = $myrowB["usertype"];
								$usertypedescB = $myrowB["usertypedesc"];
								echo "<option value='$usertypeB' "; if (isset($usertype3) && $usertypeB == $usertype3) {echo "selected";} echo ">$usertypeB - $usertypedescB</option>";
							}
					?>						
					</select></td>
					</tr>

					<tr>
						<td colspan='2' style='text-align:center;'>
						<input type="hidden" name="id1" value="<?php if (isset($id3)) {echo $id3;} ?>" />
						<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>" />
						<input type="hidden" name="submitted" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />		
						<input type="submit" name="submit_button" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />
						</td>
					</tr>
				</table>
			</form>		
			</td></tr>
		</table><br/>
	<?php }//if !isset submitted ?>
	
	<div style='text-align:center;'><a class='sButton' href='../sw_admin/chanuser.php'><span class='fas fa-arrow-circle-left'></span> Back to user account page</a></div>
	
	<hr>

	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>