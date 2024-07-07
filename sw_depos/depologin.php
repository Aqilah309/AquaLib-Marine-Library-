<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php'; 
	include '../sw_includes/functions.php';

	if (isset($_SESSION['useridentity']) && (isset($_GET['log']) && $_GET['log'] == 'out')) {
		unset($_SESSION['useridentity']); 
	}
	else if (isset($_SESSION['useridentity']))
	{
		header("Location: depositor.php");
		die();
	}
	$thisPageTitle  = "Depositor Login Page";

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head>
	<?php
		if (!$allow_depositor_function)
		{
			header("Location: ../index.php");
			die();
		}
	?>	
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>
	
	<?php
		if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'TRUE' && $proceedAfterToken)
		{
			//get data in the table for validation before permitting access
			$stmt_login = $new_conn->prepare("
							select id, useridentity, aes_decrypt(userpass,'$password_aes_key') as userpass, activation, num_attempt from eg_auth_depo 
							where useridentity=?");
			$stmt_login->bind_param("s", $_POST['useridentity']);
			$stmt_login->execute();
			$stmt_login->store_result();
				$num_results_affected_login = $stmt_login->num_rows;
			$stmt_login->bind_result($id2, $useridentity2, $userpass2, $activation2, $num_attempt2);
			$stmt_login->fetch();				
			$stmt_login->close();
			
			if ($num_results_affected_login <> 0)
			{   
				if (just_clean($_POST['useridentity']) == $useridentity2 && $_POST['userpass'] == $userpass2 && $num_attempt2 < 5)
				{
					if ($activation2 == 'ACTIVE')
					{
						echo "<div id=screenCentered style='text-align:center;color:blue;'>Authentication complete. You will be directed in no time.</div>";
						$_SESSION['useridentity']=$useridentity2;				
						
						$stmt_update = $new_conn->prepare("update eg_auth_depo set num_attempt=0 where id=?");
						$stmt_update->bind_param("i", $id2);
						$stmt_update->execute();
						$stmt_update->close();

						echo "<script>document.location.href='depositor.php'</script>";
					}
					else {
						echo "<script>alert('Account is not activated. If you have further questions, contact us at $system_admin_email');</script>";				
					}
				}
				
				else
				{
					if ($num_attempt2 == 5) {
						echo "<script>alert('You account has been blocked. Contact us at $system_admin_email for more info.');</script>";		
					}
					else
					{
						echo "<script>alert('Incorrect authentication information detected !');</script>";		
						$num_attempt2 = $num_attempt2+1;

						$stmt_update = $new_conn->prepare("update eg_auth_depo set num_attempt=? where id=?");
						$stmt_update->bind_param("ii", $num_attempt2, $id2);
						$stmt_update->execute();
						$stmt_update->close();		
					}
				}			
			}
			
			else {
				echo "<script>alert('Cannot find username !');</script>";
			}
		}
	?>

	<table class=transparentCenter100percent>
		<tr>
			<td colspan=2 style='text-align:center;'>
			<br/><img alt='Main Logo' src='../<?php echo $main_logo;?>' width=250>
			<h1>Depositor Portal</h1>
				<form action="depologin.php" method="post" data-parsley-validate>
					<?php 							
						$datelog = date("D d/m/Y h:i a");
					?>					
					
					<br/><strong><?php echo $depo_txt_identification;?> : </strong><br/><input autocomplete="off" id="roundInputTextMin" type="text" name="useridentity" size="25" maxlength="25" data-parsley-required="true" autofocus/>
					<br/><strong>Password : </strong><br/><input autocomplete="off" id="roundInputTextMin" type="password" name="userpass" size="25" maxlength="25" data-parsley-required="true"/>
	
					<input type="hidden" name="submitted" value="TRUE" />
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
					
					<br/><br/>
					<input type="submit" class="form-submit-button" name="submit_button" value="Login" /> 
					
					<?php if ($allow_depositor_function && (!$aes_key_warning || $password_aes_key != "45C799DB3EBC65DFBC69A0F36F605E6CA2447CD519C50B7DA0D0D45D2B0F2431")) { ?>
						<br/><br/>
						<input type="button" onclick="document.location.href='deporegister.php';" class="form-submit-button" name="Register" value="Register" /> 
						<input type="button" onclick="document.location.href='depofpwd.php';" class="form-submit-button" name="Fpwd" value="Forgot Password" /> 
					<?php }?>

					<?php if ($system_mode != 'maintenance' && $enable_tutorial_button) {?>
						<input type="button" onclick="window.open('<?php echo $tutorial_link;?>');" class="form-submit-button" name="Tutorial" value="Tutorial" /> 
					<?php }?>
	
					<input type="button" class="form-grey-button" name="Cancel" value="Go to Front Page" onclick="window.location='../index.php'";>
				</form>	
			</td>
		</tr>
	</table>

	<br/><br/>

	<?php include '../sw_includes/footer.php';?>
	
</body>
	
</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>