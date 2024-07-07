<?php
	
	function formatBytes($size, $precision = 2)
	{
		$base = log($size, 1024);
		$suffixes = array('', 'K', 'M', 'G', 'T');   

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}

	function showDevelopedRight()
	{
		return base64_decode("RGV2ZWxvcGVkIGJ5IFBlcnB1c3Rha2FhbiBUdWFua3UgQmFpbnVuLCBVbml2ZXJzaXRpIFBlbmRpZGlrYW4gU3VsdGFuIElkcmlzLg==");
	}

	function check_is_blocked($dirtowrite,$up)
	{
		//this function will check whether if the ip exist 3 times in a row for the current date of ip blocked list.
		//the ip will only get daily ban. will clear the next day.
		if ($GLOBALS["invalid_access_detection"] == 'strict') {$blockcount = 1;}
		else if ($GLOBALS["invalid_access_detection"] == 'precaution') {$blockcount = 5;}
		else {$blockcount = 99;}

		$ip = getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');

		if (is_file($dirtowrite.date("Ymd").".txt"))
		{
			$handle = fopen($dirtowrite.date("Ymd").".txt", "r");
			if ($handle)
			{
				$n=0;
				while (($buffer = fgets($handle)) !== false) {
					if (strpos($buffer, $ip) !== false) {
						$n=$n+1;
					}      
					if ($n>=$blockcount)
					{
						echo "<script>alert('Your session have been blocked. Illegal operation detected.');window.location.replace('$up"."index.php');</script>";
						mysqli_close($GLOBALS["conn"]); fclose($handle); exit();
					}
				}
				fclose($handle);
			}
		}
	}

	function checkstring_Exist_And_redirect_to_parent($thefullstring,$word_to_check,$page_to_redirect)
	{
		if (strpos($thefullstring,$word_to_check) !== false) 
		{
			echo "<script>window.location.replace('$page_to_redirect');</script>";
		}
	}
	
	function checkPHPExtension($extensionToCheck)
	{
		if(extension_loaded($extensionToCheck)) {return "";}
		else 
		{
			return "<table style=\"padding-top:1px;width:100%;text-align:center;\">	
			<tr style=\"background-color:red;color:white;\"><td style=\"color:white;\">
				PHP extension <u>$extensionToCheck</u> not loaded. Please check your php.ini setting.This extension is mandatory for sWADAH.
			</td></tr>
			</table>";
		}
	}
	
	function setImgDownload($imagePath) {           
		$image = imagecreatefromjpeg($imagePath);
		header('Content-Type: image/jpeg');
		imagejpeg($image);
	}
	
	function createModalPopupMenu ($menuID,$menuTitle,$menuDialog)
	{
		echo "
			<script>
				\$(function() {\$('#$menuID').click(function(e) {e.preventDefault();\$('#$menuID-confirm').dialog('open');});				
				$( '#$menuID-confirm' ).dialog({
					resizable: false,height:160,modal: true,minWidth: 350,autoOpen:false,
					buttons: {'OK': function() {\$( this ).dialog( 'close' );}}
					});
				});
			</script>
			<style>
				.ui-button.ui-corner-all.ui-widget.ui-button-icon-only.ui-dialog-titlebar-close {display: none;}
			</style>			
			<div id='$menuID-confirm' title='$menuTitle' style='display:none;'>
				<p>
					<span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>$menuDialog
				</p>
			</div>
		";
	}

	function createModalPopupMenuAuto ($menuID,$menuTitle,$menuDialog)
	{
		echo "
			<script>
				\$(function() {\$('#$menuID').click(function(e) {e.preventDefault();\$('#$menuID-confirm').dialog('open');});				
				$( '#$menuID-confirm' ).dialog({
					resizable: false,height:160,modal: true,minWidth: 350,autoOpen:false,
					buttons: {'OK': function() {\$( this ).dialog( 'close' );}}
					});
				});
			</script>
			<style>
				.ui-button.ui-corner-all.ui-widget.ui-button-icon-only.ui-dialog-titlebar-close {display: none;}
			</style>			
			<div id='$menuID-confirm' title='$menuTitle' style='display:none;'>
				<p>
					<span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>$menuDialog
				</p>
			</div>
			<script>
				$(document).ready(function(){
					$('#$menuID-confirm').dialog('open');
				});
			</script>
		";
	}
	
	function getTitle($accessnum)
	{	
		$stmt = $GLOBALS["new_conn"]->prepare("select 38title from eg_item where 38accessnum=?");
		$stmt->bind_param("s", $accessnum);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getDischargeStatus($username,$accessnum)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select 40dc from eg_item_charge where 39patron=? and 38accessnum=?");
		$stmt->bind_param("ss",$username,$accessnum);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getUserInfo($username,$system_helpdesk_contact)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select name,username,division,lastlogin from eg_auth where username=?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($name,$username,$division,$lastlogin);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		$text = "Logged as : <span style='color:green;'>$name</span>";
		$text .= "<br/>Identification ID : $username";
		$text .= "<br/>From : $division";
		$text .= "<br/>Last login : $lastlogin";
		$text .= "<br/><br/><em>To change any of these information, kindly contact: $system_helpdesk_contact</em>";
		return $text;

	}

	function getUserType($patron_id)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select usertype from eg_auth where username=?");
		$stmt->bind_param("s",$patron_id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}
		
	function isPatronEligibility($patron_id)
	{
		$queryT = "select count(39patron) as countStillLoan from eg_item_charge where 39patron='$patron_id' and 40dc!='DC'";
		$resultT = mysqli_query($GLOBALS["conn"],$queryT);
		$myrowT = mysqli_fetch_array($resultT);
		
		$queryS = "select max_loanitem from eg_auth_eligibility where usertype='".getUserType($patron_id)."'";
		$resultS = mysqli_query($GLOBALS["conn"],$queryS);
		$myrowS = mysqli_fetch_array($resultS);
		
		if ($myrowT["countStillLoan"] < $myrowS["max_loanitem"])
			{return "TRUE";}
		else
			{return "FALSE";}		
	}

	function namePatron($pid)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select name from eg_auth where id=?");
		$stmt->bind_param("i",$pid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getFullNameFromUserIdentity($useridentity)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select fullname from eg_auth_depo where useridentity=?");
		$stmt->bind_param("s",$useridentity);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getEmailPhoneFromUserIdentity($useridentity)
	{	
		$stmt = $GLOBALS["new_conn"]->prepare("select emailaddress,phonenum from eg_auth_depo where useridentity=?");
		$stmt->bind_param("s",$useridentity);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($emailaddress,$phonenum);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return "$emailaddress / $phonenum";

	}

	function namePatronfromUsername($username)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select name from eg_auth where username=?");
		$stmt->bind_param("s",$username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function patronIdToUsername($pid)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select username from eg_auth where id=?");
		$stmt->bind_param("i",$pid);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function accessnumToID($accessnum)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select id from eg_item where 38accessnum=?");
		$stmt->bind_param("s",$accessnum);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getTypeNameFromID($id)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select 38type from eg_item_type where 38typeid=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function getDownloadHitsPerItemID($id)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select count(id) as downloadhits from eg_item_download where eg_item_id=?");
		$stmt->bind_param("i",$id);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	function countFeedbackForItemForUser($id,$username)
	{
		$stmt = $GLOBALS["new_conn"]->prepare("select count(id) as thiscount from eg_item_feedback where eg_item_id=? and eg_auth_username=?");
		$stmt->bind_param("is",$id,$username);
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($returnvalue);//bind result from select statement
		$stmt->fetch();
		$stmt->close();

		return $returnvalue;
	}

	//below function will use microtime as accession number
	function millitime() {
		$microtime = microtime();
		$comps = explode(' ', $microtime);
		return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
	}

	function getBaseUrl() 
	{
		// output: /myproject/index.php
		$currentPath = $_SERVER['PHP_SELF']; 

		// output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
		$pathInfo = pathinfo($currentPath); 

		// output: localhost
		$hostName = $_SERVER['HTTP_HOST']; 

		// output: http://
		$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
		
		// return: http://localhost/myproject/
		$finalreturn = $protocol.$hostName.$pathInfo['dirname'];
		if (substr($finalreturn, -1) == '/') {$finalreturn = substr_replace($finalreturn, "", -1);}
		return "$finalreturn/";
	}

	function stringUltraClean($str)
	{
		//remove all <script></script> tags inside the content and also html entity decode,addslashes the string
		//for use with most text field
		
		return htmlspecialchars(preg_replace('#<script(.*?)>(.*?)</script>#is', '', html_entity_decode(addslashes($str), ENT_QUOTES, "UTF-8")));
	}

	function reverseStringUltraClean($str)
	{
		//to counter the when stringUltraClean is used when inputted into the database
		return stripslashes($str);
	}

	function just_clean($string,$cleanType = 'max')
	{
		//this is the ultimate sanitizing function
		//current version: 1.0.20220121

		$specialCharacters = array(
		'#' => '',
		'$' => '',
		'%' => '',
		'&' => '',
		'@' => '',
		'.' => '',
		'�' => '',
		'+' => '',
		'=' => '',
		'\\' => '',
		'/' => '',
		);

		foreach($specialCharacters as $character => $replacement) {
			$string = str_replace($character, '' . $replacement . '', $string);
		}

		// Remove all remaining other unknown characters
		if ($cleanType == 'max') {$string = preg_replace('/[^a-zA-Z0-9\-]/', ' ', $string);}
		$string = preg_replace('/^[\-]+/', ' ', $string);
		$string = preg_replace('/[\-]+$/', ' ', $string);
		$string = preg_replace('/[\-]{2,}/', ' ', $string);

		//remove all html related tags
		$string = strip_tags($string);

		//trim right and left for whitespaces, and any multiple whitespaces in between
		return trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', htmlspecialchars($string)));
	}

	function setDefaultForPostVar($fieldVar)
	{
		if (!isset($fieldVar))
			{return '';}
		else 
			{return stringUltraClean(mysqli_real_escape_string($GLOBALS["conn"],$fieldVar));}
	}

	function getSubjectHeadingNames($subject_heading_delimiter,$subjectheading)
	{
		$subjectheadings = explode($subject_heading_delimiter, $subjectheading);
		$total = count($subjectheadings) - 1;
		$i = 1;
		$returnSH = "";
		foreach($subjectheadings as $subjectheading) 
		{		
			$subjectheading = mysqli_real_escape_string($GLOBALS["conn"],trim($subjectheading));
			$query_subject = "select 43subject from eg_subjectheading where 43acronym = '$subjectheading'";
			$result_subject = mysqli_query($GLOBALS["conn"],$query_subject);
			$num_results_affected = mysqli_num_rows($result_subject);
			
			if ($num_results_affected >= 1)
				{
					$myrow_subject = mysqli_fetch_array($result_subject);	
					if (isset($myrow_subject["43subject"])) {$returnSH .= $myrow_subject["43subject"];}
				}
			else
				{$returnSH .= "";}
			if ($i < $total)
				{$returnSH .= "<br/>";}
			$i=$i+1;
		}	
		return $returnSH;
	}

	function highlight($text, $words) {    
		$words = trim($words);
		$wordsArray = explode(' ', $words);
		foreach($wordsArray as $word) {			
			if(strlen(trim($word)) > 2)
			{	
				$word = just_clean($word);//remove unwanted special characters, replace it with nothing
				if ($word != '')
					{
						$hlstart = '<span style="background-color:yellow;">';
						$hlend = '</span>';
						$text = preg_replace("/$word/i", $hlstart.'\\0'.$hlend, $text);//php7
					}
			}
		}
		return $text;
	} 

	function getmicrotime()
	{
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	} 

	function timetaken($timelog)
	{
		$now_array = explode(' ',date("D d/m/Y h:i a"));
		$lasttime_array = explode(' ',$timelog);				
		
		$now_ampm = explode(':',$now_array[2]);
		if ($now_array[3] == 'pm' && $now_ampm[0] <> 12)
			{
				$now_ampm[0] = $now_ampm[0]+12;					
			}

		$now_array[2]=$now_ampm[0].":".$now_ampm[1];
	
		$lasttime_ampm = explode(':',$lasttime_array[2]);
		if ($lasttime_array[3] == 'pm' && $lasttime_ampm[0] <> 12)
			{							
				$lasttime_ampm[0] = $lasttime_ampm[0]+12;					
			}

		$lasttime_array[2]=$lasttime_ampm[0].":".$lasttime_ampm[1];
													
		$now_days = explode('/',$now_array[1]);
		$now_days = implode('-',$now_days);					
		$lasttime_days = explode('/',$lasttime_array[1]);
		$lasttime_days = implode('-',$lasttime_days);
		
		$now = strtotime(date($now_days." ".$now_array[2]));
		$lasttime = strtotime($lasttime_days." ".$lasttime_array[2]);
		
		$dateDiff = $now-$lasttime;
		$fullDays = floor($dateDiff/(60*60*24));
		$fullHours = floor(($dateDiff-($fullDays*60*60*24))/(60*60));
		$fullMinutes = floor(($dateDiff-($fullDays*60*60*24)-($fullHours*60*60))/60);

		return "$fullDays"."d, $fullHours"."h, $fullMinutes"."m";
	}

	function sendEmail($pathway,$mel_subject,$mel_body,$mel_address,$mel_success,$mel_failed)
	{
		
		if ($GLOBALS["useEmailNotification"])
		{			
			require "$pathway/vendor/autoload.php";
			$mail = new PHPMailer\PHPMailer\PHPMailer(false);
			$mail->isSMTP();                                    			// Send using SMTP
			$mail->SMTPDebug=$GLOBALS["emailDebuggerEnable"];				// Enable verbose debug output 0=disable 1=enable
			$mail->Host=$GLOBALS["emailHost"];                    			// Set the SMTP server to send through
			$mail->SMTPAuth=$GLOBALS["emailAuthentication"];           		// Enable SMTP authentication
			$mail->Username=$GLOBALS["emailUserName"];                		// SMTP username
			$mail->Password=$GLOBALS["emailPassword"];                		// SMTP password
			$mail->SMTPSecure=$GLOBALS["emailMode"];  						// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
			$mail->SMTPAutoTLS=$GLOBALS["emailAutoTLS"];					// AutoTLS setting.
			$mail->Port=$GLOBALS["emailPort"];                    			// TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
			$mail->setFrom($GLOBALS["emailSetFrom"],$GLOBALS["emailSetFromName"]);
			$mail->addAddress($mel_address);
			$mail->isHTML(true);
			$mail->Subject=$mel_subject;
			$mail->Body=$mel_body;
			if(!$mail->Send())
				{echo $mel_failed;}
			else					
				{echo $mel_success;}				
		}		
	}

	function getDepoStatus($itemstatus)
	{
		if ($itemstatus == "ENTRY") {$status = "Pending Approval";}
		else if ($itemstatus == "ACCEPTED") {$status = "Accepted";}
		else if ($itemstatus == "ARCHIVEDP") {$status = "Live in Repository";}
		else if ($itemstatus == "ARCHIVEDL") {$status = "Archived for Limited Access";}
		else if ($itemstatus == "R_INCOMPLETE") {$status = "Rejected: Incomplete";}
		else if ($itemstatus == "R_DUPLICATE") {$status = "Rejected: Duplicate Entry";}
		else if ($itemstatus == "R_CONTACT") {$status = "Rejected: Contact Admin";}
		else if ($itemstatus == "UPD_METADATA") {$status = "Resubmission: Metadata Changes";}
		else if ($itemstatus == "UPD_SUBMISSION") {$status = "Resubmission: Digital File";}
		else {$status = "Unknown Status";}

		return $status;
	}
	
	//use preg_match to get page counts
	function getPDFPages($appendroot,$document)
	{
		unset($appendroot);
		$pdf = file_get_contents($document);
		return preg_match_all("/\/Page\W/", $pdf, $dummy);
	}

	//use smalot function instead to get page counts
	function getPDFPages2($appendroot,$document)
	{
		include $appendroot."vendor/autoload.php";
		try {
			$parser = new \Smalot\PdfParser\Parser();
			$pdf = $parser->parseFile($document);	
			$metaData = $pdf->getDetails();	
			
			$pdfTotalPages = $metaData['Pages'];
		}
		catch (Exception $e) {
			$pdfTotalPages= 0;			
		};
		return $pdfTotalPages;
	}

	function dotFileTypes($filetypes)
	{
		$separated = preg_split ("/\,/", $filetypes);
		$str = "";
		foreach ($separated as $result) {
			$str .= ".".$result.","; 
		}
		return rtrim($str,",");
	}

	function quotesFileTypes($filetypes)
	{
		$separated = preg_split ("/\,/", $filetypes);
		$str = "";
		foreach ($separated as $result) {
			$str .= "'".$result."',"; 
		}
		return rtrim($str,",");
	}

	function watermark_image($target, $wtrmrk_file, $newcopy) 
	{
		$orientation = '';
		$watermark = imagecreatefrompng("../$wtrmrk_file");
		imagealphablending($watermark, false);
		imagesavealpha($watermark, true);
		
		$exif = exif_read_data($target);
		if($exif && isset($exif['Orientation']))
			{$orientation = $exif['Orientation'];}
		$img = imagecreatefromjpeg($target);
		
		if($orientation != 1)
		{
			switch ($orientation) {
				case 3:
				$deg = 180;
				break;
				case 6:
				$deg = 270;
				break;
				case 8:
				$deg = 90;
				break;
				default:
				$deg = 0;
			}			  
			if ($deg)
				{$img = imagerotate($img, $deg, 0);}
		}
		
		$img_w = imagesx($img);
		$img_h = imagesy($img);
		$wtrmrk_w = imagesx($watermark);
		$wtrmrk_h = imagesy($watermark);
		$dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
		$dst_y = ($img_h / 2) - ($wtrmrk_h / 2); // For centering the watermark on any image
		imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);		
		imagejpeg($img, $newcopy, 100);		
		imagedestroy($img);
		imagedestroy($watermark);
	}

	function thumbnail_image($watermarked_File,$thumbnailed_target_File)
	{
		$percent = 0.5; // percentage of resize
		list($width, $height) = getimagesize($watermarked_File);
		$new_width = $width * $percent;
		$new_height = $height * $percent;
		$imagethumbnail_p = imagecreatetruecolor($new_width, $new_height);// Resample
		$imagethumbnail = imagecreatefromjpeg($watermarked_File);
		imagecopyresampled($imagethumbnail_p, $imagethumbnail, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($imagethumbnail_p, $thumbnailed_target_File, 100);	
	}

	function record_block($dirtowrite)
	{
		if (!is_dir($dirtowrite))
		{
			mkdir($dirtowrite,0777,true);
			file_put_contents("$dirtowrite"."index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");		
		}

		if (!is_file("$dirtowrite".date("Ymd").".txt"))
			{file_put_contents($dirtowrite.date("Ymd").".txt","Captured on: ".date("Ymd"));}
			
		$ip = getenv('HTTP_CLIENT_IP')?:
		getenv('HTTP_X_FORWARDED_FOR')?:
		getenv('HTTP_X_FORWARDED')?:
		getenv('HTTP_FORWARDED_FOR')?:
		getenv('HTTP_FORWARDED')?:
		getenv('REMOTE_ADDR');
		
		$writefile = fopen($dirtowrite.date("Ymd").".txt", "a");
		fwrite($writefile, "\r\n". $ip);
		fclose($writefile);

		echo "<script>alert('Illegal query. Incident has been recorded.');window.location.replace('searcher.php?sc=cl');</script>";
		mysqli_close($GLOBALS["conn"]); exit();
	}

	//create new field based on table row for new item insert on reg.php
	/*
	0 $mandatory = either true (if this field is needed) or false (not needed)
	1 $tag_show = check if tag is set to show or not usually either true or false
	2 $viewmode = either marc or simple, will control if the row will be printed out or not
	3 $tag_name = tag name with full description. refer to config.php
	4 $tag_simple_name = simple tag name. refer to config.php
	5 $tag_alternative_name = when viewmode is on simple view, pipe will be represented with an alternative name instead
	6 $require_leftsection = description and indicators or not: true or false
	7 $indicator_name = field name for indicator input box
	8 $indicator_default = default value for indicator input box
	9 $textfield_name = field name for text field for inputting desciptor
	10 $textfield_defaultvalue = default value for text field above
	11 $pipe_selection = what pipe this field going to be
	12,13 $textfield_width,$textfield_maxlength = controls text field width and maxlength
	14 is_upd = pass if user use the form as update form (true) otherwise it will be as insert new (false)
	15 db_field_value = inserted database field value for err.. a field
	16 db_indicator_value = inserted database field value for indicator
	17 field_type = default is 'text', and can change it to 'textarea'
	18 $extrabit = you can inject additional html control. it will be beside the textfield
	*/
		function regRowGenerate(
		$mandatory,
		$tag_show,
		$viewmode,
		$tag_name,
		$tag_simple_name,
		$tag_alternative_name,
		$require_leftsection,
		$indicator_name,
		$indicator_default,
		$textfield_name,
		$textfield_defaultvalue,
		$pipe_selection,
		$textfield_width,
		$textfield_maxlength,
			$is_upd = false,
			$db_field_value = "",
			$db_indicator_value = "",
			$fieldtype = "text",
			$extrabit = "")
		{
			if ($tag_show)
			{
				if ($is_upd) {
					$indicator_value = $db_indicator_value;
					$textfield_value = $db_field_value;
				}
				else {
					$indicator_value = $indicator_default;
					$textfield_value = $textfield_defaultvalue;
				}

				if ($mandatory) {
					$required_field = 'required';
				}
				else {
					$required_field = '';
				}
				
				echo "<tr>";
					echo "<td style='text-align:right;vertical-align:top;'><strong>";
						if ($require_leftsection)
						{
							if ($viewmode == 'marc')
							{
								echo $tag_name;
								echo " <input type='text' name='$indicator_name' value='$indicator_value' size=3 maxlength=2/>"; 
							}
							else{ echo $tag_simple_name;}
						}
					echo "</strong></td>";
					echo "<td style='vertical-align:top;'> ";
						if ($viewmode == 'marc') {
							echo ": <span style='color:green;'>$pipe_selection</span>";
						}
						else {
							if ($tag_alternative_name != '') {echo " $tag_alternative_name<br/>: ";}
							else {echo ": ";}
						}
						if ($fieldtype == "textarea")
							{
								if ($viewmode == 'marc') {$marginleftwidth = '15pt';} else {$marginleftwidth = '7pt';}
								echo "<br/><textarea name='$textfield_name' cols='40' rows='5' style='margin-left:$marginleftwidth;width:$textfield_width'>$textfield_value</textarea>";
							}
						else
							{echo "<input type='text' id='$textfield_name' name='$textfield_name' style='width:$textfield_width' value='$textfield_value' maxlength='$textfield_maxlength' $required_field/> $extrabit";}
					echo "</td>";
				echo "</tr>\n\n";
			}
		}
		
		//create new field based on table row for new item insert on reg.php (repetable with 1st item mandatory)
		/*
		1 $tag_show = check if tag is set to show or not usually either true or false
		2 $totalinput = how many variable you want
		3 $viewmode = either marc or simple, will control if the row will be printed out or not
		4 $tag_name = tag name with full description. refer to config.php
		5 $tag_simple_name = simple tag name. refer to config.php
		6 $indicator_name = field name for indicator input box
		7 $indicator_default = default value for indicator input box
		8 $pipe_selection = what pipe this field going to be
		9 $textfield_name = field name for text field for inputting desciptor
		10 is_upd = pass if user use the form as update form (true) otherwise it will be as insert new (false)
		11 db_field_value = inserted database field value for err.. a field
		12 db_indicator_value = inserted database field value for indicator
		*/
		function regRowGenerateRepeats(
			$tag_show,
			$totalinput,
			$viewmode,
			$tag_name,
			$tag_simple_name,
			$indicator_name,
			$indicator_default,
			$pipe_selection,
			$textfield_name,
			$is_upd = false,
			$db_field_value = "",
			$db_indicator_value = "") 
		{							
			if ($tag_show)
			{
				if ($is_upd) {
					$indicator_value = $db_indicator_value;
					$textfield_value = $db_field_value;
				}
				else {
					$indicator_value = $indicator_default;
					$textfield_value = "";
				}

				echo "<tr>";
					echo "<td style='text-align:right;vertical-align:top;'><strong>";
						if ($viewmode == 'marc')
						{
							echo $tag_name;
							echo " <input type='text' name='$indicator_name' value='$indicator_value' size=3 maxlength=2/>"; 
						}
						else {echo $tag_simple_name;}										
					echo "</strong></td>";
					echo "<td style='vertical-align:top;'> ";
						if ($viewmode == 'marc') {echo ": <span style='color:green;'>$pipe_selection</span>";}
						else {echo ": ";}										
						echo "<input type='text' name='$textfield_name"."1"."' style='width:80%' maxlength='255' value='$textfield_value' /> ";
						if ($GLOBALS["$textfield_name"."2"] == '') 
							{echo "<a id='al1' style='font-size:8pt;' onclick=\"document.getElementById('a2').style.display='';document.getElementById('al1').style.display='none';\">[+]</a>";}
					echo "</td>";
				echo "</tr>\n\n";
											
				for ($x=2;$x<=$totalinput;$x++) {
					if ($is_upd) {
						$indicator_value = $GLOBALS["$indicator_name"."_".($x+0)];
						$textfield_value = $GLOBALS["$textfield_name".($x+0)];
						if ($x<$totalinput) {$textfield_value_next = $GLOBALS["$textfield_name".($x+1)];}
							else {$textfield_value_next = "";}
					}
					else {
						$indicator_value = $indicator_default;
						$textfield_value = "";
						$textfield_value_next = "";
					}
					
					if ($textfield_value != '') {$showthis = "show";}
					else {$showthis = "none";}

					echo "
						<tr id='a".($x+0)."' style='display:$showthis'>";
						echo "<td style='text-align:right;vertical-align:top;'>";											
							if ($viewmode == 'marc') 
							{
								echo $tag_name;	
								echo " <input type='text' name='".$indicator_name."_".($x+0)."' value='$indicator_value' size=3 maxlength=2/>";
							}
							else {echo $tag_simple_name;}
						echo "</td>";
						echo "<td style='vertical-align:top;'>: ";
							if ($viewmode == 'marc') 
							{
								echo "<span style='color:green;'>$pipe_selection</span>";
							}
							echo "<input type='text' name='".$textfield_name.($x+0)."' value='$textfield_value' style='width:80%' maxlength='255'/> ";
							if ($x <> $totalinput && $textfield_value_next == '') {
								echo "<a id='al".($x+0)."' style='font-size:8pt;' onclick=\"document.getElementById('a".($x+1)."').style.display='';document.getElementById('al".($x+0)."').style.display='none';\">[+]</a>";
							}
						echo "</td>";
						echo "</tr>\n\n
					";
				}
			}
		}	

		//create new selecction: select box based on table row for new item insert on reg.php
		/*
		1 $viewmode = either marc or simple, will control if the row will be printed out or not
		2 $tag_name = tag name with full description. refer to config.php
		3 $tag_simple_name = simple tag name. refer to config.php
		4 $indicator_name = field name for indicator input box
		5 $indicator_default = default value for indicator input box
		6 $predefine_selectableonconfig = defined on config.php all the list involved in building this select box. see $tag_041_selectable for example
		7 $default_selection = default selection for the select box. value must present at config.php per item no.6 above
		8 $fieldname = name for the input text field
		9 $fieldtype = what type it is either select or all the other(free text)
		10 $require_leftsection = description and indicators or not: true or false
		11 is_upd = pass if user use the form as update form (true) otherwise it will be as insert new (false)
		12 db_field_value = inserted database field value for err.. a field
		13 db_indicator_value = inserted database field value for indicator
		*/
		function regRowGenerateSelectBox(
			$tag_show,
			$viewmode,
			$tag_name,
			$tag_simple_name,
			$indicator_name,
			$indicator_default,
			$predefine_selectableonconfig,
			$default_selection,
			$fieldname,
			$fieldtype,
			$require_leftsection = false,
			$is_upd = false,
			$db_field_value = "",
			$db_indicator_value = "")
		{
			if ($tag_show)
			{
				if ($is_upd) {
					$indicator_value = $db_indicator_value;
					$textfield_value = $db_field_value;
				}
				else {
					$indicator_value = $indicator_default;
					$textfield_value = "";
				}

				echo "<tr><td style='text-align:right;vertical-align:top;'><strong>";
				if ($require_leftsection)	
				{
					if ($viewmode == 'marc') 
						{
							echo $tag_name;
							echo " <input type='text' name='$indicator_name' size=3 maxlength=2 value='$indicator_value' />";
						}
						else 
							{echo $tag_simple_name;}
				}
				else 
					{echo $tag_simple_name;}
				echo "</strong></td>";
				echo "<td style='vertical-align:top;'>: ";
				if ($viewmode == 'marc') {
					echo "<span style='color:green;'>$indicator_default</span>";
				}

				if ($fieldtype == "select")
				{
					$selectable = explode("|",$predefine_selectableonconfig);
					echo "<select name='$fieldname'>";
					for ($x = 0; $x < sizeof($selectable); $x++) {
						echo "<option value='".$selectable[$x]."' "; 
							if ($is_upd && $textfield_value == $selectable[$x]) {echo "selected";}
							else if (!$is_upd && $selectable[$x] == $default_selection) {echo "selected ";}
						echo ">".$selectable[$x]."</option>";
					} 
					echo "</select>";
				}
				else
					{echo "<input type='text' name='$fieldname' style='width:80%' maxlength='50'/>";}
				echo "</td></tr>\n\n";
			}
		}

		//file upload box extension checker for reg.php --must be put at <head>
		function generateFileBoxAllowedExtensionRule($fileFieldName, $allowedExtensionList)
		{
			echo "
			$(function() {
				$('#$fileFieldName').change(function() {
					var fileExtension = ["; echo quotesFileTypes($allowedExtensionList); echo "];
					if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
						alert('Only "; echo dotFileTypes($allowedExtensionList); echo " formats are allowed.');
						$fileFieldName.value = '';
					}
				})
			})\n\n
			";
		}
		
		//generate file upload box for reg.php
		function regRowGenerateFileUploadBox($whatThisFor,$fileMaxSize,$filefieldName,$fileAllowedExtension,$is_upd = false,$fileLocation = null)
		{
			echo "
			<tr>
				<td style='text-align:right;vertical-align:top;'><strong>$whatThisFor <span style='color:red;'>(Max ".($fileMaxSize/1000000)." MB)</span></strong></td>
				<td>: 
				<input type='file' id='$filefieldName' name='$filefieldName' size='38' accept='"; echo dotFileTypes($fileAllowedExtension); echo "' />";
				if ($is_upd && $fileLocation != null && is_file($fileLocation))
				{
					echo "[<a target='_blank' href='$fileLocation'>Existing File</a>]";
				}
				echo "</td>
			</tr>\n\n
			";
		}
?>