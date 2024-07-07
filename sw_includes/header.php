<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
	
	$currentdir = substr(getcwd(), -8);//get the current working directory
	if ($currentdir == 'sw_admin' || $currentdir == 'sw_stats' || $currentdir == 'sw_depos' || $currentdir == 'sw_brows' || $currentdir == 'sw_tools') {$appendroot = '../';}
	else {$appendroot = '';}

	require_once $appendroot."sw_includes/mobiledetect.php";
	$detect = new Mobile_Detect;

	$refresh_component = time();
?>

<title>
	<?php 
		echo "$system_title : $thisPageTitle"; 
		if ($_SERVER["REMOTE_ADDR"] == $ezproxy_ip) {echo " [EZPROXY MODE]";}
	?>
</title>

<?php
	//handling cross side scripting if found any url ended with .php/[A-Z,0-9,specialchar] will change it to just .php
	//version 1.0.20220415.1547
	
	//for php8 == if (str_contains($_SERVER['REQUEST_URI'],'.php/'))
	if (strpos($_SERVER['REQUEST_URI'], '.php/') !== false)
	{
		$fixedURL = strstr($_SERVER['REQUEST_URI'], '.php/', true).".php" ?: $_SERVER['REQUEST_URI'];
		echo "<meta http-equiv='refresh' content=\"0;URL='$fixedURL'\" />";
		exit();		
	}

	if ($detect->isMobile())
		{echo "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1'>";}
?>

<link rel="icon" type="image/png" href="<?php echo $appendroot;?><?php echo $browser_icon;?>"/>
<link rel="stylesheet" type="text/css" href="<?php echo $appendroot;?>sw_styles/style.css<?php echo "?$refresh_component";?>"/>
<link rel="stylesheet" href="<?php echo $appendroot;?>sw_javascript/fontawesomejs/css/all.css<?php echo "?$refresh_component";?>">
<link rel="stylesheet" href="<?php echo $appendroot;?>sw_styles/jquery-ui.css<?php echo "?$refresh_component";?>">
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/jquery.min.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/jquery-ui.min.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/tiny_mce4/tinymce.min.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/parsley.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/swadah.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/jquery.qrcode.min.js<?php echo "?$refresh_component";?>"></script>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/calendarDateInput.js<?php echo "?$refresh_component";?>"></script>	

<?php 
	header("Strict-Transport-Security:$hd_Strict_Transport_Security");
	header("X-Frame-Options: $hd_X_Frame_Options");
	header("Referrer-Policy: $hd_Referrer_Policy");
	header("Content-Security-Policy: $hd_Content_Security_Policy");
	header("X-Content-Type-Options: $hd_X_Content_Type_Options");
	header("Permissions-Policy: $hd_Permissions_Policy");
?>