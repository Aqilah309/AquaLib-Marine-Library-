<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include 'core.php'; 
	include 'sw_includes/functions.php';

	if (isset($_SESSION['username_guest']) && (isset($_GET['log']) && $_GET['log'] == 'out'))
	{
		$tempusername = $_SESSION['username_guest'];
		
		//set offline if session for a username is active
		$stmt_update = $new_conn->prepare("update eg_auth set online='OFF' where username=?");
		$stmt_update->bind_param("s", $tempusername);
		$stmt_update->execute();
		$stmt_update->close();
		
		unset($_SESSION['username_guest']); 
		unset($_SESSION['lastlogin']);
	}
	else if (isset($_SESSION['username_guest']))
	{
		header("Location: usr.php");
		die();
	}
	$thisPageTitle  = "User Login Page";

	$datelog = date("D d/m/Y h:i a");

	//preventing CSRF
	include 'sw_includes/token_validate.php';
?>

<html lang='en'>

<head>
	<?php
		if (!$allow_user_to_login)
		{
			header("Location: index.php");
			die();
		}
	?>	
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>
	
	<?php
		if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'TRUE' && $proceedAfterToken)
		{
			//get data in the table for validation before permitting access
			$stmt_login = $new_conn->prepare("
							select id, username, aes_decrypt(syspassword,'$password_aes_key') as syspassword, lastlogin, online from eg_auth 
							where username=?");
			$stmt_login->bind_param("s", $_POST['username']);
			$stmt_login->execute();
			$stmt_login->store_result();
				$num_results_affected_login = $stmt_login->num_rows;
			$stmt_login->bind_result($id2, $username2, $password2, $date2, $online2);//bind result from select statement
			$stmt_login->fetch();				
			$stmt_login->close();
			
			if ($num_results_affected_login <> 0)
			{  
				if (just_clean($_POST['username']) == $username2 && $_POST['password'] == $password2)
				{
					echo "<div id='screenCentered' style='text-align:center;color:blue;'>Authentication complete. You will be directed in no time.</div>";
					$_SESSION['username_guest'] = just_clean($_POST['username']);					
					$_SESSION['lastlogin'] = $date2;

					$stmt_update = $new_conn->prepare("update eg_auth set lastlogin=?, online='ON' where id=?");
					$stmt_update->bind_param("si", $datelog, $id2);
					$stmt_update->execute();
					$stmt_update->close();
					echo "<script>document.location.href='usr.php'</script>";
				}
							
				else
					{echo "<script>alert('Incorrect authentication information detected !');</script>";}				
			}
			
			else
				{echo "<script>alert('Cannot find username !');</script>";}
		}
	?>
		
	<table class=transparentCenter100percent>
		<tr>
			<td colspan=2 style='text-align:center;'>
			<br/><img alt='User Portal' src='sw_images/edituser_big.png' width=150><br/>
			My Account Portal<br/><br/>
				<form action="usrlogin.php" method="post" data-parsley-validate>
					<br/><strong>Identification : </strong><br/><input autocomplete="off" id="roundInputTextMin" type="text" name="username" size="25" maxlength="25" data-parsley-required="true" autofocus/>
					<br/><strong>Password : </strong><br/><input autocomplete="off" id="roundInputTextMin" type="password" name="password" size="25" maxlength="25" data-parsley-required="true"/>
							
					<input type="hidden" name="submitted" value="TRUE" />
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
					
					<br/><br/><input type="submit" class="form-submit-button" name="submit_button" value="Login" />
					<input type="button" class="form-grey-button" name="Cancel" value="Go to Front Page" onclick="window.location='index.php'";>
				</form>	
			</td>
		</tr>
	</table>

	<br/><br/>

	<?php 
		include './sw_includes/footer.php';
	?>
	
</body>
	
</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>