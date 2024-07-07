<?php
defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

//Where you want the files to upload to - Important: Make sure this folder Apache rewritable
if (!is_dir("../$filedirectory/$dir_year"))
{
	mkdir("../$filedirectory/$dir_year",0777,true);		
	file_put_contents("../$filedirectory/$dir_year/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
	file_put_contents("../$filedirectory/$dir_year/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");
}

$uploaddir = "../$filedirectory/$dir_year";		
$pathparts = pathinfo($_FILES[$thisfile]['name']);
$affected_fileextension = strtolower($pathparts["extension"]);
$filesize = $_FILES[$thisfile]['size'];


//only certain extension will be allowed
if ($affected_fileextension == 'pdf') {$proceedupload = 'TRUE';}
else {$proceedupload = 'FALSE';}

if($filesize > $filemaxsize) {
	$successupload = 'FALSE SIZE';
}
else {
	$successupload = 'TRUE';
}

if ($successupload == 'TRUE' && $proceedupload == 'TRUE')
{											
	if (file_exists("$uploaddir/$idUpload"."_$timestampUpload.pdf")) {
		unlink("$uploaddir/$idUpload"."_$timestampUpload.pdf");
	}

	if(is_uploaded_file($_FILES[$thisfile]['tmp_name'])) {
		move_uploaded_file($_FILES[$thisfile]['tmp_name'],$uploaddir.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension);
	}

	echo "<span style='color:blue;'>$filesuccessfulupload</span>";

	//update status in database
	mysqli_query($GLOBALS["conn"],"update eg_item_depo set $filefield='YES', itemstatus='$set_itemstatus' where id=$idUpload");
}
else if ($successupload == 'FALSE SIZE' && $proceedupload == 'TRUE') {
	echo "<span style='color:red;'>$filesizeerror</span>";
}
else {
	echo "<span style='color:red;'$filetypeerror</span>";
}
	
?>