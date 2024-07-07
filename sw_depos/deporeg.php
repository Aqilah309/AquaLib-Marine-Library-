<!DOCTYPE HTML>
<?php
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset_depo.php';
	include '../core.php'; 
	include '../sw_includes/functions.php'; 	

	//this page will use phpmailer, below lines are required
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	if (!isset($_REQUEST["upd"]))
	{
		$query_countuseridentity = "select count(id) as totaluserdeposit from eg_item_depo where inputby='".$_SESSION['useridentity']."'";
		$result_countuseridentity = mysqli_query($GLOBALS["conn"],$query_countuseridentity);
		$myrow_countuseridentity= mysqli_fetch_array($result_countuseridentity);
		$totaluserdeposit = $myrow_countuseridentity["totaluserdeposit"];
		if ($totaluserdeposit >= $limit_amount_userdeposit)
		{
			echo "<script>alert('You do not have anymore upload quotas.');window.location='depositor.php';</script>";
			exit;
		}
	}
	$thisPageTitle = "Deposit new item";	

	//preventing CSRF
	include '../sw_includes/token_validate.php';

	//convert to bytes for things involving file size; original is in MB
	$system_allow_dfile_maxsize = $system_allow_dfile_maxsize*1000000;
    $system_allow_pfile_maxsize = $system_allow_pfile_maxsize*1000000;

?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
	<script type="text/javascript">
		<?php
			generateFileBoxAllowedExtensionRule("pfile1", $system_allow_pdocument_extension);
			generateFileBoxAllowedExtensionRule("dfile1", $system_allow_pdocument_extension);
		?>
	</script>
</head>

