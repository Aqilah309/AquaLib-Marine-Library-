<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include 'sw_includes/access_isset.php';
	include 'core.php';include 'sw_includes/access_allowed_adminip.php';
	include 'sw_includes/functions.php';	
	$thisPageTitle = "Item Details";
		
	$get_id_det = 0; if (isset($_GET["det"]) && is_numeric($_GET["det"])) {$get_id_det = $_GET["det"];} else {$get_id_det = 0;}

	$get_scstr = ""; if (isset($_SESSION["sear_scstr"])) {$get_scstr = $_SESSION["sear_scstr"];} 
		
	if (isset($_GET["infname"])) {$get_infname = $_GET["infname"];}
	if (isset($_GET["page"])) {$get_page = $_GET["page"];}
	
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		
	if ($num_results_affected >= 1)
	{
		$id5 = $myrow_item["id"];
		$titlestatement5 = reverseStringUltraClean($myrow_item["38title"]);
		$typestatement5 = $myrow_item["38typeid"];
		$status5 = $myrow_item["38status"];
		$location5 = $myrow_item["38location"];
		$link5 = $myrow_item["38link"];						
			$query_access = "select count(eg_item_access.id) as totalid from eg_item_access,eg_item where eg_item_access.eg_item_id=eg_item.id and eg_item_access.eg_item_id='$id5'";
			$result_access = mysqli_query($GLOBALS["conn"],$query_access);
			$myrow_access = mysqli_fetch_array($result_access);
			$hits5 = $myrow_access["totalid"];						
		$authorname5 = reverseStringUltraClean($myrow_item["38author"]);
		$callnumber5 = $myrow_item["38localcallnum"];
		$langcode5 = $myrow_item["38langcode"];
		$inputdate5 = $myrow_item["39inputdate"];
			$dir_year5 = substr("$inputdate5",0,4);
		$input_by5 = $myrow_item["39inputby"];			
		$input_by5 = $myrow_item["39inputby"];		
		$lastupdateby5 = $myrow_item["40lastupdateby"];
		$fulltext5 = $myrow_item["41fulltexta"];
		$reference5 = $myrow_item["41reference"];						
		$isabstract5 = $myrow_item["41isabstract"];
		$pdfattach5 = $myrow_item["41pdfattach"];
		$pdfattach_fulltext5 = $myrow_item["41pdfattach_fulltext"];
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
		$item_status5 = $myrow_item["50item_status"];
		$pagecount5 = $myrow_item["51_pagecount"];
		$embargo_timestamp5 = $myrow_item["51_embargo_timestamp"];

		$deletereq5 = $myrow_item["39proposedelete"];
		$deletereq_by5 = $myrow_item["39proposedeleteby"];
		$deletereq_reason5 = $myrow_item["39proposedelete_reason"];

		$query_item_ex = "select * from eg_item2 where eg_item_id=$id5";
		$result_item_ex = mysqli_query($GLOBALS["conn"],$query_item_ex);
		if (mysqli_num_rows($result_item_ex) >= 1)
		{
			$myrow_item_ex = mysqli_fetch_array($result_item_ex);			
			$titlestatement5_b = $myrow_item_ex["38title_b"];
			$titlestatement5_c = $myrow_item_ex["38title_c"];
			$publication5_b = $myrow_item_ex["38publication_b"];
			$publication5_c = $myrow_item_ex["38publication_c"];
			$additional_authors = $myrow_item_ex["38pname1"];
				if ($myrow_item_ex["38pname2"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname2"];}
				if ($myrow_item_ex["38pname3"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname3"];}
				if ($myrow_item_ex["38pname4"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname4"];}
				if ($myrow_item_ex["38pname5"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname5"];}
				if ($myrow_item_ex["38pname6"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname6"];}
				if ($myrow_item_ex["38pname7"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname7"];}
				if ($myrow_item_ex["38pname8"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname8"];}
				if ($myrow_item_ex["38pname9"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname9"];}
				if ($myrow_item_ex["38pname10"] !='') {$additional_authors .= "<br/>".$myrow_item_ex["38pname10"];}
			$vtitle5_a	= $myrow_item_ex["38vtitle_a"];
			$vtitle5_b	= $myrow_item_ex["38vtitle_b"];
			$vtitle5_g	= $myrow_item_ex["38vtitle_g"];
			$contenttype5_a = $myrow_item_ex["38contenttype_a"];
			$contenttype5_2 = $myrow_item_ex["38contenttype_2"];
			$mediatype5_a = $myrow_item_ex["38mediatype_a"];
			$mediatype5_2 = $myrow_item_ex["38mediatype_2"];
			$carriertype5_a = $myrow_item_ex["38carriertype_a"];
			$carriertype5_2 = $myrow_item_ex["38carriertype_2"];
			$summary5_a = $myrow_item_ex["38summary_a"];
			$se_pname5_a = $myrow_item_ex["38se_pname_a"];
			$se_pname5_x = $myrow_item_ex["38se_pname_x"];
			$se_pname5_y = $myrow_item_ex["38se_pname_y"];
		}
		else
		{
			$titlestatement5_b = '';
			$titlestatement5_c = '';
			$publication5_b = '';
			$publication5_c = '';
			$additional_authors = '';
			$vtitle5_a	= '';
			$vtitle5_b	= '';
			$vtitle5_g	= '';
			$contenttype5_a = '';
			$contenttype5_2 = '';
			$mediatype5_a = '';
			$mediatype5_2 = '';
			$carriertype5_a = '';
			$carriertype5_2 = '';
			$summary5_a = '';
			$se_pname5_a = '';
			$se_pname5_x = '';
			$se_pname5_y = '';
		}
	}
?>

<html lang='en'>

<head>
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>
		
	<?php include 'sw_includes/loggedinfo.php'; ?>
	
	<hr>			

	<div style='text-align:center'>
		<?php
			if ($num_results_affected >= 1)
			{
				echo "<table class=whiteHeader>";
				echo "<tr class=yellowHeaderCenter><td><strong>Record Details</strong> : ";												
				echo "</td></tr></table>";
				
				echo "<table class=whiteHeaderNoCenter style='border: 1px solid lightgrey; max-width:100%;overflow-x: auto;' width=100%>";	
				echo "<tr>";

					echo "<td style='display: inline-block; padding: 5px;width:500px;vertical-align:top;'>";
						echo "<table class=whiteHeaderNoCenter width=100%>";					
							
								if ($show_control_number) {
									echo "<tr><td style='text-align:right;' width=150px><strong>Control Number :</strong></td><td style='text-align:left;vertical-align:top;'>$id5</td></tr>";
								}
								
								echo "<tr><td style='text-align:right;'><strong>Hits :</strong></td><td style='text-align:left;vertical-align:top;'><a onclick=\"return openPopup(this.href,900,580);\" title='Hits for $id5' target='_blank' href='sw_stats/adsreport_ipitems.php?det=$id5'>$hits5</a></td></tr>";
										
								echo "<tr><td style='text-align:right;'><strong>Downloads :</strong></td><td style='text-align:left;vertical-align:top;'>".getDownloadHitsPerItemID($id5)."</td></tr>";													
								
								echo "<tr><td style='text-align:right;'><strong>Type :</strong></td><td style='text-align:left;vertical-align:top;'>".getTypeNameFromID($typestatement5)."</td></tr>";
								
								if ($show_browser_bar_admin && $subjectheading5 <> NULL)
								{
									echo "<tr><td style='text-align:right;vertical-align:top;'><strong>$subject_heading_as:</strong></td><td style='text-align:left;vertical-align:top;'>".getSubjectHeadingNames($subject_heading_delimiter,$subjectheading5)."</td></tr>";
								}
								
								echo "<tr><td style='text-align:right;'><strong>Status :</strong></td><td style='text-align:left;vertical-align:top;'>";
									$status5_embargo_indicator = '';
									if ($status5 == 'EMBARGO') {										
										if (date("d M Y",$embargo_timestamp5+($embargoed_duration*86400)) == date("d M Y"))
										{
											mysqli_query($GLOBALS["conn"],"update eg_item set 38status='AVAILABLE' where id=$id5");
											$status5 = 'AVAILABLE';
										}
										else
										{
											$status5_embargo_indicator = " <sup>(".date("d M Y",$embargo_timestamp5)." - ".date("d M Y",$embargo_timestamp5+($embargoed_duration*86400)).")</sup>";
										}
									}	
									echo "$status5 $status5_embargo_indicator";									
								echo "</td></tr>";
								
								if ($show_accession_number) 
									{echo "<tr><td style='text-align:right;'><strong>Accession Number :</strong></td><td style='text-align:left;vertical-align:top;'>$accessnum5</td></tr>";}
								
								if ($isbn5 != '')
									{echo "<tr><td style='text-align:right;'><strong>ISBN :</strong></td><td style='text-align:left;vertical-align:top;'>$isbn5</td></tr>";}
								if ($issn5 != '')
									{echo "<tr><td style='text-align:right;'><strong>ISSN :</strong></td><td style='text-align:left;vertical-align:top;'>$issn5</td></tr>";}
								if ($callnumber5 != '')
									{echo "<tr><td style='text-align:right;'><strong>Call number :</strong></td><td style='text-align:left;vertical-align:top;'>$callnumber5</td></tr>";}						
								if ($authorname5 != '')
									{echo "<tr><td style='text-align:right;'><strong>Main Author :</strong></td><td style='text-align:left;vertical-align:top;'>$authorname5</td></tr>";}
								
								if (isset($additional_authors) && $additional_authors != '')
									{echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Additional Authors :</strong></td><td style='text-align:left;vertical-align:top;'>$additional_authors</td></tr>";}
								
								if ($se_pname5_a != '')
									{echo "<tr><td style='text-align:right;'><strong>Subject Added Entry - Personal Name :</strong></td><td style='text-align:left;vertical-align:top;'>$se_pname5_a $se_pname5_x $se_pname5_y</td></tr>";	}
								
								echo "<tr><td style='text-align:right;'><strong>Title :</strong></td><td style='text-align:left;vertical-align:top;'>$titlestatement5 $titlestatement5_b $titlestatement5_c</td></tr>";
								
								if ($vtitle5_a != '') 
									{echo "<tr><td style='text-align:right;'><strong>Varying Form of Title :</strong></td><td style='text-align:left;vertical-align:top;'>$vtitle5_a $vtitle5_b $vtitle5_g</td></tr>";}
								
								if ($contenttype5_a != '')
									{echo "<tr><td style='text-align:right;'><strong>Content Type :</strong></td><td style='text-align:left;vertical-align:top;'>$contenttype5_a ($contenttype5_2)</td></tr>";	}
								
								if ($mediatype5_a != '')
									{echo "<tr><td style='text-align:right;'><strong>Media Type :</strong></td><td style='text-align:left;vertical-align:top;'>$mediatype5_a ($mediatype5_2)</td></tr>";	}

								if ($carriertype5_a != '')
									{echo "<tr><td style='text-align:right;'><strong>Carrier Type :</strong></td><td style='text-align:left;vertical-align:top;'>$carriertype5_a ($carriertype5_2)</td></tr>";	}
								
								if ($langcode5 != '')
									{echo "<tr><td style='text-align:right;'><strong>Language Code :</strong></td><td style='text-align:left;vertical-align:top;'>$langcode5</td></tr>";	}

								if ($edition5 != '')
									{echo "<tr><td style='text-align:right;'><strong>Edition :</strong></td><td style='text-align:left;vertical-align:top;'>$edition5</td></tr>";}
							
						echo "</table>";
					echo "</td>";

					echo "<td style='display: inline-block; padding: 5px;width:500px;vertical-align:top;'>";
						echo "<table class=whiteHeaderNoCenter width=100%>";

							if ($publication5 != '')
								{echo "<tr><td style='text-align:right;' width=150px><strong>Place of Production :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5</td></tr>";}
							if ($publication5_b != '')
								{echo "<tr><td style='text-align:right;'><strong>Publisher :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5_b</td></tr>";}
							if ($publication5_c != '')
								{echo "<tr><td style='text-align:right;'><strong>Year of Publication :</strong></td><td style='text-align:left;vertical-align:top;'>$publication5_c</td></tr>";}
							if ($physicaldesc5 != '')
								{echo "<tr><td style='text-align:right;'><strong>Physical Description :</strong></td><td style='text-align:left;vertical-align:top;'>$physicaldesc5</td></tr>";}
							if ($series5 != '')
								{echo "<tr><td style='text-align:right;'><strong>Series :</strong></td><td style='text-align:left;vertical-align:top;'>$series5</td></tr>";}
							if ($notes5 != '')
								{echo "<tr><td style='text-align:right;'><strong>Notes :</strong></td><td style='text-align:left;vertical-align:top;'>$notes5</td></tr>";}							
							if ($summary5_a != '')
								{echo "<tr><td style='text-align:right;'><strong>Summary :</strong></td><td style='text-align:left;vertical-align:top;'>$summary5_a</td></tr>";}	
							if ($source5 != '')
								{echo "<tr><td style='text-align:right;'><strong>Corporate Name :</strong></td><td style='text-align:left;vertical-align:top;'>$source5</td></tr>";}	
							if ($location5 != '')
								{echo "<tr><td style='text-align:right;'><strong>Location :</strong></td><td style='text-align:left;vertical-align:top;'>$location5</td></tr>";}
							
							if ($link5 <> NULL)
								{									
									$link5 = urldecode($link5);
									
									if (substr($link5,0,4) != 'http')
									{$link5 = 'http://'.$link5;}
									
									echo "<tr><td style='text-align:right;'><strong>Web Link :</strong></td><td style='text-align:left;vertical-align:top;'><a href='$link5' target='_blank'>Click here</a> </td></tr>";
								}
																							

							if(is_file("$system_pdocs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf")) 
								{
									echo "<tr><td style='text-align:right;'>";
										echo "<strong><span style='font-size:10px;color:darkred;'>PDF</span> Guest :</strong></td><td style='text-align:left;vertical-align:top;'><a href='$system_pdocs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf' target='_blank'>$id5"."_"."$instimestamp5.pdf</a>";
										if ($usePdfInfo)
										{
											$pdfTotalPages = getPDFPages2($appendroot,"$system_pdocs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf");
											if ($pdfTotalPages <= $max_page_toshow_red_color) {$pdfColor = "red";} else {$pdfColor = "blue";}
											
											if ($pdfTotalPages >= 1) {
												echo " <sup style='color:$pdfColor;text-decoration:none;'>$pdfTotalPages page(s)</sup>";
											}
										}
									echo " [<a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' href='sw_includes/del_inst.php?defg=$id5'>Delete</a>]</td></tr>";
								}					

							if(is_file("$system_docs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf")) 
								{
									echo "<tr><td style='text-align:right;'><strong><span style='font-size:10px;color:darkred;'>PDF</span> Full Text :</strong></td><td style='text-align:left;vertical-align:top;'><a href='$system_docs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf' target='_blank'>$id5"."_"."$instimestamp5.pdf</a>";
									if ($pdfattach_fulltext5 != '')
									{
										echo " <sup><a style='color:#1b90ad;text-decoration:none;' target='_blank' href='sw_tools/ft.php?det=$get_id_det'>CONTENT&nbspINDEXED</a></sup>";
									}

									if ($usePdfInfo && $pagecount5 == 0)
									{
										$pdfTotalPages = getPDFPages2($appendroot,"$system_docs_directory/$dir_year5/$id5"."_"."$instimestamp5.pdf");
										mysqli_query($GLOBALS["conn"],"update eg_item set 51_pagecount=$pdfTotalPages where id=$id5");
									}
									else
									{
										$pdfTotalPages = $pagecount5;
									}

									if ($pdfTotalPages <= $max_page_toshow_red_color) {$pdfColor = "red";} else {$pdfColor = "blue";}
									
									if ($pdfTotalPages <> 0)
									{
										echo " <sup style='color:$pdfColor;text-decoration:none;'>$pdfTotalPages page(s)</sup>";
									}

									echo " [<a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' href='sw_includes/del_inst.php?deff=$id5'>Delete</a>]</td></tr>";
								}							
						
						echo "</table>";
					echo "</td>";
				
				echo "</tr>";
				echo "</table>";	

				echo "<table class=whiteHeaderNoCenter style='border: 1px solid lightgrey;'>";
						if(is_file("$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg")) 
							{
								echo "<tr><td colspan=2 style='text-align:center;'><strong><u><span style='font-size:10px;color:darkred;'>JPG</span> Image :</u></strong> [<a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' href='sw_includes/del_inst.php?defj=$id5'>Delete This Image</a>]</td></tr>";
								echo "<tr><td colspan=2 style='text-align:center;vertical-align:top;'>";
									echo "<table style='border: 0px solid lightgrey; width:100%;overflow-x: auto;'><tr>";											
											echo "<td style='font-size:8pt;display: inline-block; padding: 5px; width:150px;'>";											
												echo "<strong>Original :</strong>";
												echo "<img class='centered-and-cropped' src='$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg?=".time()."' width=128px height=128px onerror=this.src='sw_images/no_image.png'><br/>";	
												echo "<a onclick='return openPopup(this.href,800,600);' target='_blank' href='$system_albums_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg' title='$titlestatement5'>1.jpg</a>";
											echo "</td>";
										
										if(is_file("$system_albums_watermark_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg")) 
										{											
												echo "<td style='font-size:8pt;display: inline-block; padding: 5px; width:150px;'>";
													echo "<strong>Watermarked :</strong>";
													echo "<img class='centered-and-cropped' src='$system_albums_watermark_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg?=".time()."' width=128px height=128px onerror=this.src='sw_images/no_image.png'><br/>";
													echo "<a onclick='return openPopup(this.href,800,680);' target='_blank' href='$system_albums_watermark_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg' title='$titlestatement5'>1.jpg</a>";
												echo "</td>";											
										}
										if(is_file("$system_albums_thumbnail_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg")) 
										{											
												echo "<td style='font-size:8pt;display: inline-block; padding: 5px; width:150px;'>";
													echo "<strong>Thumbnail :</strong>";
													echo "<img class='centered-and-cropped' src='$system_albums_thumbnail_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg?=".time()."' width=128px height=128px onerror=this.src='sw_images/no_image.png'><br/>";
													echo "<a onclick='return openPopup(this.href,800,600);' target='_blank' href='$system_albums_thumbnail_directory/$dir_year5/$id5"."_"."$instimestamp5.jpg' title='$titlestatement5'>1.jpg</a>";
												echo "</td>";											
										}
									echo "</tr></table>";
								echo "</td>";
								echo "</tr>";
							}
							if ($system_function == 'photo') 
							{
								echo "<tr><td colspan=2 style='text-align:center;'><strong><u><span style='font-size:10px;color:darkred;'>JPG</span> More Images :</u></strong></td></tr>
									<tr>
									<td colspan=2 style='text-align:center;vertical-align:top;'>";
										echo "<table style='border: 0px solid lightgrey; width:100%;overflow-x: auto;'><tr>";
											for ($x = 2; $x <= $maximum_num_imageatt_allowed; $x++) {
												$currentimage_num = "$x"; include "sw_includes/details_imageload.php";
											}
										echo "</tr></table>";					
								echo "</td></tr>";
							}
				echo "</table>";
				
				echo "<table class=whiteHeaderNoCenter style='border: 1px solid lightgrey;'>";
					if ($fulltext5 != null && $fulltext5 != '<html />')
						{
							echo "<tr style='background-color:#FFFE96'><td colspan=2 style='text-align:center;'><strong>";
								if ($isabstract5 == 1) {echo "Abstract";}
								else {echo "Full Text";}
							echo " :</strong></td></tr>";
							if ($strip_tags_fulltext_abstract_composer) {$display_fulltext5 = strip_tags(html_entity_decode($fulltext5));}
							else {$display_fulltext5 = $fulltext5;}
							echo "<tr><td colspan=2 style='text-align:left;vertical-align:top;'>".highlight($display_fulltext5,$get_scstr)."<br/></td></tr>";
						}
						
						if ($reference5 != null && $reference5 != '<html />')
						{
							echo "<tr style='background-color:#FFFE96'><td colspan=2 style='text-align:center;'><strong>References:</strong></td></tr>";
							if ($strip_tags_reference_composer) {$display_reference5 = strip_tags(html_entity_decode($reference5));}
							else {$display_reference5 = html_entity_decode($reference5);}
							echo "<tr><td colspan=2 style='text-align:left;vertical-align:top;'><div style='width: 100%; height: 180px; overflow-y: scroll;'>$display_reference5</div><br/></td></tr>";
						}
				echo "</table>";
				
				//administrator yellow panel starts
				echo "<br/><table class=cyanHeader>";			
					echo "<tr class=yellowHeaderCenter><td colspan=2 style='text-align:center;'><strong>Administrative :</strong></td></tr>";
					echo "<tr><td style='text-align:right;' width=150><strong>Input date :</strong></td><td style='text-align:left;'>$inputdate5</td></tr>";					
					echo "<tr><td style='text-align:right;'><strong>Input by : </strong></td><td style='text-align:left;'>".namePatronfromUsername($input_by5)."</td></tr>";
					
					if ($_SESSION['editmode'] == 'SUPER')
						{echo "<tr><td style='text-align:right;'><strong>Last update by : </strong></td><td style='text-align:left;'>".namePatronfromUsername($lastupdateby5)."</td></tr>";}
											
					echo "<tr><td style='text-align:right;vertical-align:top;'><strong>Option :</strong></td><td style='text-align:left;'>";	
	
					if ($_SESSION['editmode'] == 'SUPER')
					{
						if ($item_status5 == '1')
						{
							if ($delete_method == 'permanent')
							{
								echo "<img src='./sw_images/delete.gif'> <a onclick='return openPopup(this.href,200,200);' target='_blank' href='sw_includes/del_inst.php?del=$id5'>Delete</a>";
							}
							else if ($delete_method == 'takecover')
							{
								echo "<img src='./sw_images/delete.gif'> <a onclick='return openPopup(this.href,200,200);' target='_blank' href='sw_includes/del_inst.php?tel=tc&del=$id5'>Delete</a>";
							}
						}	
						else
						{
							echo "<img src='./sw_images/pencil.gif'> <a onclick='return openPopup(this.href,200,200);' target='_blank' href='sw_includes/recover_code.php?rec=$id5'>Recover</a>";
						}				
					}
					else
						{echo "Delete";}//delete will not be available if user not SUPER user
								
					//Update option
					echo " | <img src='./sw_images/pencil.gif'> <a onclick='return openPopup(this.href,950,580);' href='sw_admin/reg.php?upd=$id5'>Update</a>";

					//Delete request options
					if ($deletereq5 == 'TRUE')
					{
						$deletereq_by5 = $myrow_item["39proposedeleteby"];
						$deletereq_reason5 = $myrow_item["39proposedelete_reason"];
						if ($_SESSION['username'] == $deletereq_by5 || $_SESSION['editmode'] == 'SUPER')
							{echo "| <img src='./sw_images/undo.png' width=15px> <a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' target='_blank' href='sw_includes/undodelreq_code.php?itemid=$id5'>Undo Delete Request</a>";}
						echo "<br/>This item is on Delete Request list. Delete request made by <span style='color:red;'>$deletereq_by5</span> with the reason <span style='color:red;'>$deletereq_reason5</span>.";
					}
					else
					{
						echo "| <img src='./sw_images/duplicate.png' width=15px> <a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' target='_blank' href='sw_includes/assigndelreq_code.php?itemid=$id5&reason=duplicate+detected'>Delete Request: Duplicate</a>";
						echo "| <img src='./sw_images/alert.png' width=15px> <a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' target='_blank' href='sw_includes/assigndelreq_code.php?itemid=$id5&reason=request+by+a+higher+authority'>Delete Request: Request by a higher authority</a>";
					}
					echo "</td></tr>";		
				echo "</table>";	


				if ($enable_feedback_function)
				{				
					echo "<br/>";
					echo "<table class=cyanHeader>";
						echo "<tr class=yellowHeaderCenter><td><strong>Feedback</strong></td></tr>";								
						$stmt_fdb = $new_conn->prepare("select * from eg_item_feedback where eg_item_id=$id5");
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
							$moderated_status = $myrow_fdb["39moderated_status"];
							$moderated_by = $myrow_fdb["39moderated_by"];
							$moderated_ts = $myrow_fdb["39moderated_timestamp"];
							
							echo "<tr style='text-align:left;'><td>
								$feedback_fdb<br/>by <em>".namePatronfromUsername($feedback_by_fdb)."</em> 
								on ".date('d/M/Y',$feedback_ts_fdb)." ";								
								echo " Current approval status: [<a onclick='if (confirm(\"Are you sure?\")){return openPopup(this.href,200,200);}else{event.stopPropagation(); event.preventDefault();};' target='_blank' href='sw_includes/setstatusfeedback_code.php?fid=$id_fdb&ms=$moderated_status'>$moderated_status</a>]";						
								echo "<br/><span style='color:blue;'>Upvote: $upvote_fdb</span>";
								echo " <span style='color:red;'>Downvote: $downvote_fdb</span>";
								echo "<br/><br/></td></tr>";
								$n=1;
						}

						if ($n == 0)
						{
							echo "<tr><td style='text-align:center;vertical-align:top;background-color:lightyellow;'>";
								echo "<i>No feedback found.</i>";
							echo "</td></tr>";
						}						
					echo "</table>";
				}
			}	
			else
				{echo "<div style='padding-top:10px;font-size:18px;'>Item is not available.</div>";}					
		?> 

		<br/>
		<?php
			echo "<a class='sButton' href='javascript:history.go(-1)'><span class='fas fa-arrow-circle-left'></span> Back to previous page</a>";
		?>		
	</div>

	<hr>
		
	<?php include './sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>