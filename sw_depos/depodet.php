<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php'; 
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Manage User Deposit";

	//this page will use phpmailer, below lines are required
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	//preventing CSRF
	include '../sw_includes/token_validate.php';
?>

<html lang='en'>

<head>	
	<?php include '../sw_includes/header.php'; ?>
</head>

<?php

	if (isset($_REQUEST['s']) && $_REQUEST['s'] == 'Set' && isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && $proceedAfterToken)
	{
		$queryInfo = "select fullname,emailaddress from eg_auth_depo where useridentity='".$_REQUEST['useridentity']."'";
		$resultInfo = mysqli_query($GLOBALS["conn"],$queryInfo);
		$myrowInfo = mysqli_fetch_array($resultInfo);

		$queryInfoDepo = "select 29titlestatement from eg_item_depo where id=".$_REQUEST['id'];
		$resultInfoDepo = mysqli_query($GLOBALS["conn"],$queryInfoDepo);
		$myrowInfoDepo = mysqli_fetch_array($resultInfoDepo);
		
		$cstatus = getDepoStatus($_REQUEST['itemstatus']);
		
		mysqli_query($GLOBALS["conn"],"update eg_item_depo set itemstatus='".$_REQUEST['itemstatus']."' where id=".$_REQUEST['id']);
		
		if (isset($myrowInfo["fullname"]) && $useEmailNotification)
		{
			$mel_subject = "Current status for : ".$myrowInfoDepo['29titlestatement'];
			$mel_body = "Hi, ".$myrowInfo["fullname"]."<br/><br/>Item: <strong><em>".$myrowInfoDepo['29titlestatement']."</em></strong> current status is : <strong>$cstatus</strong>. <br/><br/>Remarks by admin: ".$_REQUEST['remarks'];
				if ($_REQUEST['itemstatus'] == "ACCEPTED") {
					$mel_body .= "<br/><br/>Please login into your account to view/print acceptance letter. [<a href='$system_path"."sw_depos/depologin.php'>Click here to login</a>]";
				}
				$mel_body .= "<br/><br/>$emailFooter";
			$mel_address = $myrowInfo["emailaddress"];
			$mel_failed = "";
			$mel_success = "";
			sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
		}

		if (isset($_REQUEST['remarks']) && $_REQUEST['remarks'] != '')
		{
			mysqli_query($GLOBALS["conn"],
				"insert into eg_item_depo_remarks values 
				(
				DEFAULT,
				".$_REQUEST['id'].",
				'".$_REQUEST['remarks']."',
				'".time()."'
				)"
			);		
		}
		echo "<script>alert('Status has been updated.');</script>";	
	}
?>

