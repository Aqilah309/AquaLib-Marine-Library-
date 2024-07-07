<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php'; 			
?>

<html lang='en'>

<body>
<?php

	function depositorAcceptanceText($depo_para_acceptance_words,$titlestatement,$name,$useridentity,$approvedOn)
	{
		$depositorAcceptanceWording = str_replace("^^titlestatement",$titlestatement,$depo_para_acceptance_words);
		$depositorAcceptanceWording = str_replace("^^name",$name,$depositorAcceptanceWording );
		$depositorAcceptanceWording = str_replace("^^useridentity",$useridentity,$depositorAcceptanceWording);
		return str_replace("^^approvedOn",$approvedOn,$depositorAcceptanceWording );
	}

	if (is_numeric($_REQUEST['id'])) {
		$requestid = $_REQUEST['id'];
	}
	else {
		$requestid = 0;
	}
	
	$stmt_fdb = $new_conn->prepare("
	select 29titlestatement,timestamp, inputby from eg_item_depo 
	where id=? and (itemstatus='ACCEPTED' or itemstatus like 'ARCHIVE%')
	");
	$stmt_fdb->bind_param("i", $requestid);
	$stmt_fdb->execute();
	$stmt_fdb->store_result();
	$rowcnt_fdb = $stmt_fdb->num_rows;
	$stmt_fdb->bind_result($titlestatement,$approvedOn,$inputby_matrix);
	$stmt_fdb->fetch();				
	$stmt_fdb->close();

	if ($rowcnt_fdb >= 1)
	{
		$query_Name = "select fullname from eg_auth_depo where useridentity='$inputby_matrix'";
		$result_Name = mysqli_query($GLOBALS["conn"],$query_Name);
		$myrow_Name = mysqli_fetch_array($result_Name);
		$rowcnt_Name = $result_Name->num_rows;
			if ($rowcnt_Name >= 1) {$name = $myrow_Name["fullname"];}
		
		if ($approvedOn != '')
		{

?>
		<script src="../sw_javascript/jquery.min.js" type="text/javascript" ></script>
		<script src="../sw_javascript/jquery.qrcode.min.js" type="text/javascript"></script>
		<script>
			$(document).ready(function() {
				var strText = '<?php echo $system_path;?>sw_depos/depoacceptance.php?id=<?php echo $requestid;?>';
				jQuery('#selecteddiv').qrcode({
					text	: strText,width:128,height:128
				});	
			});
		</script> 

		<table style='width:100%;text-align:center;'>
			<tr><td>
				<img alt='Institution Image' src='../<?php echo $depo_image_institution;?>' width="250">

				<br/><br/>
				<strong><?php echo $depo_txt_slip_title;?></strong>

				<br/>
				<?php echo $depo_txt_institution;?>
				<table style='width:50%;margin-left:auto;margin-right:auto;'><tr><td><hr></td></tr></table>
			</td></tr>

			<tr><td>
				<?php echo depositorAcceptanceText($depo_para_acceptance_words,$titlestatement,$name,$inputby_matrix,date('d/m/Y',$approvedOn));?>
				<br/><br/>
			</td></tr>

			<tr><td>
				<?php echo $depo_validation; ?>
				<div style='text-align:center;' id='selecteddiv'></div>
				<br/><br/><?php echo $depo_para_autoremarks;?>
			</td></tr>
		</table>
<?php 
		}//if exist timestamp
	}//if request is valid
	
	else
	{
?>
	<table style='width:100%;text-align:center;'>
		<tr><td>
			<img alt='Institution Logo or Image' src='../<?php echo $depo_image_institution;?>' width="250">

			<br/><br/>
			<strong><?php echo $depo_txt_slip_title;?></strong>

			<br/>
			<?php echo $depo_txt_institution;?>
			<table style='width:50%;margin-left:auto;margin-right:auto;'><tr><td><hr></td></tr></table>
		</td></tr>

		<tr><td>
			<?php echo $depo_txt_notvalidslip;?>
		</td></tr>
	</table>
<?php
	}//else request is invalid
?>
</body>
</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>