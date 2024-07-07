<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

	if(is_file("$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5/$currentimage_num.jpg")) 
	{
		echo "<td style='font-size:8pt;display: inline-block; padding: 5px; width:150px;'>
				<img class='centered-and-cropped' src='$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5/$currentimage_num.jpg?=".time()."' width=128px height=128px onerror=this.src='sw_images/no_image.png'>
				<br/>
				[<a onclick='return openPopup(this.href,800,680);' target='_blank' href='$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5/$currentimage_num.jpg'>$currentimage_num.jpg</a>]";
				if (is_file("$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5/$currentimage_num"."_wm.jpg")) {
					echo " [<a onclick='return openPopup(this.href,800,680);' target='_blank' href='$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5/$currentimage_num"."_wm.jpg'>Watermarked</a>]";
				}
				echo " [<a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' href='sw_includes/del_inst.php?defi=$id5&pic=$currentimage_num'>Delete</a>]";
		echo "</td>";
	}
?>