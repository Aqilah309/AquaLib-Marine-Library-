<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_isset.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';
	$thisPageTitle = "Password Control";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>
				
	<?php 	
		$username3 = $_SESSION['username'];
				
		if (isset($_REQUEST["upd"]) && $_REQUEST["upd"] <> NULL)
		{
	?>	
			<table class=yellowHeader>
				<tr class=yellowHeaderCenter><td>
					<strong>Please input your new password alongside with it confirmation :</strong>
				</td></tr>
			</table>
						
			<form action="passchange.php" method="post">
				<table class=greyBody>
					<tr style='text-align:center;'><td>
						<?php if ($username3 != 'admin') { ?>
							<strong>Old Password:</strong><br/><input type="password" name="password_oldverify" size="25" maxlength="40"/><br/><br/>
						<?php }?>
						<strong>New Password:</strong><br/><input type="password" name="password_new" size="25" maxlength="40"/><br/><br/>
						<strong>New Password (Again):</strong><br/><input type="password" name="password_newagain" size="25" maxlength="40"/><br/><br/>
						
						<input type="hidden" name="submitted" value="TRUE" />
						<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
						<input type="hidden" name="upd" value=".g" />
						<input type="submit" name="submit_button" value="Update"/>
					</td></tr>
				</table>
			</form>									
	<?php					
		}
				
		if (isset($_POST["submitted"]) && $proceedAfterToken)
		{
			//get old password
			$stmt_getoldpwd = $new_conn->prepare("select AES_DECRYPT(syspassword,'$password_aes_key') as syspassword from eg_auth where username=? and id > 0");
			$stmt_getoldpwd->bind_param("s", $username3);
			$stmt_getoldpwd->execute();
			$stmt_getoldpwd->bind_result($password_old);
			$stmt_getoldpwd->fetch();				
			$stmt_getoldpwd->close();
			
			if (isset($_POST["password_oldverify"])) {$password4o = mysqli_real_escape_string($GLOBALS["conn"], $_POST["password_oldverify"]);}
			else {$password4o = '';}
			$password4 = mysqli_real_escape_string($GLOBALS["conn"], $_POST["password_new"]);
			$password4a = mysqli_real_escape_string($GLOBALS["conn"], $_POST["password_newagain"]);
			
			if ($password4o == $password_old || $username3 == 'admin')
			{
				if (!empty($password4))
				{
					if ($password4 == $password4a)
					{
						$stmt_update = $new_conn->prepare("update eg_auth set syspassword=AES_ENCRYPT(?,'$password_aes_key') where username=?");
						$stmt_update->bind_param("ss", $password4, $username3);
						$stmt_update->execute();
						$stmt_update->close();
						
						echo "<script>window.alert('Your password has been updated. Please login again.');location.href='../index.php?log=out';</script>";
					}
					else
					{
						echo "<script>window.alert('Confirmation failed.');history.go(-1);</script>";exit(); mysqli_close($GLOBALS["conn"]);
					}
				}
				else
				{
					echo "<script>window.alert('Please insert any empty field.');history.go(-1);</script>";exit(); mysqli_close($GLOBALS["conn"]);
				}
			}
			else {
				echo "<script>window.alert('Please verify your old password.');history.go(-1);</script>";exit(); mysqli_close($GLOBALS["conn"]);			
			}
		}
	?>

	<hr>	
		
	<?php include '../sw_includes/footer.php';?>

	<?php
	if ($_SESSION['needtochangepwd'])
	{
		createModalPopupMenuAuto ('changePassword','Change Password','You will need to change your password before using this system.');
	}
	?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>