<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset_depo.php';
	include '../core.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Depositor Change Password";

	$username3 = $_SESSION['useridentity'];

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>
	
	<?php include 'navbar_depo.php'; ?>
	
	<hr>
				
	<?php			
		if (isset($_GET["upd"]) && $_GET['upd'] == 'g')
		{
	?>
			<table class=yellowHeader>
				<tr class=yellowHeaderCenter><td>
					<strong>Please input your new password alongside with it confirmation :</strong>
				</td></tr>
			</table>
						
			<form action="depopchange.php" method="post">
				<table class=greyBody>
					<tr style='text-align:center;'><td>
						<strong>Old Password:</strong><br/><input autocomplete="off" type="password" name="password3o" size="25" maxlength="25"/><br/><br/>
						<strong>New Password:</strong><br/><input autocomplete="off" type="password" name="password3" size="25" maxlength="25"/><br/><br/>
						<strong>New Password (Again):</strong><br/><input autocomplete="off" type="password" name="password3a" size="25" maxlength="25"/><br/><br/>

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
			$stmt_getoldpwd = $new_conn->prepare("select AES_DECRYPT(userpass,'$password_aes_key') as syspassword from eg_auth_depo where useridentity=?");
			$stmt_getoldpwd->bind_param("s", $username3);
			$stmt_getoldpwd->execute();
			$stmt_getoldpwd->bind_result($password_old);
			$stmt_getoldpwd->fetch();				
			$stmt_getoldpwd->close();
			
			$password4o = $_POST["password3o"];
			$password4 = $_POST["password3"];
			$password4a = $_POST["password3a"];
			
			echo "<table class=whiteHeader><tr><td>";
			
			if ($password4o == $password_old)
			{
				if (!empty($password4))
				{
					if ($password4 == $password4a)
					{
						$stmt_update = $new_conn->prepare("update eg_auth_depo set userpass=AES_ENCRYPT(?,'$password_aes_key') where useridentity=?");
						$stmt_update->bind_param("ss", $password4, $username3);
						$stmt_update->execute();
						$stmt_update->close();
						echo "<img src='../sw_images/tick.gif'><br/><br/>Your password has been updated. Click <A HREF='depositor.php'>here</A> to continue.";
					}
					else {
						echo "<img src='../sw_images/caution.png'><br/><br/><span style='color:red;'>Confirmation failed. Click <A HREF='javascript:history.go(-1)'>here</A> to reinput the password.</span>";
					}
				}
				else {
					echo "<img src='../sw_images/caution.png'><br/><br/><span style='color:red;'>Please insert any empty field ! Click <A HREF='javascript:history.go(-1)'>here</A> to retry.</span>";
				}
			}
			else {
				echo "<img src='../sw_images/caution.png'><br/><br/><span style='color:red;'>Please verify your old password ! Click <A HREF='javascript:history.go(-1)'>here</A> to retry.</span>";
			}
			
			echo "</td></tr></table>";
		}//if submitted
		else {
			echo "<div style='text-align:center;margin-top:8px;'><a class='sButton' href='depositor.php''><span class='fas fa-arrow-circle-left'></span> Back to front page</a></div>";
		}
	?>

	<hr>	
		
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>