<body>	
	<?php include '../sw_includes/navbar.php';?>

	<hr>
	<div style="width:100%;text-align:center;">
	<?php

		if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) {
			$detid = $_REQUEST['id'];
		}
		else {
			$detid = 0;
		}

		$stmt_depodet = $new_conn->prepare("select * from eg_item_depo where id=?");
		$stmt_depodet->bind_param("i",$detid);
		$stmt_depodet->execute();
		$result_depodet = $stmt_depodet->get_result();
		$num_results_affected = $result_depodet->num_rows;
		$myrow_depodet = $result_depodet->fetch_assoc();

		if ($num_results_affected >= 1)
		{
			$id = $myrow_depodet["id"];
			$authorname = $myrow_depodet["29authorname"];
			$titlestatement = $myrow_depodet["29titlestatement"];
			$dissertation_note_b = $myrow_depodet["29dissertation_note_b"];
			$publication_b = $myrow_depodet["29publication_b"];
			$pname1 = $myrow_depodet["29pname1"];
			$pname2 = $myrow_depodet["29pname2"];
			$pname3 = $myrow_depodet["29pname3"];
			$pname4 = $myrow_depodet["29pname4"];
			$pname5 = $myrow_depodet["29pname5"];
			$pname6 = $myrow_depodet["29pname6"];
			$pname7 = $myrow_depodet["29pname7"];
			$pname8 = $myrow_depodet["29pname8"];
			$pname9 = $myrow_depodet["29pname9"];
			$pname10 = $myrow_depodet["29pname10"];
			$abstract = $myrow_depodet["29abstract"];
			$accesscategory = $myrow_depodet["29accesscategory"];
			$year = $myrow_depodet["year"];
			$timestamp = $myrow_depodet["timestamp"];
			$lastupdated = $myrow_depodet["29lastupdated"];
			$itemstatus = $myrow_depodet["itemstatus"];
			$inputby = $myrow_depodet["inputby"];
			$inputbyName = getFullNameFromUserIdentity($myrow_depodet["inputby"]);
			$contactdetails = getEmailPhoneFromUserIdentity($myrow_depodet["inputby"]);

			echo "<table class=whiteHeaderNoCenter>";	
				echo "<tr class=yellowHeaderCenter>";
					echo "<td colspan=2>Details</td>";
				echo "</tr>";															
			
				if ($myrow_depodet['29dfile'] == 'YES' && file_exists("../$system_dfile_directory/$year/$id"."_".$timestamp.".pdf")) {
					$dfilelink = "<a target='blank' href='../$system_dfile_directory/$year/$id"."_".$timestamp.".pdf'>$system_dfile_directory/$year/$id"."_".$timestamp.".pdf</a>";
				}
				else {
					$dfilelink = "N/A";
				}

				if ($myrow_depodet['29pfile'] == 'YES' && file_exists("../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf")) {
					$pfilelink = "<a target='blank' href='../$system_pfile_directory/$year/$id"."_".$timestamp.".pdf'>$system_pfile_directory/$year/$id"."_".$timestamp.".pdf</a>";
				}
				else {
					$pfilelink = "N/A";
				}
				
				echo "<tr><td width=200 bgcolor=lightgrey>Submission</td><td style='text-align:left;vertical-align:top;'><font color=blue>$inputbyName ($inputby)</font> <em>$contactdetails</em><br/>$titlestatement</td></tr>";
				
				echo "<tr><td bgcolor=lightgrey>Publisher and Degree Type</td><td style='text-align:left;vertical-align:top;'>$publication_b<br/><font color=green>$dissertation_note_b</font></td></tr>";
				
				if ($allow_declaration_submission) {
					echo "<tr><td bgcolor=lightgrey>Submission Declaration Form</td><td style='text-align:left;vertical-align:top;'>$dfilelink</td></tr>";
				}
				
				echo "<tr><td bgcolor=lightgrey>Full Text</td><td style='text-align:left;vertical-align:top;'>$pfilelink</td></tr>";
				
				if (!$hide_additional_authors_entry) {
					echo "<tr><td bgcolor=lightgrey>Additional Authors</td><td style='text-align:left;vertical-align:top;'>";
						if ($pname1 != '') {echo "$pname1";}
						if ($pname2 != '') {echo ", $pname2";}
						if ($pname3 != '') {echo ", $pname3";}
						if ($pname4 != '') {echo ", $pname4";}
						if ($pname5 != '') {echo ", $pname5";}
						if ($pname6 != '') {echo ", $pname6";}
						if ($pname7 != '') {echo ", $pname7";}
						if ($pname8 != '') {echo ", $pname8";}
						if ($pname9 != '') {echo ", $pname9";}
						if ($pname10 != '') {echo ", $pname10";}
				}
				echo "</td></tr>";

				echo "<tr style='text-align:left;vertical-align:top;'><td bgcolor=lightgrey>Abstract</td><td style='text-align:left;vertical-align:top;'>$abstract</td></tr>";
				
				echo "<tr><td bgcolor=lightgrey>Access Category</td><td style='text-align:left;vertical-align:top;'>$accesscategory</td></tr>";	
				
				echo "<tr><td bgcolor=lightgrey>Inserted On</td><td style='text-align:left;vertical-align:top;'>".date('Y-m-d H:i:s',$timestamp)."</td></tr>";	
				
				echo "<tr><td bgcolor=lightgrey>Last Updated</td><td style='text-align:left;vertical-align:top;'>".date('Y-m-d H:i:s',$lastupdated)."</td></tr>";	
				
				echo "<tr><td bgcolor=lightgrey>Current Status</td>";
					echo "<td style='text-align:left;vertical-align:top;'>";
					
					//defaults
					$entry = ''; $accepted = ''; $archivedp = ''; $archivedl = ''; $r_incomplete = ''; $r_duplicate = ''; $r_contact = '';
					if ($itemstatus == "ENTRY") {$entry = "selected";}
						else if ($itemstatus == "ACCEPTED") {$accepted = "selected";}
						else if ($itemstatus == "ARCHIVEDP") {$archivedp = "selected";}
						else if ($itemstatus == "ARCHIVEDL") {$archivedl =  "selected";}
						else if ($itemstatus == "R_INCOMPLETE") {$r_incomplete = "selected";}
						else if ($itemstatus == "R_DUPLICATE") {$r_duplicate = "selected";}
						else if ($itemstatus == "R_CONTACT") {$r_contact = "selected";}
						echo "<form name='itemstatusform' action='depodet.php' method='post'>";
						echo "<input type='hidden' name='token' value='".$_SESSION['token']."'>";
						echo "<input type='hidden' name='id' value='$id'>";
						echo "<span style='color:blue;'>Email remarks:</span><br/><input type='text' style='width:80%;' name='remarks'><br/><br/>";
						echo "<span style='color:blue;'>Status:</span><br/><select name='itemstatus'>";
							echo "<option $entry value='ENTRY'>Pending Approval</option>";
							echo "<option $accepted value='ACCEPTED'>Accepted</option>";
							echo "<option $archivedp value='ARCHIVEDP'>Live in Repository</option>";
							echo "<option $archivedl value='ARCHIVEDL'>Archived for Limited Access</option>";
							echo "<option $r_incomplete value='R_INCOMPLETE'>Rejected: Incomplete</option>";
							echo "<option $r_duplicate value='R_DUPLICATE'>Rejected: Duplicate Entry</option>";
							echo "<option $r_contact value='R_CONTACT'>Rejected: Contact Admin</option>";
						echo "</select>";
						echo " <input type='hidden' name='useridentity' value='$inputby'>";
						echo " <input type='submit' name='s' value='Set'/>";
						echo "</form>";

					echo "</td>";
				echo "</tr>";		
			echo "</table>";

			echo "<br/>";
			echo "<table class=whiteHeaderNoCenter>";			
				$query_log = "select * from eg_item_depo_remarks where eg_item_depo_id=$id order by id";
				$result_log = mysqli_query($GLOBALS["conn"],$query_log);
				
				echo "<tr style='background-color:lightgrey;'>";
					echo "<td width=30%>Date recorded</td>";
					echo "<td width=70%>Email Remarks</td>";		
				echo "</tr>";

				while ($myrow_log = mysqli_fetch_array($result_log))
				{
					$remarks = $myrow_log["39remarks"];
					$timestamp = $myrow_log["timestamp"];
				
					echo "<tr style='text-align:center;'>";
						echo "<td>".date('Y-m-d H:i:s',$timestamp)."</td>";
						echo "<td>$remarks</td>";				
					echo "</tr>";
				}
			echo "</table>";

		}
		else {
			echo "<br/>Item is not available.<br/>";
		}
	?>
	
	<br/>
	</div>

	<div style="width:100%;text-align:center;">
		<a class='sButton' href='depoadmin.php'><span class='fas fa-arrow-circle-left'></span> Back to Manage User Deposit</a>
	</div>

	<hr>		
	<?php include '../sw_includes/footer.php';?>
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>