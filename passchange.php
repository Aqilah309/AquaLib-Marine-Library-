<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include 'sw_includes/access_isset_guest.php';
	include 'core.php';
	$thisPageTitle = "Change Password";

	//preventing CSRF
	include 'sw_includes/token_validate.php';
?>

<html lang='en'>

<head><?php include 'sw_includes/header.php'; ?></head>

<body>
	
	<?php include 'sw_includes/navbar_guest.php'; ?>
	
	<br/>
				
	<?php 	
		$username3 = $_SESSION['username_guest'];
				
		if (isset($_GET["upd"]))
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
						<strong>Old Password:</strong><br/><input type="password" name="password_oldverify" size="25" maxlength="40"/><br/><br/>
						<strong>New Password:</strong><br/><input type="password" name="password_new" size="25" maxlength="40"/><br/><br/>
						<strong>New Password (Again):</strong><br/><input type="password" name="password_newagain" size="25" maxlength="40"/><br/><br/>
						
						<input type="hidden" name="submitted" value="TRUE" />
						<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
						<input type="submit" name="submit_button" value="Update"/>
					</td></tr>
				</table>
			</form>
									
	<?php					
		}//if upd <> null
				
		if (isset($_POST["submitted"]) && $proceedAfterToken)
		{
			//get old password
			$stmt_getoldpwd = $new_conn->prepare("select AES_DECRYPT(syspassword,'$password_aes_key') as syspassword from eg_auth where username=? and id > 0");
			$stmt_getoldpwd->bind_param("s", $username3);
			$stmt_getoldpwd->execute();
			$stmt_getoldpwd->bind_result($password_old);
			$stmt_getoldpwd->fetch();				
			$stmt_getoldpwd->close();
			
			$password4o = $_POST["password_oldverify"];
			$password4 = $_POST["password_new"];
			$password4a = $_POST["password_newagain"];
			
			echo "<table class=whiteHeader><tr><td>";
			
			if ($password4o == $password_old)
			{
				if (!empty($password4))
				{
					if ($password4 == $password4a)
					{
						echo "<img src='sw_images/tick.gif'><br/><br/>Your password has been updated. Click <A HREF='index.php?log=out'>here</A> to log in with the new password.";
						
						$stmt_update = $new_conn->prepare("update eg_auth set syspassword=AES_ENCRYPT(?,'$password_aes_key') where username=?");
						$stmt_update->bind_param("ss", $password4, $username3);
						$stmt_update->execute();
						$stmt_update->close();
					}
					else {
						echo "<img src='sw_images/caution.png'><br/><br/><span style='color:red;'>Confirmation failed. Click <A HREF='javascript:javascript:history.go(-1)'>here</A> to reinput the password.</span>";
					}
					
				}
				else {
					echo "<img src='sw_images/caution.png'><br/><br/><span style='color:red;'>Please insert any empty field ! Click <A HREF='javascript:javascript:history.go(-1)'>here</A> to retry.</span>";
				}
			}
			else {
				echo "<img src='sw_images/caution.png'><br/><br/><span style='color:red;'>Please verify your old password ! Click <A HREF='javascript:javascript:history.go(-1)'>here</A> to retry.</span>";
			}
			
			echo "</td></tr></table>";
		}//if submitted
		else {
			echo "<br/><div style='text-align:center'><a class='sButton' href='usr.php''><span class='fas fa-arrow-circle-left'></span> Back to my account page</a></div><br/>";
		}
	?>

	<hr>	
		
	<?php include 'sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>