<body>	

	<?php include 'navbar_depo.php'; ?>
			
	<hr>
	
	<?php 		

		//if user submitted an entry
		if (isset($_REQUEST['submitted']) && $proceedAfterToken)
		{
			$titlestatement1 = setDefaultForPostVar($_POST["titlestatement1"] ?? '');
			$authorname1 = setDefaultForPostVar($_POST["authorname1"] ?? '');
			$publication1_b = setDefaultForPostVar($_POST["publication1_b"] ?? '');
			$publication1_c = setDefaultForPostVar($_POST["publication1_c"] ?? '');
			$dissertation_note1_b = setDefaultForPostVar($_POST["dissertation_note1_b"] ?? '');
			$pname1 = setDefaultForPostVar($_POST["pname1"] ?? '');
			$pname2 = setDefaultForPostVar($_POST["pname2"] ?? '');
			$pname3 = setDefaultForPostVar($_POST["pname3"] ?? '');
			$pname4 = setDefaultForPostVar($_POST["pname4"] ?? '');
			$pname5 = setDefaultForPostVar($_POST["pname5"] ?? '');
			$pname6 = setDefaultForPostVar($_POST["pname6"] ?? '');
			$pname7 = setDefaultForPostVar($_POST["pname7"] ?? '');
			$pname8 = setDefaultForPostVar($_POST["pname8"] ?? '');
			$pname9 = setDefaultForPostVar($_POST["pname9"] ?? '');
			$pname10 = setDefaultForPostVar($_POST["pname10"] ?? '');
			$abstract1 = setDefaultForPostVar($_POST["abstract1"] ?? '');
			$accesscategory1 = setDefaultForPostVar($_POST["accesscategory1"] ?? '');
			$instimestamp1 = setDefaultForPostVar($_POST["instimestamp1"] ?? '');
			$inputby1 = setDefaultForPostVar($_POST["inputby1"] ?? '');
	
			$year1 = date("Y");

			$queryInfo = "select fullname,emailaddress from eg_auth_depo where useridentity='".$_SESSION['useridentity']."'";
			$resultInfo = mysqli_query($GLOBALS["conn"],$queryInfo);
			$myrowInfo = mysqli_fetch_array($resultInfo);
				
			echo "<table class=whiteHeader>";
			echo "<tr><td>";
				if (!empty($titlestatement1))
				{									
					//if new insert
					if ($_REQUEST['submitted'] == 'TRUE')
					{
						mysqli_query($GLOBALS["conn"],"insert into eg_item_depo values
										(DEFAULT,
										'$authorname1',
										'$titlestatement1',
										'$publication1_b',
										'$publication1_c',
										'$dissertation_note1_b',
										'$pname1',
										'$pname2',
										'$pname3',
										'$pname4',
										'$pname5',
										'$pname6',
										'$pname7',
										'$pname8',
										'$pname9',
										'$pname10',
										'NO',
										'NO',
										'$abstract1',
										'$accesscategory1',
										'".time()."',
										'$year1',
										'$inputby1',
										'$instimestamp1',
										'ENTRY'
										)");		
						echo "<br/><img src='../sw_images/tick.gif'><br/><br/>All provided data has been inputted into the database.<br/>";					
						
						if ($useEmailNotification)
						{
							$mel_subject = "Deposit item : $titlestatement1";
							$mel_body = "Hi, ".$myrowInfo["fullname"]."<br/><br/>Item: <strong><em>$titlestatement1</em></strong> has been submitted for acceptance.<br/><br/>$emailFooter";
							$mel_address = $myrowInfo["emailaddress"];
							$mel_failed = "<script>alert('Error in sending email but your item already been submitted. Kindly wait for our confirmation.');</script>";
							$mel_success = "<script>alert('Your digital item has been uploaded. You will be contacted via email for further communication.');</script>";
							sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
						}
						
						//prompt user to get the id for the item that has been inputted
						$queryN = "select id from eg_item_depo where timestamp='$instimestamp1' and inputby='$inputby1'";
						$resultN = mysqli_query($GLOBALS["conn"],$queryN);
						$myrowN = mysqli_fetch_array($resultN);
						$idUpload = $myrowN["id"];//reassign and pass it

						$timestampUpload = $instimestamp1; //reassign and pass it
						$dir_year = $year1;//reassign and pass it
					}
					
					//else if update existing
					else if ($_REQUEST['submitted'] == "Update")
					{
						$idUpload = $_POST["upd"];
						$timestampUpload = $_POST["oldtimestamp"]; //reassign and pass it	
						$dir_year = $_POST["oldyear"];//reassign and pass it	

						mysqli_query($GLOBALS["conn"],"update eg_item_depo set 
						29authorname='$authorname1',
						29titlestatement='$titlestatement1',
						29publication_b='$publication1_b',
						29publication_c='$publication1_c',
						29dissertation_note_b='$dissertation_note1_b',
						29pname1='$pname1',
						29pname2='$pname2',
						29pname3='$pname3',
						29pname4='$pname4',
						29pname5='$pname5',
						29pname6='$pname6',
						29pname7='$pname7',
						29pname8='$pname8',
						29pname9='$pname9',
						29pname10='$pname10',
						29abstract='$abstract1',
						29accesscategory='$accesscategory1',
						29lastupdated='".time()."',
						year='$year1',
						inputby='$inputby1', 
						itemstatus='UPD_METADATA' 
						where id=$idUpload");
						echo "<br/><img src='../sw_images/tick.gif'><br/><br/>All provided data has been updated into the database.<br/>";	
						
						if ($useEmailNotification)
						{
							$mel_subject = "Updated deposit item : $titlestatement1";
							$mel_body = "Hi, ".$myrowInfo["fullname"]."<br/><br/>Item: <strong><em>$titlestatement1</em></strong> has been resubmitted for acceptance.<br/><br/>$emailFooter";
							$mel_address = $myrowInfo["emailaddress"];
							$mel_failed = "<script>alert('Error in sending email but your item already been updated. Kindly wait for our confirmation.');</script>";
							$mel_success = "<script>alert('Your item has been updated. You will be contacted via email for further communication.');</script>";
							sendEmail("..",$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed);
						}
					}

					if ($_REQUEST['submitted'] == 'TRUE') {$set_itemstatus = 'ENTRY';}
					else if ($_REQUEST['submitted'] == "Update") {$set_itemstatus = 'UPD_SUBMISSION';}
					
					//submission to library file upload to server
					if (isset($_FILES['dfile1']['name']) && $_FILES['dfile1']['name'] != null)
					{		
						$filedirectory = $system_dfile_directory;
						$filemaxsize = $GLOBALS["system_allow_dfile_maxsize"];
						$filefield = "29dfile";
						$thisfile = "dfile1";
						$filesuccessfulupload = "Submission declaration form attachment uploaded successfully !";
						$filesizeerror = "Upload aborted ! Attachment file size > than expected.";
						$filetypeerror = ">Upload aborted ! Incorrect file type.";
						
						echo "<br/><br/>File upload status :";
						include 'upl/upload_pdf.php';							
					}

					//full text file upload to server
					if (isset($_FILES['pfile1']['name']) && $_FILES['pfile1']['name'] != null)
					{		
						$filedirectory = $system_pfile_directory;
						$filemaxsize = $GLOBALS["system_allow_pfile_maxsize"];
						$filefield = "29pfile";
						$thisfile = "pfile1";
						$filesuccessfulupload = "Full text file attachment uploaded successfully !";
						$filesizeerror = "Upload aborted ! Attachment file size > than expected.";
						$filetypeerror = ">Upload aborted ! Incorrect file type.";	
						
						echo "<br/><br/>File upload status :";
						include 'upl/upload_pdf.php';

					}					
				}
			
				else {
					echo "<img src='../sw_images/caution.png'><br/><br/><span style='color:red;'>Your previous input has been cancelled.Check if any field(s) left emptied before posting.</span>";
				}
				
			echo "<br/><br/>Click <a href='depositor.php'>here</a> to view your submission.<br/><br/>";
			echo "</td></tr>";
			echo "</table><br/><br/>";
		}//submitted new  --end	
	?>

	<?php

		//if user want to update their deposit, fields will be automatically retrieved from database
		if (isset($_REQUEST['upd']) && is_numeric($_REQUEST['upd']))
		{
			$stmt_query1 = $new_conn->prepare("select SQL_CALC_FOUND_ROWS * from eg_item_depo where id=? and inputby='".$_SESSION['useridentity']."'");
			$stmt_query1->bind_param("i",$_REQUEST['upd']);
			$stmt_query1->execute();
			$result_query1 = $stmt_query1->get_result();
			$num_results_affected = $result_query1->num_rows;
			$myrow1= $result_query1->fetch_assoc();

			if ($num_results_affected >= 1)
			{
				$id = $myrow1["id"];
				$authorname = stripslashes($myrow1["29authorname"]);
				$titlestatement = stripslashes($myrow1["29titlestatement"]);
				$publication_b = $myrow1["29publication_b"];
				$publication_c = $myrow1["29publication_c"];
				$dissertation_note_b = $myrow1["29dissertation_note_b"];
				$pname1 = stripslashes($myrow1['29pname1']);
				$pname2 = stripslashes($myrow1['29pname2']);
				$pname3 = stripslashes($myrow1['29pname3']);
				$pname4 = stripslashes($myrow1['29pname4']);
				$pname5 = stripslashes($myrow1['29pname5']);
				$pname6 = stripslashes($myrow1['29pname6']);
				$pname7 = stripslashes($myrow1['29pname7']);
				$pname8 = stripslashes($myrow1['29pname8']);
				$pname9 = stripslashes($myrow1['29pname9']);
				$pname10 = stripslashes($myrow1['29pname10']);
				$abstract = stripslashes($myrow1['29abstract']);
				$accesscategory = $myrow1['29accesscategory'];
				$dfile = $myrow1['29dfile'];
				$pfile = $myrow1['29pfile'];
				$oldtimestamp = $myrow1['timestamp'];
				$oldyear = $myrow1['year'];
				$inputby = $myrow1['inputby'];

				$itemstatus = $myrow1['itemstatus'];
				if ($itemstatus == 'ACCEPTED' || substr($itemstatus,0,2) == 'AR')
				{
					echo "<script>alert('Your item has been accepted. Editing is not allowed.');window.location.href='depositor.php';</script>";
					exit;
				}
				if ($inputby != $_SESSION['useridentity'])
				{
					echo "<script>alert('Illegal access. You have been warned.');window.location.href='depositor.php';</script>";
					exit;
				}
			}
			else
			{
				echo "<script>alert('Illegal access. You have been warned.');window.location.href='depositor.php';</script>";
				exit;
			}
		}
		else if (isset($_REQUEST['upd']) && !is_numeric($_REQUEST['upd']))
		{
			echo "<script>alert('Illegal access. You have been warned.');window.location.href='depositor.php';</script>";
			exit;
		}
		//update existing --end
	?>
			
	<?php
		//insert or update form depending on what user want
		if (!isset($_REQUEST['submitted']))
		{	
	?>
			<table class=yellowHeader >
				<tr>
				<td>
					<strong>Fill in all fields: </strong>
					<br/><div style='color:blue;'>You may require a <u>good internet connection</u> for uploading your materials.</div>
				</td>
				</tr>
			</table>
			
			<form name="swadahform" action="deporeg.php" method="post" enctype="multipart/form-data" data-parsley-validate>
				<table class=greyBody>		
						
						<tr style='background-color:grey;'>
							<td colspan=2 style='text-align:left;vertical-align:top;color:white;'><strong><?php echo $depo_txt_mandatory_fields;?></strong></td>
						</tr>

						<?php if (!$hide_main_author) {?>
						<tr>
						<td style='text-align:right;vertical-align:top;width:350;'><strong><?php echo $tag_100_simple;?></strong></td>
						<td style='vertical-align:top;'>: <input data-parsley-required="true" type="text" name="authorname1" value="<?php if (isset($authorname)) {echo $authorname;}?>" style="width:80%" maxlength="150"/></td>
						</tr>
						<?php }?>	
						
						<tr>
						<td style='text-align:right;vertical-align:top;width:350;'><strong><?php echo $tag_245_simple;?></strong></td>
						<td style='vertical-align:top;'>: <input data-parsley-required="true" type="text" name="titlestatement1" value="<?php if (isset($titlestatement)) {echo $titlestatement;}?>" style="width:80%" maxlength="255"/>
						</td>
						</tr>
				
						<tr>
						<td style='text-align:right;vertical-align:top;'>Name of publisher</td>
						<td style='vertical-align:top;'>: 
							<select name="publication1_b" style='width:80%;'>						
							<?php
								$queryB = "select * from eg_publisher";
								$resultB = mysqli_query($GLOBALS["conn"],$queryB);
													
								while ($myrowB = mysqli_fetch_array($resultB))
									{
										echo "<option ";
										if (isset($publication_b) && $publication_b == $myrowB["43acronym"]) {echo "selected";}
										echo" value='".$myrowB["43acronym"]."'>".$myrowB["43publisher"]."</option>";
									}
							?>						
							</select>
						</td>
						</tr>	
						
						<?php if ($show_dateof_publication) {?>
						<tr>
						<td style='text-align:right;vertical-align:top;'>Date of publication</td>
						<td style='vertical-align:top;'>: <input data-parsley-required="true" type="text" name="publication1_c" style="width:80%" value="<?php if (isset($publication_c)) {echo $publication_c;}?>" maxlength="255"/></td>
						</tr>
						<?php }?>

						<tr>
								<td style='text-align:right;vertical-align:top;'><strong><?php echo $tag_502_simple;?></strong></td>
								<td style='vertical-align:top;'>:
									<?php
										if ($tag_502_inputtype == "select")
										{
											$selectable_502_b = explode("|",$tag_502_b_selectable);
											echo "<select name='dissertation_note1_b' required=''>";
											echo "<option value='' hidden>Choose</option>	";
											for ($x = 0; $x < sizeof($selectable_502_b); $x++) {
												echo "<option value='".$selectable_502_b[$x]."' ";
												if ((isset($_REQUEST['upd']) && $dissertation_note_b == $selectable_502_b[$x]) || (isset($_REQUEST['upd']) && $selectable_502_b[$x] == $tag_502_b_selectable_default)) {
													echo "selected";
												}
												echo ">".$selectable_502_b[$x]."</option>";
											} 
											echo "</select>";
										}
										else {
											echo "<input type=\"text\" name=\"dissertation_note1_b\" style=\"width:80%\" maxlength=\"50\"/>";
										}
									?>
								</td>
						</tr>	
						
						<?php if (!$hide_additional_authors_entry) {?>
						<tr>
						<td style='text-align:right;vertical-align:top;'><strong><?php echo $tag_700_simple;?></strong></td>							
						<td style='vertical-align:top;'>: <input type='text' name='pname1' value="<?php echo $pname1;?>" style="width:80%" maxlength='255'/>
						</td>						
						</tr>
						<tr id='a2'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname2' value="<?php echo $pname2;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a3'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname3' value="<?php echo $pname3;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a4'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname4' value="<?php echo $pname4;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a5'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname5' value="<?php echo $pname5;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a6'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname6' value="<?php echo $pname6;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a7'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname7' value="<?php echo $pname7;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a8'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname8' value="<?php echo $pname8;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a9'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname9' value="<?php echo $pname9;?>" style="width:80%" maxlength='255'/></td></tr>
						<tr id='a10'><td style='text-align:right;vertical-align:top;'></td><td style='vertical-align:top;'>: <input type='text' name='pname10' value="<?php echo $pname10;?>" style="width:80%" maxlength='255'/></td></tr>				
						<?php }?>
						
						<tr>
							<td style='text-align:right;vertical-align:top;'><strong>Full Text PDF <span style='color:red;'>(<?php echo "Max ".($system_allow_pfile_maxsize/1000000)." MB";?>)</span></strong></td>
							<td>: 						
								<?php if (isset($pfile) && $pfile == 'YES' && file_exists("../$system_pfile_directory/$oldyear/$id"."_".$oldtimestamp.".pdf")) {echo "<font color=blue>You have already submitted.  If you want to replace click </font>";}  else {echo "<font color=red>You have not submitted.</font>";}?> 
								<input type="file" id="pfile1" name="pfile1" size="38" accept="<?php echo dotFileTypes($system_allow_dfile_extension);?>"/>
							</td>
						</tr>

						<tr style='background-color:grey;'>
							<td colspan=2 style='text-align:left;vertical-align:top;color:white;'><strong><?php echo $depo_txt_optional_fields;?></strong></td>
						</tr>
						
						<?php if ($allow_confidential_setup) {?>	
						<tr>
						<td style='text-align:right;vertical-align:top;'><?php echo $tag_506_simple;?></td>
						<td style='vertical-align:top;'>: 
							<select name="accesscategory1">	
								<option value='open_access' <?php if (isset($accesscategory) && $accesscategory == 'open_access') {echo "selected";}?>>Tidak Terhad / Open Access</option>
								<option value='confidential' <?php if (isset($accesscategory) && $accesscategory == 'confidential') {echo "selected";}?>>Sulit / Confidential</option>
								<option value='restricted' <?php if (isset($accesscategory) && $accesscategory == 'restricted') {echo "selected";}?>>Terhad / Restricted</option>								
							</select>
							<div style='font-size:10px;'><?php echo $depo_confidentiality_remarks;?></div>
						</td>
						</tr>	
						<?php } else {echo "<input type='hidden' name='accesscategory1' value='open_access'>";}?>

						<?php if ($allow_declaration_submission) {?>	
						<tr>
							<td style='text-align:right;vertical-align:top;font-size:10px;'>
								<strong><?php echo $depo_txt_declaration_to_library;?> <span style='color:red;'>(<?php echo "Max ".($system_allow_dfile_maxsize/1000000)." MB";?>)</span></strong>
							</td>
							<td>: 								
								<?php if (isset($dfile) && $dfile == 'YES' && file_exists("../$system_dfile_directory/$oldyear/$id"."_".$oldtimestamp.".pdf")) {echo "<font color=blue>You have already submitted. If you want to replace click </font>";} else {echo "<font color=red>You have not submitted.</font>";}?> 
								<input type="file" id="dfile1" name="dfile1" size="38" accept="<?php echo dotFileTypes($system_allow_dfile_extension);?>"/> 
							</td>
						</tr>
						<?php }?>
						
						<?php if ($allow_abstract_submission) {?>
						<tr>
							<td style='text-align:center;vertical-align:top;' colspan=2><br/><br/>
							<strong>Abstract </strong>:</td>
						</tr>					
						<tr>
							<td style='vertical-align:top;' colspan=2>
							<textarea data-parsley-required="true" <?php if ($fulltext_abstract_composer_type == 'richtext') {echo "id='wadahComposer'";} ?> style="resize: none; width: 100%;" name="abstract1" cols="72" rows="30"><?php if (isset($abstract)) {echo $abstract;}?></textarea>
							</td>
						</tr>
						<?php }?>

						<tr style='background-color:grey;'>
							<td colspan=2 style='text-align:left;vertical-align:top;color:white;'><strong><?php echo $depo_acknowledgement;?></strong></td>
						</tr>

						<tr>
							<td style='vertical-align:top;' colspan=2>
								<div width=90% style='background-color:lightyellow;'>
									<?php echo $depo_para_acknowledgement;?>
									<br/>
									<input type="checkbox"  <?php if (isset($_GET['upd'])) {echo "checked";}?> data-parsley-required="true" id="agreed_box" name="agreed_box" value="agree"><span style='color:blue;'>I have read and agreed with all of above.</span>
									<br/><br/>
								</div>
							</td>
						</tr>					
						
						<tr><td colspan='2' style='text-align:center;vertical-align:top;'>
							<input type="hidden" name="inputby1" value="<?php echo $_SESSION['useridentity']; ?>" />
							<input type="hidden" name="instimestamp1" value="<?php echo time(); ?>" />
							<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
							<?php 	
							if (!isset($_REQUEST['upd']) || $_REQUEST['upd'] == '') 
								{?>							
									<input type="hidden" name="submitted" value="TRUE" />
									<br/><br/><input type="submit" name="submit_button" value="Submit" />													
							<?php 	
								}
							else if ($_REQUEST['upd'] != '') 
								{?>
									<input type="hidden" name="upd" value="<?php echo $id; ?>" />
									<input type="hidden" name="oldtimestamp" value="<?php echo $oldtimestamp;?>" />
									<input type="hidden" name="oldyear" value="<?php echo $oldyear;?>" />
									<input type="hidden" name="submitted" value="Update" />
									<br/><br/><input type="submit" name="submit_button" value="Update" />
						<?php 	}?>
							<br/><br/>	
						</td></tr>		
				</table>		
			</form>		
	<?php
		}//if submitted

		echo "<div style='text-align:center;margin-top:5px;'><a class='sButton' href='depositor.php''><span class='fas fa-arrow-circle-left'></span> Back to front page</a></div>";
	?>
	
	<hr>

	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>