<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	$thisPageTitle = "Edit Deposit User";

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
			$useridentity1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["useridentity1"]);
			$fullname1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["fullname1"]);
			$emailaddress1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["emailaddress1"]);
			$phonenum1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["phonenum1"]);

			$id1 = $_POST["id1"];
		
			echo "<table class=whiteHeader><tr><td>";
			if (!empty($useridentity1) && !empty($emailaddress1) && !empty($phonenum1) && is_numeric($id1))
			{
				mysqli_query($GLOBALS["conn"],"update eg_auth_depo set useridentity='$useridentity1', fullname='$fullname1', emailaddress='$emailaddress1', phonenum='$phonenum1' where id=$id1");
				echo "<img src='../sw_images/tick.gif'><br/><br/>The record has been updated.";					
			}
			else {
				echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Error. Please make sure there were no empty field(s).<br/>The record has been restored to it original state.</div>";
			}
			echo "</td></tr></table>";			
		}
		

		if (isset($_GET["edt"]) && $_GET["edt"] <> NULL && is_numeric($_GET["edt"]))// if edt <> null
		{
			$get_id_upd = $_GET["edt"];

			$stmt_query3 = $new_conn->prepare("select useridentity,fullname,emailaddress,phonenum from eg_auth_depo where id=?");
			$stmt_query3->bind_param("i",$get_id_upd);
			$stmt_query3->execute();
			$result_query3 = $stmt_query3->get_result();
			$myrow3= $result_query3->fetch_assoc();

			$useridentity3 = $myrow3["useridentity"];
			$fullname3 = $myrow3["fullname"];
			$emailaddress3 = $myrow3["emailaddress"];
			$phonenum3 = $myrow3["phonenum"];
			$id3 = $get_id_upd;
		}
		else
		{
			$useridentity3 = "";
			$fullname3 = "";		
			$emailaddress3 = "";
			$phonenum3 = "";
			$id3 = "";
		}
		
	?>
	
	<?php if (!isset($_REQUEST["submitted"]) || $_REQUEST["submitted"] != "Update") { ?>
	<table class=whiteHeader>
		<tr class=yellowHeaderCenter><td><strong>Edit self-deposit user </strong></td></tr>
		<tr class=greyHeaderCenter><td style='width:370;'><br/>		
		<form action="depouser_edit.php" method="post" enctype="multipart/form-data">
			<table style='margin-left:auto;margin-right:auto;'>								
				<tr>
				<td><strong><?php echo $depo_txt_identification;?> </strong></td>
				<td><input type="text" name="useridentity1" size="40" maxlength="255" value="<?php if ($useridentity3 <> NULL) {echo $useridentity3;} ?>"/></td>
				</tr>
			
				<tr>
				<td><strong>Full Name </strong></td>
				<td><input type="text" name="fullname1" size="40" maxlength="255" value="<?php if ($fullname3 <> NULL) {echo $fullname3;} ?>"/></td>
				</tr>

				<tr>
				<td><strong>Email Address </strong></td>
				<td><input type="text" name="emailaddress1" size="40" maxlength="255" value="<?php if ($emailaddress3 <> NULL) {echo $emailaddress3;} ?>"/></td>
				</tr>

				<tr>
				<td><strong>Phone Number </strong></td>
				<td><input type="text" name="phonenum1" size="40" maxlength="255" value="<?php if ($phonenum3 <> NULL) {echo $phonenum3;} ?>"/></td>
				</tr>									

				<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
				<input type="hidden" name="id1" value="<?php if ($id3 <> NULL) {echo $id3;} ?>" />
				<input type="hidden" name="submitted" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" />			
				
				<tr><td colspan='2' style='text-align:center;'><br/><input type="submit" name="submit_button" value="<?php if (isset($_GET['edt'])) {echo "Update";} else {echo "Insert";}?>" /></td></tr>
			</table>
		</form>		
		</td></tr>
	</table>
	<?php }?>

	<br/>
	
	<div style='text-align:center;'><a class='sButton' href='depouser.php'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a></div>
	
	<hr>

	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>