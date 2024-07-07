<?php 
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");   
?>
<script type="text/javascript" src="<?php echo $appendroot;?>sw_javascript/wz_tooltip.js"></script>
<?php 
	if (isset($_SESSION['username']))
	{
		include $appendroot.'sw_includes/navbar.php';  
	}
	else
	{
		include $appendroot.'sw_includes/navbar_guest.php'; 
	}
?>