<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include 'core.php';
	include 'sw_includes/access_ip.php';
	include 'sw_includes/functions.php';
	$thisPageTitle = "Item Details";

	check_is_blocked("$blocked_file_location/","");

	//php7+ only ternary operator utilization
	$get_id_det = !is_numeric($_REQUEST["det"]) ? 0 : $_REQUEST["det"];

	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
	
	if ($num_results_affected >= 1)
	{
		$id5 = $myrow_item["id"];
		$status5 = $myrow_item["38status"];
		if ($searcher_marker_to_hide == null) {$titlestatement5 = reverseStringUltraClean($myrow_item["38title"]);}	else {$titlestatement5 = reverseStringUltraClean(str_replace($searcher_marker_to_hide, "", $myrow_item["38title"]));}
		$typestatement5 = $myrow_item["38typeid"];
		$location5 = $myrow_item["38location"];
		$link5 = $myrow_item["38link"];
		$authorname5 = reverseStringUltraClean($myrow_item["38author"]);
		$callnumber5 = $myrow_item["38localcallnum"];
		$inputdate5 = $myrow_item["39inputdate"];
			$dir_year5 = substr("$inputdate5",0,4);
		$fulltext5 = $myrow_item["41fulltexta"];
		$reference5 = $myrow_item["41reference"];
		$isabstract5 = $myrow_item["41isabstract"];
		$pdfattach5 = $myrow_item["41pdfattach"];
		$instimestamp5 = $myrow_item["41instimestamp"];
		$subjectheading5 = $myrow_item["41subjectheading"];
		$imageatt5 = $myrow_item["41imageatt"];			
		$accessnum5 = $myrow_item["38accessnum"];
		$isbn5 = $myrow_item["38isbn"];
		$issn5 = $myrow_item["38issn"];			
		$edition5 = $myrow_item["38edition"];
		$publication5 = $myrow_item["38publication"];
		$physicaldesc5 = $myrow_item["38physicaldesc"];
		$series5 = $myrow_item["38series"];
		$notes5 = $myrow_item["38notes"];			
		$source5 = $myrow_item["38source"];
		$ppdfattach5 = $myrow_item["41ppdfattach"];	
		$item_status5 = $myrow_item['50item_status'];

		$query_item_ex = "select * from eg_item2 where eg_item_id=$id5";
		$result_item_ex = mysqli_query($GLOBALS["conn"],$query_item_ex);
		$myrow_item_ex = mysqli_fetch_array($result_item_ex);		
			$titlestatement5_b = $myrow_item_ex["38title_b"] ?? '';
			$titlestatement5_c = $myrow_item_ex["38title_c"] ?? '';
			$publication5_b = $myrow_item_ex["38publication_b"] ?? '';
			$publication5_c = $myrow_item_ex["38publication_c"] ?? '';
			$additional_authors = $myrow_item_ex["38pname1"] ?? '';
				if (isset($myrow_item_ex["38pname2"]) && $myrow_item_ex["38pname2"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname2"];}
				if (isset($myrow_item_ex["38pname3"]) && $myrow_item_ex["38pname3"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname3"];}
				if (isset($myrow_item_ex["38pname4"]) && $myrow_item_ex["38pname4"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname4"];}
				if (isset($myrow_item_ex["38pname5"]) && $myrow_item_ex["38pname5"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname5"];}
				if (isset($myrow_item_ex["38pname6"]) && $myrow_item_ex["38pname6"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname6"];}
				if (isset($myrow_item_ex["38pname7"]) && $myrow_item_ex["38pname7"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname7"];}
				if (isset($myrow_item_ex["38pname8"]) && $myrow_item_ex["38pname8"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname8"];}
				if (isset($myrow_item_ex["38pname9"]) && $myrow_item_ex["38pname9"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname9"];}
				if (isset($myrow_item_ex["38pname10"]) && $myrow_item_ex["38pname10"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname10"];}
			$vtitle5_a	= $myrow_item_ex["38vtitle_a"] ?? '';
			$vtitle5_b	= $myrow_item_ex["38vtitle_b"] ?? '';
			$vtitle5_g	= $myrow_item_ex["38vtitle_g"] ?? '';
			$contenttype5_a = $myrow_item_ex["38contenttype_a"] ?? '';
			$contenttype5_2 = $myrow_item_ex["38contenttype_2"] ?? '';
			$mediatype5_a = $myrow_item_ex["38mediatype_a"] ?? '';
			$mediatype5_2 = $myrow_item_ex["38mediatype_2"] ?? '';
			$carriertype5_a = $myrow_item_ex["38carriertype_a"] ?? '';
			$carriertype5_2 = $myrow_item_ex["38carriertype_2"] ?? '';
			$summary5_a = $myrow_item_ex["38summary_a"] ?? '';
			$se_pname5_a = $myrow_item_ex["38se_pname_a"] ?? '';
			$se_pname5_x = $myrow_item_ex["38se_pname_x"] ?? '';
			$se_pname5_y = $myrow_item_ex["38se_pname_y"] ?? '';
		
		//get hits count and update it on the main table (eg_item) every now and then this record get view by users
		if (isset($id5) && is_numeric($id5))
		{
			$query_access = "select count(eg_item_access.id) as totalid from eg_item_access,eg_item where eg_item_access.eg_item_id=eg_item.id and eg_item_access.eg_item_id='$id5'";
			$result_access = mysqli_query($GLOBALS["conn"],$query_access);
			$myrow_access = mysqli_fetch_array($result_access);					
			$inputhits = $myrow_access["totalid"] + 1;
			mysqli_query($GLOBALS["conn"],"update eg_item set 41hits=$inputhits where id=$id5");

			$datePattern = date("D d/m/Y h:i a");						
			$ip = $_SERVER['REMOTE_ADDR'];	
			mysqli_query($GLOBALS["conn"],"insert into eg_item_access values(DEFAULT,$get_id_det,'".session_id()."','$datePattern','$ip')"); //records detailed access ip for this record	
		}	

		//generate downloadkey -start
		if(empty($_SERVER['REQUEST_URI'])) {$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];}								
		$url = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);			
		$folderpath = $system_path;
		$key = uniqid(md5(rand()));		
		$ip_address = $_SERVER["REMOTE_ADDR"];	
		$time = date('U');

		//enable access to partial text/guest 
		$pdocs_file = "sw_admin/temp/no_permission.pdf";//default
		if ($allow_guest_access_to_pt) 
			{$pdocs_file = "$system_pdocs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf";}
		
		//enable access to full text
		$docs_file = "sw_admin/temp/no_permission.pdf";//default
		if ((($allow_guest_access_to_ft || isset($_SESSION['username_guest'])) && $status5 == 'AVAILABLE') || $_SERVER["REMOTE_ADDR"] == $ezproxy_ip) 
			{$docs_file = "$system_docs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf";}

		$albums_file = "$system_albums_watermark_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg";		
		
		$registerid = mysqli_query($GLOBALS["conn"],"
									INSERT INTO eg_downloadkey (uniqueid,eg_item_id,ip_address,timestamped,pdocs,docs,albums) 
									VALUES('$key','$id5','$ip_address','$time','$pdocs_file','$docs_file','$albums_file')
									");
	}
	else
		{$item_status5 = '0';}

	//preventing CSRF
	include 'sw_includes/token_validate.php';
?>

<html lang='en'>

<?php 
if ($item_status5 == '0' || $num_results_affected == '0') 
{?>
		<head><?php include 'sw_includes/header.php'; ?></head>
		<body>
			<?php include 'sw_includes/navbar_guest.php'; ?>
			<div style='text-align:center'>
				<hr>
				<h3>Sorry. This item is not available.</h3>
				<?php
					if (!isset($_GET['h'])) {echo "<a class='sButton' href='searcher.php'><span class='fas fa-arrow-circle-left'></span> Back to searcher</a>";}
				?>	
				<br/>		
			</div>
			<?php 
				echo "<hr>"; include 'sw_includes/footer.php';		
			?>
		</body>
<?php 
} //if $item_status5 == 0 

else if ($item_status5 == '1') 
{
?>
	<head>
		<meta name="description" content="<?php echo $system_title;?> Item Detail: <?php echo $titlestatement5; ?>" />
		<meta name="citation_title" content="<?php echo "$titlestatement5 $titlestatement5_b $titlestatement5_c"; ?>">
		<meta name="citation_author" content="<?php echo $authorname5; ?>">
		<meta name="citation_publication_date" content="<?php echo $dir_year5; ?>">
		<meta name="citation_journal_title" content="<?php echo ''; ?>">
		<meta name="citation_publisher" content="<?php echo $source5; ?>">
		<meta name="citation_volume" content="<?php echo ''; ?>">
		<meta name="citation_issue" content="<?php echo ''; ?>">
		<meta name="citation_firstpage" content="<?php echo '';?>">
		<meta name="citation_lastpage" content="<?php echo '';?>">
		<meta name="citation_abstract_html_url" content="<?php echo getBaseUrl().'detailsg.php?det='.$id5; ?>">
		<?php if(is_file($pdocs_file)) { ?><meta name="citation_pdf_url" content="<?php echo getBaseUrl().'detailsg.php?det='.$id5; ?>"><?php }?>
		
		<?php include 'sw_includes/header.php'; ?>

		<script>
			$(document).ready(function() {
				var strText = '<?php echo $system_path;?>detailsg.php?det=<?php echo $id5;?>&h=1';
				jQuery('#selecteddiv').qrcode({
					text	: strText,width:128,height:128
				});	
			});
		</script> 
	</head>

	<body>
		
		<?php include 'sw_includes/navbar_guest.php'; ?>
		
		<hr>	

		<div style='text-align:center'>
			<?php		
				
				$get_highlight = $_GET["highlight"] ?? '';
								
				//bookmarking handling -start
				if ((isset($_GET['bk']) && $_GET['bk'] == 'yes') && isset($_SESSION["username_guest"]))
				{
					$query_charge = "select 40dc from eg_item_charge where 39patron='".$_SESSION['username_guest']."' and 38accessnum='$accessnum5'";
					$result_charge = mysqli_query($GLOBALS["conn"],$query_charge);
					$myrow_charge = mysqli_fetch_array($result_charge);		
					$num_results_charge = mysqli_num_rows($result_charge);	
															
					$status_charge = ''; if ($num_results_charge >= 1) {$status_charge = $myrow_charge["40dc"];}	
										
					if ($status_charge == 'DC') {
						mysqli_query($GLOBALS["conn"],"update eg_item_charge 
															set 39charged_on=".time().", 40dc='NO', 40dc_on='', 40dc_by='' 
															where 39patron = '".$_SESSION['username_guest']."' and 38accessnum='$accessnum5'");
					}
					else {
						mysqli_query($GLOBALS["conn"],"insert into eg_item_charge values(
																DEFAULT,
																'$accessnum5',
																'".$_SESSION['username_guest']."',
																'".time()."',
																'".$_SESSION['username_guest']."',
																0,
																'NO',
																'',
																''
																)");
					}
				}		
				
				if (isset ($_SESSION["username_guest"]))
				{	
					echo "<table class=whiteHeader><tr class=whiteHeaderCenter><td>";										
							$query_chargeid = "select id from eg_item_charge where 39patron = '".$_SESSION['username_guest']."' and 38accessnum='$accessnum5' and 40dc='NO'";
							$result_chargeid = mysqli_query($GLOBALS["conn"],$query_chargeid);
							if (mysqli_num_rows($result_chargeid) >= 1)
							{
								echo "<span style='color:red;'>Bookmarked.</span><br/>";	
							}
							else 
							{
								if (isPatronEligibility($_SESSION['username_guest']) == 'FALSE')
									{echo "<span style='color:magenta;'>You have reached maximum bookmark items.</span>";}
								else
									{echo "[<a href='detailsg.php?det=$get_id_det&bk=yes'>Bookmark this item</a>]";}
							}				
					echo "</td></tr></table>";
				}
				//bookmarking handling -end
				
				//if swadah works as photo mode, then show main image above
				if ($system_function == 'photo' && is_file("$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg"))
				{
					echo "
						<div style='background-color:white;'>
							<img id='expandedImg' style='width:70%;' src='sw_tools/image.php?d=$id5&t=w''>
							<span id='imgtext'></span>
						</div>							
						<script>
						function imageClickFunction(imgs) {
							var expandImg = document.getElementById(\"expandedImg\");
							expandImg.src = imgs.src;
							expandImg.parentElement.style.display = \"block\";
							imgtext.innerHTML = \"<br/><br/><img src='sw_images/magnifying.png' style='width:24px;'><a style='font-size:12pt;' target='_blank' href='\"+imgs.src+\"'>Click here to enlarge</a><br/><br/><br/>\";
							window.scrollTo(0, 0);	
						}
						</script>	
					";							
						echo "<div style='background-color:black;display:flex;overflow-x: scroll;'>";
						$otherPhotoExist = false;
						$otherPhotos = "<div style='float:left;width:250px;padding:5px;margin: 0 auto;'><img class='centered-and-cropped' style='width:150px;height:150px;' src='sw_tools/image.php?d=$id5&t=w' onerror=this.src='sw_images/no_image.png' onclick='imageClickFunction(this);'></div>";
						for ($x = 2; $x <= 20; $x++)
						{
							if(is_file("$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5"."/$x"."_wm.jpg")) 
								{
									$otherPhotos .= "<div style='float:left;width:250px;padding:5px;margin: 0 auto;'><img class='centered-and-cropped' style='width:150px;height:150px;' src='sw_tools/image.php?d=$id5&t=$x' onerror=this.src='sw_images/no_image.png' onclick='imageClickFunction(this);'></div>"; 
									$otherPhotoExist = true;
								}	
						}
						if ($otherPhotoExist) {echo $otherPhotos;}			
						echo "</div>";							
				}
				
				//the main content of this file, shows all meta data for the selected item
				echo "<table class=whiteHeaderNoCenter style='border: 1px solid lightgrey; max-width:100%;overflow-x: auto;' width=100%>";	
					echo "<tr>";
					
					echo "<td style='display: inline-block; padding: 5px;vertical-align:top;' width=350>";
						echo "<table border=0 class=whiteHeaderNoCenter width=100%>";										

							if ($show_qr_code_for_item)
								{echo "<tr><td colspan=2 style='text-align:center;vertical-align:top;'><strong>QR Code Link :</strong><br/><br/><div style='text-align:center;' id='selecteddiv'></div></td></tr>";}

							echo "<tr><td style='text-align:right;vertical-align:top;' width=150><strong>Type :</strong></td><td style='text-align:left;vertical-align:top;'>".getTypeNameFromID($typestatement5)."</td></tr>";			

							if ($show_browser_bar_guest && $subjectheading5 <> NULL)
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>$subject_heading_as :</strong></td><td style='text-align:left;vertical-align:top;'>".getSubjectHeadingNames($subject_heading_delimiter,$subjectheading5)."</td></tr>";}
												
							if ($isbn5 != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>ISBN :</strong></td><td style='text-align:left;vertical-align:top;'>$isbn5</td></tr>";}
							
							if ($issn5 != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>ISSN :</strong></td><td style='text-align:left;vertical-align:top;'>$issn5</td></tr>";}
							
							if ($callnumber5 != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Call number :</strong></td><td style='text-align:left;vertical-align:top;'>$callnumber5</td></tr>";}
							
							if ($authorname5 != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Main Author :</strong></td><td style='text-align:left;vertical-align:top;'>$authorname5</td></tr>";}
							
							if ($additional_authors != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Additional Authors :</strong></td><td style='text-align:left;vertical-align:top;'>$additional_authors</td></tr>";}
							
							if ($se_pname5_a != '')
								{echo "<tr><td style='text-align:right;'><strong>Subject Added Entry - Personal Name :</strong></td><td style='text-align:left;vertical-align:top;'>$se_pname5_a $se_pname5_x $se_pname5_y</td></tr>";}	
							
							echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Title :</strong></td><td style='text-align:left;vertical-align:top;'>$titlestatement5 $titlestatement5_b $titlestatement5_c</td></tr>";
							
							if ($vtitle5_a != '') 
								{echo "<tr><td style='text-align:right;'><strong>Varying Form of Title :</strong></td><td style='text-align:left;vertical-align:top;'>$vtitle5_a $vtitle5_b $vtitle5_g</td></tr>";}
						
							if ($contenttype5_a != '')
								{echo "<tr><td style='text-align:right;'><strong>Content Type :</strong></td><td style='text-align:left;vertical-align:top;'>$contenttype5_a ($contenttype5_2)</td></tr>";}	
							
							if ($mediatype5_a != '')
								{echo "<tr><td style='text-align:right;'><strong>Media Type :</strong></td><td style='text-align:left;vertical-align:top;'>$mediatype5_a ($mediatype5_2)</td></tr>";}	

							if ($carriertype5_a != '')
								{echo "<tr><td style='text-align:right;'><strong>Carrier Type :</strong></td><td style='text-align:left;vertical-align:top;'>$carriertype5_a ($carriertype5_2)</td></tr>";}	
						
							if ($edition5 != '')
								{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Edition :</strong></td><td style='text-align:left;vertical-align:top;'>$edition5</td></tr>";}

						echo "</table>";
					echo "</td>";

					echo "<td style='display: inline-block; padding: 5px;vertical-align:top;' width=350>";
						echo "<table border=0 class=whiteHeaderNoCenter width=100%>";
						if ($publication5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;' width=150><strong>Place of Production :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5</td></tr>";}
						
						if ($publication5_b != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Publisher :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5_b</td></tr>";}
						
						if (isset($publication5_c) && $publication5_c != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Year of Publication :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5_c</td></tr>";}
						
						if ($physicaldesc5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Physical Description :</strong></td><td style='text-align:left;vertical-align:top;'>$physicaldesc5</td></tr>";}
						
						if ($series5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Series :</strong></td><td style='text-align:left;vertical-align:top;'>$series5</td></tr>";}
						
						if ($notes5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Notes :</strong></td><td style='text-align:left;vertical-align:top;'>$notes5</td></tr>";}
						
						if ($summary5_a != '')
							{echo "<tr><td style='text-align:right;'><strong>Summary :</strong></td><td style='text-align:left;vertical-align:top;'>$summary5_a</td></tr>";}

						if ($source5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Corporate Name :</strong></td><td style='text-align:left;vertical-align:top;'>$source5</td></tr>";}
						
						if ($location5 != '')
							{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Location :</strong></td><td style='text-align:left;vertical-align:top;'>$location5</td></tr>";}
					
						if ($link5 <> NULL)
						{
							$link5 = urldecode($link5);
							if (substr($link5,0,4) != 'http') {$link5 = 'http://'.$link5;}

							echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Web Link :</strong></td><td style='text-align:left;vertical-align:top;'><a href='$link5' target='_blank'>Click to view web link</a></td></tr>";
						}			
												
						if ($system_function != 'photo')
						{
							if(is_file($pdocs_file) && $allow_guest_access_to_pt)
								{
									echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Guest :</strong></td><td style='text-align:left;vertical-align:top;'>";
										echo "<a target='_blank' href='".$folderpath."doc.php?t=p&id=".$key."'>Click to view PDF file</a>";
									echo "</td></tr>";																
								}							
							if (is_file("$system_docs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf"))
							{
								if (
										($allow_guest_access_to_ft && $status5 == 'AVAILABLE') 
										|| 
										($_SERVER["REMOTE_ADDR"] == $ezproxy_ip && $status5 == 'AVAILABLE')
										||
										(isset($_SESSION['username_guest']))
									)
								{										
										echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Full Text :</strong></td><td style='text-align:left;vertical-align:top;'>";
											echo "<a target='_blank' href='".$folderpath."doc.php?t=d&id=".$key."'>Click to view PDF file</a>";
										echo "</td></tr>";														
								}							
								else if ($allow_guest_access_to_ft || $status5 == 'LIMITED')
								{
									echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Full Text :</strong></td><td style='text-align:left;vertical-align:top;'>The author has requested the full text of this item to be restricted.</td></tr>";
								}
								else if ($allow_guest_access_to_ft || $status5 == 'EMBARGO')
								{
									echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Full Text :</strong></td><td style='text-align:left;vertical-align:top;'>Item embargoed.</td></tr>";
								}
								else
								{
									echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Full Text :</strong></td><td style='text-align:left;vertical-align:top;'>Login required to access this item.</td></tr>";
								}
							}			
							if(is_file($albums_file))
							{
								echo "<tr><td style='text-align:right;vertical-align:top;'><strong><span style='font-size:10px;color:darkred;'>JPG</span></strong> <strong>Related Image</strong> :</td>";
								echo " <td style='text-align:left;vertical-align:top;'>";
									echo "<a target='_blank' href='".$folderpath."doc.php?t=a&id=".$key."'>Click to view Image file</a>";
								echo "</td></tr>";																
							}
						}
						echo "</table>";
					echo "</td>";
					
					echo "</tr>";
				echo "</table>";
				
				if ($system_function != 'photo')
				{
					if ($fulltext5 != null && $fulltext5 != '<html />')
					{	
						echo "<br/>";
						echo "<table class=whiteHeaderNoCenter>";
							echo "<tr style='text-align:center;'><td><strong>"; if ($isabstract5 == 1) {echo "Abstract";} else {echo "Full Text";} echo " : </strong><em>$source5</em></td></tr>";							
							if ($strip_tags_fulltext_abstract_composer) {$display_fulltext5 = strip_tags(html_entity_decode($fulltext5));} else {$display_fulltext5 = $fulltext5;}							
							echo "<tr><td style='text-align:left;vertical-align:top;'>".highlight(htmlspecialchars_decode($display_fulltext5),$get_highlight)."<br/></td></tr>";
						echo "</table>";
					}
					
					if ($reference5 != null && $reference5 != '<html />')
					{	
						if ($strip_tags_reference_composer) {$display_reference5 = strip_tags(html_entity_decode($reference5));} else {$display_reference5 = html_entity_decode($reference5);}
						
						echo "<br/><table class=whiteHeaderNoCenter style='table-layout:fixed;'><tr style='text-align:center;'><td><strong>References</strong></td></tr>";							
							echo "<tr><td style='text-align:left;vertical-align:top;'><div style='width: 100%; height: 180px; overflow-y: scroll;'>$display_reference5</div><br/></td></tr>";
						echo "</table>";
					}
				}
				
				if (isset($_SESSION['username_guest']) && $enable_feedback_function)
				{
					
					if (isset($_POST['submit_button']))
					{
						$timestamp = time();
						$stmt_insert = $new_conn->prepare("insert into eg_item_feedback values(DEFAULT,?,?,?,?,0,0,'','NO','','')");
						$stmt_insert->bind_param("isss",$get_id_det,$_SESSION['username_guest'],$_POST['feedback1'],$timestamp);
						$stmt_insert->execute();
						$stmt_insert->close();
						echo "<script>window.alert('Your feedback has been recorded and will be moderated before it can be published.');</script>";
					}
					
					if (isset($_GET["df"]) && is_numeric($_GET["df"]))
					{
						$get_id_del = mysqli_real_escape_string($GLOBALS["conn"], $_GET["df"]);
						$stmt_del = $new_conn->prepare("delete from eg_item_feedback where id = ? and eg_auth_username = ?");
						$stmt_del->bind_param("is", $get_id_del,$_SESSION['username_guest']);
						$stmt_del->execute();
						$stmt_del->close();
					}

					if (isset($_GET["fid"]) && is_numeric($_GET["fid"]))
					{
						
						$stmt_findvote = $GLOBALS["new_conn"]->prepare("select 38upvote,38downvote,38votebys from eg_item_feedback where id=?");
							$stmt_findvote->bind_param("i",$_GET['fid']);
							$stmt_findvote->execute();
							$stmt_findvote->store_result();
							$stmt_findvote->bind_result($upvotecount,$downvotecount,$votebys);
							$stmt_findvote->fetch();
							$stmt_findvote->close();

						if (strpos($votebys, "*".$_SESSION['username_guest']."|") !== false) {						
							echo "<script>window.alert('You have cast your vote.');</script>";
						}
						else
						{
							if (isset($_GET['vote']) && $_GET['vote'] == 'up'){
								$by_manipulated = $votebys."*".$_SESSION['username_guest']."|";
								$upvotecount=$upvotecount+1;
								$stmt_update = $new_conn->prepare("update eg_item_feedback set 38upvote=?, 38votebys=? where id=?");
								$stmt_update->bind_param("isi",$upvotecount,$by_manipulated,$_GET["fid"]);
							}
							else if (isset($_GET['vote']) && $_GET['vote'] == 'down'){
								$by_manipulated = $votebys."*".$_SESSION['username_guest']."|";
								$downvotecount=$downvotecount+1;
								$stmt_update = $new_conn->prepare("update eg_item_feedback set 38downvote=?, 38votebys=? where id=?");
								$stmt_update->bind_param("isi",$downvotecount,$by_manipulated,$_GET["fid"]);
							}						
							$stmt_update->execute();
							$stmt_update->close();
						}
					}
					
					echo "<br/>";
					echo "<table class=whiteHeaderNoCenter>";
						echo "<tr style='text-align:center;'><td><strong>Feedback</strong></td></tr>";								
						$stmt_fdb = $new_conn->prepare("select * from eg_item_feedback where eg_item_id=$id5 and 39moderated_status='YES' order by 38upvote desc");
						$stmt_fdb->execute();
						$result_fdb = $stmt_fdb->get_result();
						$n=0;
						while($myrow_fdb = $result_fdb->fetch_assoc())	
						{
							$id_fdb = $myrow_fdb["id"];
							$feedback_fdb = $myrow_fdb["38feedback"];
							$feedback_by_fdb = $myrow_fdb["eg_auth_username"];
							$feedback_ts_fdb = $myrow_fdb["38timestamp"];
							$upvote_fdb = $myrow_fdb["38upvote"];
							$downvote_fdb = $myrow_fdb["38downvote"];
							$votebys_fdb =  $myrow_fdb["38votebys"];
							
							echo "<tr style='text-align:left;'><td>
								$feedback_fdb<br/>by <em>".namePatronfromUsername($feedback_by_fdb)."</em> 
								on ".date('d/M/Y',$feedback_ts_fdb)." ";
								if ($_SESSION['username_guest'] == $feedback_by_fdb) {
									echo "[<a href='detailsg.php?det=$id5&df=$id_fdb' onclick=\"return confirm('Are you sure ?');\">Delete</a>]";
								}
								echo " [";
									if (strpos($votebys_fdb, "*".$_SESSION['username_guest']."|") !== false) {
										echo "<span style='color:blue;'>Upvote: $upvote_fdb</span>";
									}
									else {
										echo "<a onclick=\"return confirm('Are you sure ? This action will be finalize.');\" style='color:blue;' href='detailsg.php?det=$id5&fid=$id_fdb&vote=up'>Upvote: $upvote_fdb</a>";
									}										
								echo "]";
								echo " [";
									if (strpos($votebys_fdb, "*".$_SESSION['username_guest']."|") !== false) {	
										echo "<span style='color:red;'>Downvote: $downvote_fdb</span>";
									}
									else {
										echo "<a onclick=\"return confirm('Are you sure ? This action will be finalize.');\" style='color:red;' href='detailsg.php?det=$id5&fid=$id_fdb&vote=down'>Downvote: $downvote_fdb</a>";
									}
								echo "]";
								echo "<br/><br/></td></tr>";
								$n=1;
						}

						if ($n == 0)
						{
							echo "<tr><td style='text-align:center;vertical-align:top;background-color:lightyellow;'>";
								echo "<i>No feedback found.</i>";
							echo "</td></tr>";
						}

						if (countFeedbackForItemForUser($id5,$_SESSION['username_guest']) == 0)
						{
							echo "<tr><td style='text-align:center;vertical-align:top;background-color:lightyellow;'>";
								echo "<form action='detailsg.php' method='post' enctype='multipart/form-data'>";
									echo "<strong>My feedback: </strong><br/>";
									echo "<textarea name='feedback1' style='width:50%;height:150px;' /></textarea>";
									echo "
										<br/>
										<input type='hidden' name='token' value=".$_SESSION['token'].">
										<input type='hidden' name='det' value='$id5'>
										<input type='submit' name='submit_button' value='Submit' /> 
										<input type='button' value='Cancel' onclick=\"location.href='detailsg.php?det=$id5';\">
										";
								echo "</form>";
							echo "</td></tr>";
						}
						
					echo "</table>";
				}

				echo "<table class=whiteHeaderNoCenter><tr><td style='background-color:lightgrey;text-align:center;'><em>$copyright_info</td></tr></table>";		
			?>

			
			<br/>
			
			<?php				
				if (isset($_SESSION['whichbrowser'])) {echo "<a class='sButton' href='javascript:window.history.back();'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a>";}
				else if (isset($_GET['bk']) && is_numeric($_GET['bk']) && $_GET['bk'] == '1')  {echo "<a class='sButton' href='usr.php?u=g'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a>";}
				else {echo "<a class='sButton' href='searcher.php'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a>";}
			?>	
				
		</div>
		
		<?php 			
			echo "<hr>";
			include './sw_includes/footer.php';							
		?>
		
	</body>
<?php 
} //if $item_status5 == 1
?>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>