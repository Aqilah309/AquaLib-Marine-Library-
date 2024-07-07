<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include 'core.php'; 	
	include 'sw_includes/access_allowed_adminip.php';
	$thisPageTitle  = "Main Page";
	
	//clear search sessions
	unset($_SESSION['sear_scstr']);
	unset($_SESSION['sear_sctype']);
	unset($_SESSION['sear_page']);

	if (isset($_SESSION['username']))
	{
		$tempusername = $_SESSION['username'];

		//set offline if session for a username is active
		$stmt_update = $new_conn->prepare("update eg_auth set online='OFF' where username=?");
		$stmt_update->bind_param("s", $tempusername);
		$stmt_update->execute();
		$stmt_update->close();

		unset($_SESSION['username']); 
		unset($_SESSION['editmode']);
		unset($_SESSION['lastlogin']);
		unset($_SESSION['ref']);
		unset($_SESSION['viewmode']);
	}

	$datelog = date("D d/m/Y h:i a");

	//preventing CSRF
	include 'sw_includes/token_validate.php';
?>

<html lang='en'>

<head>
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>

	<?php
		$login_label = "";
		if (isset($_REQUEST['submitted']) && $proceedAfterToken)
		{
				//get one row of data in the table for validation before permitting access with num affected result
				$stmt_login = $new_conn->prepare("
								select id, username, aes_decrypt(syspassword,'$password_aes_key') as syspassword, usertype, lastlogin, online, num_attempt from eg_auth 
								where username=?");
				$stmt_login->bind_param("s", $_POST['username']);
				$stmt_login->execute();
				$stmt_login->store_result();
					$num_results_affected_login = $stmt_login->num_rows;
				$stmt_login->bind_result($id2, $username2, $password2, $usertype2, $date2, $online2, $num_attempt2);//bind result from select statement
				$stmt_login->fetch();				
				$stmt_login->close();
				
				$login_label = "<div style='text-align:center;font-size:12px;'><strong>Status:</strong> ";
				
					if ($num_results_affected_login <> 0)
					{
						if ($usertype2 == 'STAFF' || $usertype2 == 'SUPER')
							{$allowed3 = 'STAFF';}
						else
							{$allowed3 = 'FALSE';}																															
						
						if ($_POST['username'] == $username2 && $_POST['password'] == $password2 && $allowed3 == 'STAFF' && $num_attempt2 < 5)
						{
								if ($online2 == 'OFF' || $usertype2 == 'SUPER')
								{
									$login_label .= "<label style='color:blue;'>Authentication complete. You will be directed in no time.</label>";
									
									$_SESSION['username'] = $_POST['username'];
									$_SESSION['editmode'] = $usertype2;
									$_SESSION['lastlogin'] = $date2;

									session_regenerate_id();
									$_SESSION['validSession'] = session_id();

									if ($password2 == $default_create_password || $password2 == $default_password_if_forgotten) 
										{$_SESSION['needtochangepwd'] = true;}
									else
										{$_SESSION['needtochangepwd'] = false;}
									
									//for admin, default password is also a no go
									if ($_SESSION['username'] == 'admin' && $password2 == 'pustaka')
										{$_SESSION['needtochangepwd'] = true;}

									$stmt_update = $new_conn->prepare("update eg_auth set lastlogin=?, online='ON', num_attempt=0 where id=?");
									$stmt_update->bind_param("si", $datelog, $id2);
									$stmt_update->execute();
									$stmt_update->close();
									
									echo "<script>document.location.href='index2.php'</script>";
									exit;
								}
								else {
									$login_label .= "<label style='color:red;'>Improper log-out session detected. Contact your administrator.</label>";
								}
						}
						
						else if ($_POST['username'] == $username2 && $_POST['password'] == $password2 && $allowed3 == 'FALSE') {
							$login_label .= "<label style='color:red;'>Inactive or invalid account detected! You're not permitted to enter the system.</label>";
						}
						
						else
							{
								if ($num_attempt2 == 5) {
									$login_label .= "<label style='color:red;'>Your account has been blocked. Contact us for more info.</label>";		
								}
								else
								{
									$login_label .= "<label style='color:red;'>Incorrect authentication information detected !</label>";		
									$num_attempt2 = $num_attempt2+1;

									//record all invalid access attempt
									$stmt_update = $new_conn->prepare("update eg_auth set num_attempt=? where id=?");
									$stmt_update->bind_param("ii", $num_attempt2, $id2);
									$stmt_update->execute();
									$stmt_update->close();	
								}
							}
					}
					
					else {
						$login_label .= "<label style='color:red;'>Cannot find username !</label>";
					}
		
				$login_label .= "</div><br/>";			
		}
	?>

	<table style="padding-top:30px;" class=transparentCenter100percent>		
	<tr>
		<td>
		<div id="adminlogin">	
			<img alt='Main Logo' width=<?php echo $main_logo_width;?> src="./<?php echo $main_logo;?>">
			<br/><br/>
			<form action="in.php" method="post" data-parsley-validate>
				<?php 							
					$datelog = date("D d/m/Y h:i a");
				?>
				<br/><strong>Admin Name: </strong><br/><input autocomplete="off" id="roundInputTextMin" type="text" name="username" size="25" maxlength="25" data-parsley-required="true" autofocus/>						
				<br/><strong>Password: </strong><br/><input autocomplete="off" id="roundInputTextMin" type="password" name="password" size="25" maxlength="25" data-parsley-required="true"/>
				
				<input type="hidden" name="submitted" value="TRUE" />				
				<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">

				<br/><br/>
				<input type="submit" class="form-submit-button" name="submit_button" value="Login" /> <input type="button" class="form-grey-button" name="Cancel" value="Go to Front Page" onclick="window.location='index.php'";>
			</form>
		</div>
		</td>
	</tr>
	</table>

	<?php echo $login_label;?>
	
	<br/><br/>
	
	<?php include './sw_includes/footer.php';?>
	
</body>	

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>