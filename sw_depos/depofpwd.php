<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php'; 
	if (isset($_SESSION['useridentity'])) {unset($_SESSION['useridentity']);}
	$thisPageTitle  = "Depositor Forgot Password";
	include '../sw_includes/functions.php'; 	

	//this page will use phpmailer, below lines are required
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

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
			$stmt_login = $new_conn->prepare("select emailaddress, aes_decrypt(userpass,'$password_aes_key') as userpass from eg_auth_depo where emailaddress=? and activation='ACTIVE' and num_attempt < $default_num_attempt_login");
			$stmt_login->bind_param("s", $_POST['emailaddress']);
			$stmt_login->execute();
			$stmt_login->store_result();
				$num_results_affected_login = $stmt_login->num_rows;
			$stmt_login->bind_result($emailaddress,$userpass);
			$stmt_login->fetch();				
			$stmt_login->close();
			
			if ($num_results_affected_login <> 0)
			{     
				$ip = getenv('HTTP_CLIENT_IP')?:
						getenv('HTTP_X_FORWARDED_FOR')?:
						getenv('HTTP_X_FORWARDED')?:
						getenv('HTTP_FORWARDED_FOR')?:
						getenv('HTTP_FORWARDED')?:
						getenv('REMOTE_ADDR');
				
				mysqli_query($GLOBALS["conn"],"insert into eg_forgotpassword_depo values
										(DEFAULT,
										'$emailaddress',
										'$ip',
										".time()."										
										)");		
				
				if ($useEmailNotification)
				{
					$mel_subject = "$system_title : Password retrieval";
					$mel_body = "Greetings, your password is $userpass. You will be required to change this as soon as possible.<br/>$emailFooter";
					$mel_address = $emailaddress;
					$mel_failed = "<script>alert('Error in sending email. We might have a problem with the mailing subsystem. Try again later or contact us for help.');</script>";
					$mel_success = "";
					sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
				}
				
				echo "<div style='text-align:center;'>
						<img src='../$main_logo' width=250><h2>Your password has been emailed to you.</h2>
						<input type=\"button\" name=\"Cancel\" class=\"form-grey-button\" value=\"Go to Front Page\" onclick=\"window.location='../index.php'\";>
					  </div><br/><br/>";
			}
			
			else {
				echo "<script>alert('Email not registered in the system, account has not been activated or your account has been blocked. Contact our staff for more info.');window.location='depologin.php';</script>";
			}
		}
	?>

	<?php if (!isset($_REQUEST["submitted"])) {?>	
		<div style='text-align:center;'>
		<br/><img alt='System Logo' src='../<?php echo $main_logo;?>' width=250>
		<h2>Enter your registered email address for password retrieval. Your password will be emailed back to you.<br/>If however, you have entered a wrong email address during registration, you will not receive any email from us. Contact us at pustakasys@upsi.edu.my for assistance.<br/><br/></h2>
		<form action="depofpwd.php" method="post" enctype="multipart/form-data" data-parsley-validate>

			<table style='width:450;margin-left:auto;margin-right:auto;'>																	
				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Email Address </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" data-parsley-type="email" type="text" name="emailaddress" style='width:90%;' maxlength="100" autofocus/></td>
				</tr>
				
				<tr><td colspan='2' style='text-align:center;'><br/>
					<input type="hidden" name="submitted" value="TRUE" />
					<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
					<input type="submit" name="submit_button" class="form-submit-button" value="Submit" />
					<input type="button" name="Cancel" class="form-grey-button" value="Go to Front Page" onclick="window.location='../index.php'";>
				</td></tr>
			</table>
		</form>		
		</div>
	<?php }?>

	<?php include '../sw_includes/footer.php';?>
	
</body>
	
</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>