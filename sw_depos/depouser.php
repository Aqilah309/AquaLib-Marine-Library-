<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Deposit User List";

	//this page will use phpmailer, below lines are required
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>

	<script type="text/javascript">
		window.onclick = function(event) {
			if (event.target.matches('.dropbtn2')) {
				var dropdowns = document.getElementsByClassName('dropbtn2');
				for (var i = 0; i < dropdowns.length; i++) {
					var openDropdown = dropdowns[i];
					if (openDropdown.classList.contains('show') && !event.target.classList.contains('show')) {
						openDropdown.classList.remove('show');
						}
				}
				event.target.classList.toggle("show");
			}
		}
	</script>

	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>

	<table class=whiteHeaderNoCenter style='width:100%;'>
	<tr style='text-align:center;'>
		<td>
			<form  action="depouser.php" method="get" enctype="multipart/form-data" style="margin:auto;max-width:100%">	
				Search: 
				<br/><input type="text" placeholder="Enter ID or Email Address" name="scstr" style='width:50%;font-size:14px' maxlength="255" value="<?php if (isset($_GET['scstr'])) {echo just_clean($_GET['scstr'],'min');}?>"/>
				<input type="submit" class="form-submit-button" name="s" value="Search" />
			</form>
		</td>
	</tr>
	</table>

	<br/>
	
	<?php 		
				
			if (isset($_GET["rep"]) && $_GET["rep"] <> NULL && is_numeric($_GET["rep"]))// if reset password
			{
				$get_id_rep = $_GET["rep"];

				$stmt_update = $new_conn->prepare("update eg_auth_depo set userpass=AES_ENCRYPT('$default_password_if_forgotten','$password_aes_key') where id=?");
				$stmt_update->bind_param("i", $get_id_rep);
				$stmt_update->execute();
				$stmt_update->close();
			}

			if (isset($_GET["del"]) && $_GET["del"] <> NULL && is_numeric($_GET["del"]))// if reject and delete
			{
				$get_id_del = $_GET["del"];
								
				$stmt_fdba = $new_conn->prepare("select emailaddress from eg_auth_depo where id=?");
				$stmt_fdba->bind_param("i",$get_id_del);
				$stmt_fdba->execute();
				$result_fdba = $stmt_fdba->get_result();
				$myrow_fdba = $result_fdba->fetch_assoc();
				
				$stmt_del = $new_conn->prepare("delete from eg_auth_depo where id=?");
				$stmt_del->bind_param("i", $get_id_del);
				$stmt_del->execute();
				$stmt_del->close();

				if ($useEmailNotification)
				{
					$mel_subject = "$system_title : Registration Rejected/Deleted";
					$mel_body = "Hi. your $system_title account has been rejected/deleted. <br/><br/>Kindly contact us at 05-4506797/6454 for more information or try register again with a valid input. Kindly rechecking all important fields before submitting.<br/><br/>$emailFooter";
					$mel_address = $myrow_fdba["emailaddress"];
					$mel_failed = "<script>alert('Error in sending email.'); </script>";
					$mel_success = "<script>alert('Rejection/deletion email has been sent.');</script>";
					sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
					if ($emailDebuggerEnable == 0) {echo "<script>window.location.replace('depouser.php');</script>";}
				}				
			}

			if (isset($_GET["reb"]) && $_GET["reb"] <> NULL && is_numeric($_GET["reb"]))// if unblock
			{
				$get_id_reb = $_GET["reb"];
				
				$stmt_update = $new_conn->prepare("update eg_auth_depo set num_attempt=0 where id=?");
				$stmt_update->bind_param("i", $get_id_reb);
				$stmt_update->execute();
				$stmt_update->close();

				echo "<script>window.location.replace('depouser.php');</script>";
			}

			if (isset($_GET["act"]) && $_GET["act"] <> NULL && is_numeric($_GET["act"]))// if activation
			{
				$get_id_act = $_GET["act"];
								
				$stmt_act = $new_conn->prepare("select id,useridentity, activation, emailaddress from eg_auth_depo where id=?");
				$stmt_act->bind_param("i",$get_id_act);
				$stmt_act->execute();
				$result_act = $stmt_act->get_result();
				$myrow_act = $result_act->fetch_assoc();

				if ($myrow_act['activation'] == 'ACTIVE')
					{
						mysqli_query($GLOBALS["conn"],"update eg_auth_depo set activation='NOTACTIVE' where id='".$myrow_act['id']."'");
						$prompt = 'Deactivation';
						$prompt_a = 'deactivated';
					}

				else if ($myrow_act['activation'] == 'NOTACTIVE')
					{
						mysqli_query($GLOBALS["conn"],"update eg_auth_depo set activation='ACTIVE' where id='".$myrow_act['id']."'");
						$prompt = 'Activation';
						$prompt_a = 'activated';
					}
				
				if ($useEmailNotification)
				{
					$mel_subject = "$system_title : ".$myrow_act['useridentity']." has been $prompt_a.";
					$mel_body = "Hi. your $system_title Account has been $prompt_a. <br/><br/>Kindly contact us at 05-4506797/6454 if you require more information.<br/><br/>$emailFooter";
					$mel_address = $myrow_act["emailaddress"];
					$mel_failed = "<script>alert('Error in sending email.'); </script>";
					$mel_success = "<script>alert('$prompt email has been sent.');</script>";
					sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
					if ($emailDebuggerEnable == 0) {echo "<script>window.location.replace('depouser.php');</script>";}
				}
			}
			
			
	?>

	<?php													

		if (isset($_GET['scstr']) && $_GET['scstr'] != '')
		{
			$strtosearch = just_clean(mysqli_real_escape_string($GLOBALS["conn"], $_GET['scstr']),'min');

			$param = "%$strtosearch%";
			$stmt_fdb = $new_conn->prepare("select * from eg_auth_depo where (useridentity like ? or emailaddress like ?) order by activation desc,fullname");
			$stmt_fdb->bind_param("ss",$param,$param);
			$stmt_fdb->execute();
			$result_fdb = $stmt_fdb->get_result();
			$num_results_affected_fdb = $result_fdb->num_rows;
		}
		else
		{
			if (!isset($_SESSION['appendurl'])) {
				$_SESSION['appendurl'] = "where activation='NOTACTIVE'";
			}
			
			if (isset($_GET['show']) &&  $_GET['show'] == 'ACTIVE') {
				$_SESSION['appendurl'] = "where activation='ACTIVE'";
			}
			else if (isset($_GET['show']) &&  $_GET['show'] == 'NOTACTIVE') {
				$_SESSION['appendurl'] = "where activation='NOTACTIVE'";
			}
			
			$query_fdb = "select * from eg_auth_depo ".$_SESSION['appendurl']." order by activation desc,fullname";																				
			$result_fdb = mysqli_query($GLOBALS["conn"],$query_fdb);			
			$num_results_affected_fdb = mysqli_num_rows($result_fdb);	
		}

		echo "<table class=yellowHeader>";
			echo "<tr><td><strong>Total deposit user in the system</strong> : ";
				echo "$num_results_affected_fdb <em>record(s) found.</em>";
				echo "<div style='color:blue;'>To search use CTRL+F (Windows) or CMD+F (macOS).</div>";
				echo "<br/>Select : [<a href='depouser.php?show=ACTIVE'>Show activated account</a>] [<a href='depouser.php?show=NOTACTIVE'>Show unactivated account only</a>]";
					echo "<br/><br/>
					<span class='fas fa-check-circle' style='color:blue;'></span> Approved 
					<span class='fas fa-times-circle' style='color:red;'></span> Rejected
					<span class='fas fa-folder-plus' style='color:green;'></span> Archived
					<span class='fas fa-file-alt' style='color:grey;'></span> Unprocessed
					<span class='fas fa-exclamation-triangle' style='color:orange;'></span> No submission";
			echo "</td></tr>";
		echo "</table>";	
														
		echo "<table class=whiteHeader>";										
			echo "<tr style='text-decoration:underline;'>";
				echo "<td width=10% colspan=2>#</td>";
				echo "<td width=20% style='text-align:left;'>Full Name</td>";
				echo "<td width=30% class=specialTD>User Identity</td>";
				echo "<td width=10%>Email</td>";
				echo "<td width=10%>Phone</td>";
				echo "<td width=10%>Option</td>";
				echo "<td width=10%>Activation</td>";
			echo "</tr>";
												
			$n = 1;
			while ($myrow_fdb = mysqli_fetch_array($result_fdb))
			{
				
				$id_fdb = $myrow_fdb["id"];
				$useridentity_fdb = $myrow_fdb["useridentity"];
				$fullname_fdb = $myrow_fdb["fullname"];
				$emailaddress_fdb = $myrow_fdb["emailaddress"];
				$phonenum_fdb = $myrow_fdb["phonenum"];
				$num_attempt_fdb = $myrow_fdb["num_attempt"];
				$activation_fdb = $myrow_fdb["activation"];
				if ($activation_fdb == 'ACTIVE') {$styleColor = "blue";}
				else {$styleColor = "red";}
							
				$query_deposit = "select id,itemstatus,inputby from eg_item_depo where inputby='$useridentity_fdb' limit 1";
				$result_deposit = mysqli_query($GLOBALS["conn"],$query_deposit);
				$myrow_deposit = mysqli_fetch_array($result_deposit);

				echo "<tr class=yellowHover>";
					echo "<td>$n</td>";

					echo "<td>";
						if (isset($myrow_deposit["id"])) {
							echo "<a onclick=\"return confirm('Are you sure ? This will leave this module and jump to the user deposit module.');\" href='depodet.php?id=".$myrow_deposit["id"]."'>";
								if (substr($myrow_deposit["itemstatus"],0,2) == 'AC')
									{echo " <span class='fas fa-check-circle' style='color:blue;'></span>";}
								else if (substr($myrow_deposit["itemstatus"],0,2) == 'R_')
									{echo " <span class='fas fa-times-circle' style='color:red;'></span>";}
								else if (substr($myrow_deposit["itemstatus"],0,2) == 'AR')
									{echo " <span class='fas fa-folder-plus' style='color:green;'></span>";}
								else if (substr($myrow_deposit["itemstatus"],0,2) == 'UP' || substr($myrow_deposit["itemstatus"],0,2) == 'EN')
									{echo " <span class='fas fa-file-alt' style='color:grey;'></span>";}
							echo "</a>";
						}
						else {
							echo " <span class='fas fa-exclamation-triangle' style='color:orange;'></span>";
						}
					echo "</td>";

					echo "<td style='text-align:left;text-transform: uppercase;'>";
						echo $fullname_fdb;
						if ($num_attempt_fdb >= $default_num_attempt_login) {echo " <img src='../sw_images/cu_locked.png' width=12>";}
					echo "</td>";	

					echo "<td class=specialTD>$useridentity_fdb</td>";	

					echo "<td>$emailaddress_fdb</td>";	

					echo "<td>$phonenum_fdb</td>";		

					echo "<td>";					
						echo "<div class='dropdown'><button class='dropbtn2'>Options</button><div id='myDropdown".$n."' class='dropdown-content'>";
						if ($useridentity_fdb != 'admin')
						{
							echo "<a href='depouser.php?rep=$id_fdb' onclick=\"return confirm('Are you sure to reset the user $useridentity_fdb\'s password to $default_password_if_forgotten ?');\" title='Reset Password'><img src='../sw_images/cu_resetpass.png' width=16>Reset Password</a> ";
							
							if ($num_attempt_fdb >= $default_num_attempt_login) {
								echo "<a href='depouser.php?reb=$id_fdb' onclick=\"return confirm('Are you sure to unblock the user $useridentity_fdb\'s ?');\" title='Unblock Account'><img src='../sw_images/cu_ublock.png' width=16>Unblock</a>";
							}
							
							if (!isset($myrow_deposit["id"])) {
								echo "<a href='depouser_edit.php?edt=$id_fdb' title='Edit Account'><img src='../sw_images/cu_edituser.png' width=16>Edit</a>";
							}

							echo "<a href='depouser.php?del=$id_fdb' onclick=\"return confirm('Are you sure to delete the user $useridentity_fdb ?');\" title='Delete User'><img src='../sw_images/cu_deactivate.png' width=16>Delete</a>";
						}					
						echo "</div></div>";
					echo "</td>";
					
					echo "<td><a style='color:$styleColor;' href='depouser.php?act=$id_fdb'>$activation_fdb</a></td>";	
				echo "</tr>";
				$n = $n +1 ;
			}		
		echo "</table>";
	?>
		
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>