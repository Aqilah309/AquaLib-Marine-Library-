<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include 'core.php'; 	
	include 'sw_includes/functions.php';	
	include 'sw_includes/function_createdir.php';	
	$thisPageTitle  = "Main Page";

	//log out active user whenever they arrived at this page
	if (isset($_SESSION['username_guest']) && (isset($_GET['log']) && $_GET['log'] == 'out'))
	{
		$tempusername_guest = $_SESSION['username_guest'];
		mysqli_query($GLOBALS["conn"],"update eg_auth set online='OFF' where username='$tempusername_guest'");
		
		//clear search sessions
		unset($_SESSION['sear_scstr']);
		unset($_SESSION['sear_sctype']);
		unset($_SESSION['sear_page']);
		unset($_SESSION['appendurl']);
		unset($_SESSION['appendfilter']);

		//clear routes
		unset($_SESSION['route1']);
		unset($_SESSION['route2']);
		unset($_SESSION['route3']);
		unset($_SESSION['needtochangepwd']);
		
		//clear user session
		unset($_SESSION['username_guest']); 

		//clear special route for admin to bypass ip restriction
		unset($_SESSION['m']); 
	}
	else if (isset($_SESSION['username_guest']))
	{
		header("Location: index2.php");
		die();
	}
	
	//if username is set and log is defined
	if (isset($_SESSION['username']) && isset($_GET['log']))
	{
		$tempusername = $_SESSION['username'];
		
		if ($_GET['log'] == 'out') {
			mysqli_query($GLOBALS["conn"],"update eg_auth set online='OFF' where username='$tempusername'");
		}
		else if ($_GET['log'] == 'block') {
			mysqli_query($GLOBALS["conn"],"update eg_auth set num_attempt=$default_num_attempt_login where username='$tempusername'");
			echo "<script>alert('Illegal operation. You have been blocked.');</script>";
		}
		
		unset($_SESSION['username']); 
		unset($_SESSION['editmode']);
		unset($_SESSION['lastlogin']);
		unset($_SESSION['ref']);
		unset($_SESSION['viewmode']);
		unset($_SESSION['validSession']);
		session_destroy();
	}
	else if (isset($_SESSION['username']))
	{
		header("Location: index2.php");
		die();
	}
	
	//handling submitted directive
	if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'Enter Searcher')
		{echo "<script>document.location.href='searcher.php?sc=cl'</script>";}
	else if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'My Account Portal')
		{echo "<script>document.location.href='usrlogin.php'</script>";}
	else if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'Depositer')
		{echo "<script>document.location.href='sw_depos/depologin.php'</script>";}
	else if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'Tutorial')
		{echo "<script>document.location.href='$tutorial_link'</script>";}
	
?>

<html lang='en'>

<head>
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>

	<table style="padding-top:30px;" class=transparentCenter100percent>		
		<tr>
			<td>
				<img alt='Main Logo' width=<?php echo $main_logo_width;?> src="./<?php echo $main_logo;?>">
				<br/><?php echo $intro_words;?>
				<br/><br/>
				<?php if ($system_mode != 'maintenance') 
				{
					if ($searcher_type_bar_visibility)
					{
				?>
					<form  action="searcher.php" method="get" enctype="multipart/form-data" style="margin:auto;max-width:100%">							
						<input id="roundInputText" type="text" placeholder="Enter terms and press enter" name="scstr" style='width:60%;font-size:14px' maxlength="255" autofocus/>
						<input type="hidden" name="sctype" value="EveryThing" />
					</form>
					<br/><?php if ($show_browser_bar_guest) {include './sw_includes/browser_bar.php';} ?>	
					<br/>
				<?php 
					}
				}
				else {
					echo "<h2 style='color:red;'>System is currently under maintenance. Will be right back !</h2>";
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<form action="index.php" method="post">
						<?php 
						if ($system_mode != 'maintenance' && $allow_user_to_login) 
						{?>
							<button class="unstyled-button" type="submit" name="submitted" value='My Account Portal'>
								<img alt='My Account Button' src='./sw_images/myaccountbutton.png' width=120>
							</button>
						<?php 
						}?>
						<?php
						if ($system_mode != 'maintenance' && $enable_user_deposit_button && ($system_function == 'depo' || $system_function == 'full'))
						{
						?>
							<button class="unstyled-button" type="submit" name="submitted" value='Depositer'>
									<img alt='Depositor Button' src='./sw_images/depositorbutton.png' width=120>
							</button>
						<?php
						}
						?>
				</form>
			</td>
		</tr>		
	</table>

	<br/>
	
	<?php 
		include './sw_includes/footer.php';
		include './sw_includes/meta.php';
	?>

	<?php
	$ip = getenv('HTTP_CLIENT_IP')?:
	getenv('HTTP_X_FORWARDED_FOR')?:
	getenv('HTTP_X_FORWARDED')?:
	getenv('HTTP_FORWARDED_FOR')?:
	getenv('HTTP_FORWARDED')?:
	getenv('REMOTE_ADDR');
	
	echo "<br/><br/>";
	echo checkPHPExtension('gd');
	echo checkPHPExtension('exif');
	echo checkPHPExtension('mbstring');
	echo checkPHPExtension('bz2');
	echo checkPHPExtension('curl');
	echo checkPHPExtension('fileinfo');
	echo checkPHPExtension('gettext');

	if ($show_client_ip_on_startup) {
		echo "<br/><div style='width:100%;text-align:center;'>Recorded IP: $ip</div>";
	}
	?>

	
</body>	

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>