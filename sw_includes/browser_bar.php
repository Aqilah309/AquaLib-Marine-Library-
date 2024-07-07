<?php defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>"); ?>
<table style='width:100%'>
	<tr><td style='height:29;text-align:center;'>Browse by: 
		<?php if ($show_subject_browser_bar) {?><a style='margin-right:8px;' href='sw_brows/subjectbrowser.php'><?php echo $subject_heading_as;?></a> <?php }?>
		<?php if ($show_publisher_browser_bar) {?> <a style='margin-right:8px;' href='sw_brows/publisherbrowser.php'><?php echo $publisher_as;?></a> <?php }?>
		<?php if ($show_year_browser_bar) {?> <a style='margin-right:8px;' href='sw_brows/yearbrowser.php'>Year</a><?php }?>
	</td></tr>
</table>