<?php 
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");   

	if ($system_mode != 'maintenance') 
	{
		if ($GLOBALS["enable_oai_pmh"]) {createModalPopupMenu ('oai_click','OAI PMH v2.0 Status',"OAI PMH v2.0 is enabled for this repository: <a href='$system_path"."oai2.php'>Click Here</a>");}
		if ($GLOBALS["enable_searcher_api"]) {createModalPopupMenu ('api_click','Searcher API Status',"Searcher API is enabled for this repository: <a href='$system_path"."searcherapi.php'>Click Here</a>");}
	?>
		<br/>
		<div style='text-align:center;font-size:8pt;'>
			<?php if ($show_admin_login_link || $GLOBALS["enable_oai_pmh"] || $GLOBALS["enable_searcher_api"]) {echo "Meta:";}?>
			<?php 
				if ($show_admin_login_link) {echo "[<a href='in.php'>Administration</a>]";}
				if ($GLOBALS["enable_oai_pmh"]) {echo "  [<a href='#' id='oai_click'>OAI</a>]";}
				if ($GLOBALS["enable_searcher_api"]) {echo "  [<a href='#' id='api_click'>API</a>]";}
			?>
		</div>
	<?php
	}
?>