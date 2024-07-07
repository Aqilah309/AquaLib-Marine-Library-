<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

	if ($currentDisplay_image == '2') 
		{${"displayT".$currentDisplay_image} = "";} 
	else 
		{${"displayT".$currentDisplay_image} = "none";} 
?>
<tr id='t<?php echo $currentDisplay_image;?>' style="display:<?php echo ${"displayT".$currentDisplay_image};?>">
	<td style='text-align:right;vertical-align:top;'></td>
	<td>: 
		<input type="file" id="<?php echo $currentInput_image;?>" name="<?php echo $currentInput_image;?>" size="38" accept="<?php echo dotFileTypes($system_allow_imageatt_extension);?>"/>
		<?php if ($requirenext)  {?>
			<a id="tl<?php echo $currentDisplay_image;?>" style="font-size:8pt;" onclick="document.getElementById('t<?php echo $currentDisplay_image+1;?>').style.display='';document.getElementById('tl<?php echo $currentDisplay_image;?>').style.display='none';">[+]</a>
		<?php }?>
	</td>
</tr>