<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Report Generator";

	//create cache directory if not exist
	if (!is_dir("../$system_statcache_directory"))
		{
			mkdir("../$system_statcache_directory",0755,true);
			file_put_contents("../$system_statcache_directory/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
			file_put_contents("../$system_statcache_directory/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");
		}
?>

<html lang='en'>
<head>
	<?php include '../sw_includes/header.php'; ?>
	<script>
	$(document).ready(function(){		
		$.ajax({
			url: 'load_toggle_1.php',
			success: function(data){				
				$("#toggle_1").html(data);
			}
		})
		$.ajax({
			url: 'load_toggle_2.php',
			success: function(data){				
				$("#toggle_2").html(data);
			}
		})
	});
	</script>
</head>

<body>
	<?php include '../sw_includes/loggedinfo.php'; ?>
					
	<hr>				

	<div style="text-align:center">
		
		<br/>
		Analytics:		
		 <?php if ($system_function == "full" || $system_function == "repo" || $system_function == "photo") {?>[<a class=thisonly href='adsreport.php?toggle=1'>Managers</a>]<?php }?>
		 <?php if ($system_function == "full" || $system_function == "repo" || $system_function == "photo") {?>[<a class=thisonly href='adsreport.php?toggle=2'>Access</a>]<?php }?>
		 <?php if ($system_function == "full" || $system_function == "repo" || $system_function == "photo") {?>[<a class=thisonly href='adsreport.php?toggle=3'>Search</a>]<?php }?>
		 <?php if ($system_function == "full" || $system_function == "repo") {?>[<a class=thisonly href='adsreport.php?toggle=4'>Bookmark</a>]<?php }?>
		 <?php if ($system_function == "full" || $system_function == "depo") {?>[<a class=thisonly href='adsreport.php?toggle=5'>Deposit</a>]<?php }?>
		<br/><br/>

			<?php if ((isset($_GET['toggle']) && $_GET['toggle'] == '1') || (!isset($_GET['toggle']))) {?>								
				<div id="toggle_1">
					<img alt='Loading..' style='margin-top:20px;margin-bottom:20px;' src='../sw_images/loading.gif' width=64><br/><span style='font-size:10px;'>Loading data. Please wait a while.</span><br/><br/>		
				</div>
			<?php }?>
		
			<?php if (isset($_GET['toggle']) && $_GET['toggle'] == '2') {?>
				<div id="toggle_2">
					<img alt='Loading..' style='margin-top:20px;margin-bottom:20px;' src='../sw_images/loading.gif' width=64><br/><span style='font-size:10px;'>Loading data. Please wait a while.</span><br/><br/>		
				</div>
			<?php }?>
		
			<?php if (isset($_GET['toggle']) && $_GET['toggle'] == '3') {?>
				<?php					
					if (file_exists("../$system_statcache_directory/total_hit_sum.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_hit_sum.txt");
						$diff = time() - $lines[1];
					}					
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUM = $lines[0];
						}					
						else
						{
							$querySUM = "select sum(37freq) as totalhitSUM from eg_userlog";
							$resultSUM = mysqli_query($GLOBALS["conn"],$querySUM);
							$myrowSUM = mysqli_fetch_array($resultSUM);
							$numSUM = $myrowSUM["totalhitSUM"];
							file_put_contents("../$system_statcache_directory/total_hit_sum.txt", $numSUM."\n".time());
						}						
				?>
				<table class=whiteHeaderNoCenter>								
					<tr class=yellowHeaderCenter><td colspan=2><strong>Search Statistic</strong></td></tr>
					<tr style='background-color:#EDF0F6;text-align:center'><td colspan=2>
						<br/>Total search hits (all time): <br/>
						<span style='font-size:22px;'><strong><?php echo "$numSUM";?></strong></span>
						<?php if ($report_count_generator == 'daily') {?><br/><em>Statistic generated date and time: <?php echo date('Y-m-d H:i:s',$lines[1]) ?><br/>Statistic is valid for that date and time. New statistic will be generated 24 hours later.</em><?php }?>
						<br/><br/>View Details By: <a href='adsreport_keyworddetails.php'>Search Terms</a> | <a href='adsreport_ipdetails.php'>IP Addresses</a><br/><br/>
					</td></tr>		
					<tr style='background-color:#FFFE96;text-align:center'><td colspan=2>Choose one of the following report generators:</td></tr>							
					<tr style='background-color:#EDF0F6;text-align:left'><td><form name="getMonthStat" action="adsreport.php?toggle=3" method=post>Search hits by month : 
					<br/><br/><select name='monthly'>
						<option value='01' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '01')) {echo 'selected';}?>>Jan</option>
						<option value='02' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '02')) {echo 'selected';}?>>Feb</option>
						<option value='03' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '03')) {echo 'selected';}?>>Mar</option>
						<option value='04' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '04')) {echo 'selected';}?>>Apr</option>
						<option value='05' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '05')) {echo 'selected';}?>>May</option>
						<option value='06' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '06')) {echo 'selected';}?>>June</option>
						<option value='07' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '07')) {echo 'selected';}?>>Jul</option>
						<option value='08' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '08')) {echo 'selected';}?>>Aug</option>
						<option value='09' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '09')) {echo 'selected';}?>>Sep</option>
						<option value='10' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '10')) {echo 'selected';}?>>Oct</option>
						<option value='11' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '11')) {echo 'selected';}?>>Nov</option>
						<option value='12' <?php if ((isset($_POST['monthly'])) && ($_POST['monthly'] == '12')) {echo 'selected';}?>>Dec</option>
					</select>
					and year :
					<input type="text" name="yearly" size="5" <?php if (isset($_POST['yearly']) && is_numeric($_POST['yearly'])) {echo 'value="'.$_POST['yearly'].'"';}?> maxlength="4"/>
					<br/><br/><input type="submit" value="Submit Query"></form></td>
					<td>
						<?php 
							if (isset($_POST['monthly']) && is_numeric($_POST['monthly']) && isset($_POST['yearly']) && is_numeric($_POST['yearly']))
								{
									$hitsSTRING = $_POST['monthly'].'/'.$_POST['yearly'];
									$querySTAT = "select count(id) as totalhitSTAT from eg_userlog_det where 38logdate like '%$hitsSTRING%'";
									$resultSTAT = mysqli_query($GLOBALS["conn"],$querySTAT);
									$myrowSTAT = mysqli_fetch_array($resultSTAT);
									$numSTAT = $myrowSTAT["totalhitSTAT"];
									echo "<a href='adsreport_hitsdetails.php?hitsDate=$hitsSTRING'>$numSTAT hits</a>";
								}
							else
								{echo '0 hits.';}
						?>
					</td></tr>

					<tr style='background-color:#EDF0F6;text-align:left'><td>Search hits by date : 
						<form name="getTarikh" action="adsreport.php?toggle=3" method="post">
							<script>DateInput('hitsDate', true, 'DD/MM/YYYY' <?php if (isset($_POST["hitsDate"])) {echo ",'".$_POST["hitsDate"]."'"; } ?>)</script>
							<input type="submit" value="Submit Query">
						</form>
					</td>
					<td>
					<?php
						if (isset($_POST['hitsDate']))
						{
							$hitsDate = $_POST['hitsDate'];
							
							$param = "%".$hitsDate."%";
							$stmt_count = $new_conn->prepare("select count(id) as totalhitSUMselect from eg_userlog_det where 38logdate like ?");
							$stmt_count->bind_param("s", $param);
							$stmt_count->execute();
							$stmt_count->bind_result($numSUMselect);
							$stmt_count->fetch();
							$stmt_count->close();
						
							if ($numSUMselect <> 0)
								{echo "<a href='adsreport_hitsdetails.php?hitsDate=$hitsDate'>$numSUMselect hits</a>";}
							else
								{echo '0 hits.<br/><br/>';}
						}
					?>
					</td></tr>					
				</table>
			<?php }?>
		
			<?php if (isset($_GET['toggle']) && $_GET['toggle'] == '4') {?>
				<?php		
					if (file_exists("../$system_statcache_directory/total_bookmark_sum.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_bookmark_sum.txt");
						$diff = time() - $lines[1];
					}
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUMl = $lines[0];
						}
						else
						{
							$querySUMl = "select count(id) as totalLoan from eg_item_charge";
							$resultSUMl = mysqli_query($GLOBALS["conn"],$querySUMl);
							$myrowSUMl = mysqli_fetch_array($resultSUMl);
							$numSUMl = $myrowSUMl["totalLoan"];
							file_put_contents("../$system_statcache_directory/total_bookmark_sum.txt", $numSUMl."\n".time());
						}	
				?>
				<table class=whiteHeader>								
					<tr class=yellowHeaderCenter><td colspan=2><strong>Bookmarks Statistic</strong></td></tr>
					<tr style='background-color:#EDF0F6;'><td colspan=2>
						<br/>Total items bookmarked (all time) : <br/>
						<span style='font-size:22px;'><strong><?php echo "$numSUMl";?></strong></span>
						<?php if ($report_count_generator == 'daily') {?><br/><em>Statistic generated date and time: <?php echo date('Y-m-d H:i:s',$lines[1]) ?><br/>Statistic is valid for that date and time. New statistic will be generated 24 hours later.</em><?php }?>
						<br/><br/>
					</td></tr>
					<tr style='background-color:#FFFE96;text-align:center'><td colspan=2>Choose one of the following report generators:</td></tr>				
					<tr style='background-color:#EDF0F6;text-align:left'>
						<td>
							<form name="getMonthStat" action="adsreport.php?toggle=4" method="post">Bookmarks by month : 
								<br/><br/><select name='lmonthly'>
									<option value='01' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '01')) {echo 'selected';}?>>Jan</option>
									<option value='02' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '02')) {echo 'selected';}?>>Feb</option>
									<option value='03' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '03')) {echo 'selected';}?>>Mar</option>
									<option value='04' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '04')) {echo 'selected';}?>>Apr</option>
									<option value='05' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '05')) {echo 'selected';}?>>May</option>
									<option value='06' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '06')) {echo 'selected';}?>>June</option>
									<option value='07' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '07')) {echo 'selected';}?>>Jul</option>
									<option value='08' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '08')) {echo 'selected';}?>>Aug</option>
									<option value='09' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '09')) {echo 'selected';}?>>Sep</option>
									<option value='10' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '10')) {echo 'selected';}?>>Oct</option>
									<option value='11' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '11')) {echo 'selected';}?>>Nov</option>
									<option value='12' <?php if ((isset($_POST['lmonthly'])) && ($_POST['lmonthly'] == '12')) {echo 'selected';}?>>Dec</option>
								</select>
								and year :
								<input type="text" name="lyearly" size="5" <?php if (isset($_POST['lyearly']) && is_numeric($_POST['lyearly'])) {echo 'value="'.$_POST['lyearly'].'"';}?> maxlength="4"/>
								<br/><br/><input type="submit" value="Submit Query">
							</form>
						</td>
						<td>
							<?php 
								if (isset($_POST['lmonthly']) && is_numeric($_POST['lmonthly']) && isset($_POST['lyearly']) && is_numeric($_POST['lyearly']))
									{
										$hitsSTRINGl = $_POST['lmonthly'].'/'.$_POST['lyearly'];
										
										$param = "%".$hitsSTRINGl."%";
										$stmt_count = $new_conn->prepare("select count(id) as totalhitSTAT from eg_item_charge where DATE_FORMAT(FROM_UNIXTIME(39charged_on), '%d/%m/%Y') like ?");
										$stmt_count->bind_param("s", $param);
										$stmt_count->execute();
										$stmt_count->bind_result($numSTATl);
										$stmt_count->fetch();
										$stmt_count->close();
										
										echo "<a href='adsreport_loandetails.php?hitsDate=$hitsSTRINGl'>$numSTATl transactions</a>";
									}
								else
									{echo '0 transactions.';}
							?>
						</td>
					</tr>

					<tr style='background-color:#EDF0F6;text-align:left'><td>Bookmarks by date :
						<form name="getTarikhL" action="adsreport.php?toggle=4" method="post">
							<script>DateInput('hitsDateL', true, 'DD/MM/YYYY' <?php if (isset($_POST['hitsDateL'])) {echo ",'".$_POST['hitsDateL']."'"; } ?>)</script>
							<input type="submit" value="Submit Query">
						</form>
					</td>
					<td>
					<?php
						if (isset($_POST['hitsDateL']))
						{
							$hitsDateL = $_POST['hitsDateL'];
							
							$param = "%".$hitsDateL."%";
							$stmt_count = $new_conn->prepare("select count(id) as totalhitSUMselect from eg_item_charge where DATE_FORMAT(FROM_UNIXTIME(39charged_on), '%d/%m/%Y') like ?");
							$stmt_count->bind_param("s", $param);
							$stmt_count->execute();
							$stmt_count->bind_result($numSUMselectL);
							$stmt_count->fetch();
							$stmt_count->close();
						
							if ($numSUMselectL <> 0)
								{echo "<a href='adsreport_loandetails.php?hitsDate=$hitsDateL'>$numSUMselectL transactions</a>";}
							else
								{echo '0 transactions.<br/><br/>';}
						}
					?>
					</td></tr>					
				</table>
			<?php }?>

			<?php if (isset($_GET['toggle']) && $_GET['toggle'] == '5') {?>
				<?php		
					if (file_exists("../$system_statcache_directory/total_deposit_all.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_deposit_all.txt");
						$diff = time() - $lines[1];
					}
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUMl = $lines[0];
						}
						else
						{
							$querySUMl = "select count(id) as totaldeposit from eg_item_depo";
							$resultSUMl = mysqli_query($GLOBALS["conn"],$querySUMl);
							$myrowSUMl = mysqli_fetch_array($resultSUMl);
							$numSUMl = $myrowSUMl["totaldeposit"];
							file_put_contents("../$system_statcache_directory/total_deposit_all.txt", $numSUMl."\n".time());
						}	

					if (file_exists("../$system_statcache_directory/total_deposit_accepted.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_deposit_accepted.txt");
						$diff = time() - $lines[1];
					}
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUMla = $lines[0];
						}
						else
						{
							$querySUMla = "select count(id) as totaldeposit from eg_item_depo where itemstatus like 'ACC%'";
							$resultSUMla = mysqli_query($GLOBALS["conn"],$querySUMla);
							$myrowSUMla = mysqli_fetch_array($resultSUMla);
							$numSUMla = $myrowSUMla["totaldeposit"];
							file_put_contents("../$system_statcache_directory/total_deposit_accepted.txt", $numSUMla."\n".time());
						}	
					
					if (file_exists("../$system_statcache_directory/total_deposit_rejected.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_deposit_rejected.txt");
						$diff = time() - $lines[1];
					}
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUMlb = $lines[0];
						}
						else
						{
							$querySUMlb = "select count(id) as totaldeposit from eg_item_depo where itemstatus like 'R_%'";
							$resultSUMlb = mysqli_query($GLOBALS["conn"],$querySUMlb);
							$myrowSUMlb = mysqli_fetch_array($resultSUMlb);
							$numSUMlb = $myrowSUMlb["totaldeposit"];
							file_put_contents("../$system_statcache_directory/total_deposit_rejected.txt", $numSUMlb."\n".time());
						}	

					if (file_exists("../$system_statcache_directory/total_deposit_archived.txt") && $report_count_generator == 'daily')
					{
						$lines = file("../$system_statcache_directory/total_deposit_archived.txt");
						$diff = time() - $lines[1];
					}
						if ((isset($diff) && $diff < 86400)  && $report_count_generator == 'daily')
						{
							$numSUMlr = $lines[0];
						}
						else
						{
							$querySUMlr = "select count(id) as totaldeposit from eg_item_depo where itemstatus like 'ARC%'";
							$resultSUMlr = mysqli_query($GLOBALS["conn"],$querySUMlr);
							$myrowSUMlr = mysqli_fetch_array($resultSUMlr);
							$numSUMlr = $myrowSUMlr["totaldeposit"];
							file_put_contents("../$system_statcache_directory/total_deposit_archived.txt", $numSUMlr."\n".time());
						}
				?>
				<table class=whiteHeader>								
					<tr class=yellowHeaderCenter><td colspan=4><strong>Deposit Statistic (all time)</strong></td></tr>
					<tr style='background-color:#EDF0F6;'>
						<td>
							Total deposit : 
							<br/><span style='font-size:22px;'><strong><?php echo "$numSUMl";?></strong></span>
						</td>

						<td>
							Total deposit (<span style='color:green;'>ACCEPTED</span>) : 
							<br/><span style='font-size:22px;'><strong><?php echo "$numSUMla";?></strong></span>
						</td>

						<td>
							Total deposit (<span style='color:red;'>REJECTED</span>) : 
							<br/><span style='font-size:22px;'><strong><?php echo "$numSUMlb";?></strong></span>
						</td>

						<td>
							Total deposit (<span style='color:blue;'>ARCHIVED</span>) : 
							<br/><span style='font-size:22px;'><strong><?php echo "$numSUMlr";?></strong></span>
						</td>
					</tr>					
					<?php if ($report_count_generator == 'daily') {?><tr style='background-color:#EDF0F6;'><td colspan=4><em>Statistic generated date and time: <?php echo date('Y-m-d H:i:s',$lines[1]) ?><br/>Statistic is valid for that date and time. New statistic will be generated 24 hours later.</em></td></tr><?php }?>
				</table>
				<table class=whiteHeader>	
					<tr style='background-color:#FFFE96;text-align:center'><td colspan=2>Choose one of the following report generators:</td></tr>				
					<tr style='background-color:#EDF0F6;text-align:left'>
						<td>
							<form name="getMonthStat" action="adsreport.php?toggle=5" method="post">Deposit by month : 
								<br/><br/><select name='dlmonthly'>
									<option value='01' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '01')) {echo 'selected';}?>>Jan</option>
									<option value='02' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '02')) {echo 'selected';}?>>Feb</option>
									<option value='03' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '03')) {echo 'selected';}?>>Mar</option>
									<option value='04' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '04')) {echo 'selected';}?>>Apr</option>
									<option value='05' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '05')) {echo 'selected';}?>>May</option>
									<option value='06' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '06')) {echo 'selected';}?>>June</option>
									<option value='07' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '07')) {echo 'selected';}?>>Jul</option>
									<option value='08' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '08')) {echo 'selected';}?>>Aug</option>
									<option value='09' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '09')) {echo 'selected';}?>>Sep</option>
									<option value='10' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '10')) {echo 'selected';}?>>Oct</option>
									<option value='11' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '11')) {echo 'selected';}?>>Nov</option>
									<option value='12' <?php if ((isset($_POST['dlmonthly'])) && ($_POST['dlmonthly'] == '12')) {echo 'selected';}?>>Dec</option>
								</select>
								and year :
								<input type="text" name="dlyearly" size="5" <?php if (isset($_POST['dlyearly']) && is_numeric($_POST['dlyearly'])) {echo 'value="'.$_POST['dlyearly'].'"';}?> maxlength="4"/>
								<br/><br/><input type="submit" value="Submit Query">
							</form>
						</td>
						<td>
							<?php 
								if ((isset($_POST['dlmonthly'])) && (isset($_POST['dlyearly'])))
									{
										$hitsSTRINGl = $_POST['dlmonthly'].'/'.$_POST['dlyearly'];
										
										$param = "%".$hitsSTRINGl."%";
										$stmt_count = $new_conn->prepare("select count(id) as totalhitSTAT from eg_item_depo where DATE_FORMAT(FROM_UNIXTIME(timestamp), '%d/%m/%Y') like ?");
										$stmt_count->bind_param("s", $param);
										$stmt_count->execute();
										$stmt_count->bind_result($numSTATl);
										$stmt_count->fetch();
										$stmt_count->close();
										
										echo "<a href='adsreport_depodetails.php?hitsDate=$hitsSTRINGl'>$numSTATl transactions</a>";
									}
								else
									{echo '0 transactions.';}
							?>
						</td>
					</tr>	
				</table>
			<?php }?>
	</div>
					
	<hr>
	
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>