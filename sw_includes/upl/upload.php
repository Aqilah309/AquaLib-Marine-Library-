<?php
defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

if (!is_dir($affected_directory))
{
	mkdir($affected_directory,0777,true);
	file_put_contents("$affected_directory/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
	file_put_contents("$affected_directory/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");
}

$pathparts = pathinfo($_FILES[$affected_filefield]['name']);
$affected_fileextension = strtolower($pathparts["extension"]);

//if extension = jpeg, rename it to jpg
if ($affected_fileextension == 'jpeg') {
	$affected_fileextension = 'jpg';
}

//only certain extension will be allowed
if ($affected_fileextension == 'jpg' || $affected_fileextension == 'pdf' || $affected_fileextension == 'txt') {
	$proceedupload = 'TRUE';
}
else {
	$proceedupload = 'FALSE';
}

if($_FILES[$affected_filefield]['size'] > $targetted_filemaxsize) {
	$successupload = 'FALSE SIZE';
}
else
{
	$successupload = 'TRUE';

	if ($allow_parser_to_parse_internally)
	{
		if ($_FILES[$affected_filefield]['size'] > $max_allow_parser_to_work) {
			$successbutnotparse = 'TRUENOT';			
		}
		else {
			$successbutnotparse = "NOT";	
		}
	}
}

if ($successupload == 'TRUE' && $proceedupload == 'TRUE')
{
	if(is_uploaded_file($_FILES[$affected_filefield]['tmp_name']))
	{
		//if pdf
		if ($upload_type == "text") {
			move_uploaded_file($_FILES[$affected_filefield]['tmp_name'],$affected_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension);
		}
		
		//if jpg, jpeg --multiple images
		else if ($upload_type == "multiimage")
		{
			//upload the original image
			move_uploaded_file($_FILES[$affected_filefield]['tmp_name'],$affected_directory.'/'.$imagenumber.'.'.$affected_fileextension);

			//lets watermarked the image
			$original_File = $affected_directory.'/'.$imagenumber.'.'.$affected_fileextension;
			$watermarked_File = $affected_directory.'/'.$imagenumber.'_wm.'.$affected_fileextension;
			watermark_image($original_File,$watermark_overlay_file,$watermarked_File);//function already declared in upload_i.php
		}

		//else if jpg,jpeg
		else if ($upload_type == "image")
		{
			if (!is_dir($affected_watermark_directory))
			{
				mkdir($affected_watermark_directory,0777,true);
				file_put_contents("$affected_watermark_directory/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
				file_put_contents("$affected_watermark_directory/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");
			}
			
			//lets watermarked the image
			$original_File = $affected_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension;
			$watermarked_File = $affected_watermark_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension;
				//if original image successfully uploaded ..
				if (move_uploaded_file($_FILES[$affected_filefield]['tmp_name'],$original_File))
				{
					//..then watermark the image
					watermark_image($original_File,$watermark_overlay_file,$watermarked_File);
				}
			
			//make thumbnail from the watermarked image
			if (!is_dir($affected_thumbnail_directory))
			{
				mkdir($affected_thumbnail_directory,0777,true);
				file_put_contents("$affected_thumbnail_directory/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
				file_put_contents("$affected_thumbnail_directory/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");
			}

			$thumbnailed_target_File = $affected_thumbnail_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension;
			thumbnail_image($watermarked_File,$thumbnailed_target_File);	
		}		
		
		if ($parse_txt_file)
		{
			$text_output = file_get_contents($affected_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension);
			$text_output = addslashes(preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$text_output));
		}
	}
	echo "<span style='color:blue;'>$successful_upload_mesage</span>";
	
	if ($parse_txt_file) {
		mysqli_query($GLOBALS["conn"],"update eg_item set $targetted_field_to_update='".addslashes($text_output)."' where id=$idUpload");
	}
	else if (!$parse_txt_file && $parse_txt_file != null) {
		mysqli_query($GLOBALS["conn"],"update eg_item set $targetted_field_to_update='TRUE' where id=$idUpload");
	}
}
else if ($successupload == 'FALSE SIZE' && $proceedupload == 'TRUE') {
	echo "<span style='color:red;'>$incorrect_filesize_mesage</span>";
}
else {
	echo "<span style='color:red;'>$incorrect_filetype_mesage</span>";
}
	
?>