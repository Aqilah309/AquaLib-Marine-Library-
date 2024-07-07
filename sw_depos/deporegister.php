<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php'; 
	if (isset($_SESSION['useridentity'])) {unset($_SESSION['useridentity']);}
	$thisPageTitle  = "Depositor Login Page";
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
		if (isset($_REQUEST['submitted']) && $_REQUEST["submitted"] == 'TRUE' && $proceedAfterToken)
		{
			$useridentity1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["useridentity"]);
			$userpass1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["userpass"]);
			$userpass2 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["userpass2"]);
			$fullname1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["fullname"]);
			$emailaddress1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["emailaddress"]);
			$phonenum1 = mysqli_real_escape_string($GLOBALS["conn"],$_POST["phonenum"]);
			$key = md5(microtime().rand().$emailaddress1);

			echo "<br/><br/><table style='margin-left:auto;margin-right:auto;border:0px;text-align:center;' width=400><tr><td>";
			
			if ($userpass1 == $userpass2)
			{
				//check if user the identity existed
				$stmt_count = $new_conn->prepare("select useridentity from eg_auth_depo where useridentity=?");
				$stmt_count->bind_param("s", $useridentity1);
				$stmt_count->execute();
				$stmt_count->store_result();
					$num_results_affected_username = $stmt_count->num_rows;			
				$stmt_count->close();
				
				if ($num_results_affected_username == 0)
				{
						if (!empty($useridentity1) && !empty($fullname1) && !empty($emailaddress1) && !empty($phonenum1))
						{
							$stmt_insert = $new_conn->prepare("insert into eg_auth_depo values(DEFAULT,?,AES_ENCRYPT(?,'$password_aes_key'),?,?,?,".time().",'NOTACTIVE',0,?)");
							$stmt_insert->bind_param("ssssss", $useridentity1, $userpass1,$fullname1,$emailaddress1,$phonenum1,$key);
							$stmt_insert->execute();
							$stmt_insert->close();

							if ($enable_self_activation)
							{
								$mel_subject = "$system_title : Self activation link";
								$mel_body = "Greetings, please click on this link to activate your account: <a href='$system_path"."sw_depos/depoactivate.php?k=$key'>click here</a>.<br/>$emailFooter";
								$mel_address = $emailaddress1;
								$mel_failed = "<script>alert('Error in sending email. We might have a problem with the mailing subsystem. We have to manually activate your account. Contact us for more info.');</script>";
								$mel_success = "";
								sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
					
								echo "<img src='../sw_images/tick.gif'><br/><br/><div style='text-align:center;color:blue;'>User <strong>$fullname1</strong> has been registered and activation link has been sent.<br/><br/>If you have further question kindly contact $system_admin_email</div><br/><a href='depologin.php'>Click here to continue</a><br/><br/>";								
							}
							else {
								echo "<img src='../sw_images/tick.gif'><br/><br/><div style='text-align:center;color:blue;'>User <strong>$fullname1</strong> has been registered. Activation and verification will take 24-48 hours on working days only.<br/>If you have further question kindly contact $system_admin_email</div><br/><a href='depologin.php'>Click here to continue</a><br/><br/>";
							}
						}
						
						else {
							echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Your input has been cancelled. Check if any field(s) left emptied before posting.</div><br/><a href='deporegister.php'>Click here to retry</a><br/><br/>";
						}
				}						

				else if ($num_results_affected_username >= 1) {
					echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Your input has been cancelled. Duplicate identification detected. <br/>If you have further question kindly contact $system_admin_email</div><br/><a href='deporegister.php'>Click here to retry</a><br/><br/>";
				}
				
				
			}
			else {
				echo "<img src='../sw_images/caution.png'><br/><br/><div style='text-align:center;color:red;'>Passwords not matching with one another. Please retry.</div><br/><a href='deporegister.php'>Click here to retry</a><br/><br/>";
			}
			
			echo "</td></tr></table>";
		}		
	?>

	<?php if (!isset($_REQUEST["submitted"])) {?>	
		<div style='text-align:center;'>
		<br/><img alt='Main Logo' src='../<?php echo $main_logo;?>' width=250>
		<h2>Register new account. Activation will take 24-48 hours on working days only.<br/>Plan your deposit timeframe before proceeding.<br/><br/></h2>
		<form action="deporegister.php" method="post" enctype="multipart/form-data" data-parsley-validate>

			<table style='width:450;margin-left:auto;margin-right:auto;'>								
				<tr>
				<td style='text-align:right;vertical-align:top;'><strong><?php echo $depo_txt_identification;?> </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" type="text" name="useridentity" style='width:90%;' maxlength="25"/></td>
				</tr>

				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Password </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" id="password1" type="password" name="userpass" style='width:90%;' maxlength="25"/></td>
				</tr>
				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Password (Enter again)</strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" data-parsley-equalto="#password1" type="password" name="userpass2" style='width:90%;' maxlength="25"/></td>
				</tr>

				<tr><td colspan=2><br/></td></tr>
			
				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Full Name </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" type="text" name="fullname" style='width:90%;' maxlength="80"/></td>
				</tr>
									
				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Email Address </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" data-parsley-type="email" type="text" name="emailaddress" style='width:90%;' maxlength="100"/></td>
				</tr>

				<tr>
				<td style='text-align:right;vertical-align:top;'><strong>Phone Number </strong></td>
				<td>: <input autocomplete="off" data-parsley-required="true" type="text" name="phonenum" style='width:90%;' maxlength="20"/></td>
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