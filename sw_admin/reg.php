<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php'; 	
	$thisPageTitle = "Add new item";	

	$is_upd = false; if ((isset($_GET["upd"]) && is_numeric($_GET["upd"])) || (isset($_POST['submit_button']) && $_POST['submit_button'] == 'Update')) {$is_upd = true;}

	//preventing CSRF
	include '../sw_includes/token_validate.php';

	//convert to bytes for things involving file size; original is in MB
	$system_allow_document_maxsize = $system_allow_document_maxsize*1000000;
    $system_allow_pdocument_maxsize = $system_allow_pdocument_maxsize*1000000;
    $system_allow_txt_maxsize = $system_allow_txt_maxsize*1000000;
    $system_allow_imageatt_maxsize = $system_allow_imageatt_maxsize*1000000;
	$max_allow_parser_to_work = $max_allow_parser_to_work*1000000;
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
	<script type="text/javascript">
		<?php
			generateFileBoxAllowedExtensionRule("pfile1", $system_allow_pdocument_extension);
			generateFileBoxAllowedExtensionRule("file1", $system_allow_document_extension);
			generateFileBoxAllowedExtensionRule("txtindex_file1", $system_allow_txt_extension);
			generateFileBoxAllowedExtensionRule("imageatt1", $system_allow_imageatt_extension);
		?>
	</script>
</head>

<body>	

	<?php if (!$is_upd) {include '../sw_includes/loggedinfo.php';} ?>
			
	<hr>
	
	<?php 	
			
		if (isset($_REQUEST['submitted']) && $_REQUEST['submitted'] == 'TRUE' && $proceedAfterToken)
		{			
			//notice handler for posted item that do not assigned with a value
			$val_typestatement1=setDefaultForPostVar($_POST["typestatement1"] ?? '');
			$val_accessnum1=setDefaultForPostVar($_POST["accessnum1"] ?? '');
			$val_status1=setDefaultForPostVar($_POST["status1"] ?? '');
			$val_isbn1=setDefaultForPostVar($_POST["isbn1"] ?? '');
			$val_issn1=setDefaultForPostVar($_POST["issn1"] ?? '');
			$val_langcode1=setDefaultForPostVar($_POST["langcode1"] ?? '');
			$val_notes1=setDefaultForPostVar($_POST["notes1"] ?? '');
			$val_callnum1=setDefaultForPostVar($_POST["callnum1"] ?? '');
			$val_edition1=setDefaultForPostVar($_POST["edition1"] ?? '');
			$val_publication1=setDefaultForPostVar($_POST["publication1"] ?? '');
			$val_series1=setDefaultForPostVar($_POST["series1"] ?? '');
			$val_location1=setDefaultForPostVar($_POST["location1"] ?? '');
			$val_link1=setDefaultForPostVar($_POST["link1"] ?? '');
			$val_subjectheading1=setDefaultForPostVar($_POST["subjectheading1"] ?? '');
			$val_publication1_b=setDefaultForPostVar($_POST["publication1_b"] ?? '');
			$val_publication1_c=setDefaultForPostVar($_POST["publication1_c"] ?? '');
			$val_dissertation_note1=setDefaultForPostVar($_POST["dissertation_note1"] ?? '');
			$val_dissertation_note1_b=setDefaultForPostVar($_POST["dissertation_note1_b"] ?? '');
			$val_dissertation_note1_c=setDefaultForPostVar($_POST["dissertation_note1_c"] ?? '');
			$val_dissertation_note1_d=setDefaultForPostVar($_POST["dissertation_note1_d"] ?? '');
			$val_callnum1_b=setDefaultForPostVar($_POST["callnum1_b"] ?? '');
			$val_authorname1=setDefaultForPostVar($_POST["authorname1"] ?? '');
			$val_authorname1_c=setDefaultForPostVar($_POST["authorname1_c"] ?? '');
			$val_authorname1_d=setDefaultForPostVar($_POST["authorname1_d"] ?? '');
			$val_authorname1_e=setDefaultForPostVar($_POST["authorname1_e"] ?? '');
			$val_authorname1_q=setDefaultForPostVar($_POST["authorname1_q"] ?? '');
			$val_titlestatement1=setDefaultForPostVar($_POST["titlestatement1"] ?? '');
			$val_titlestatement1_b=setDefaultForPostVar($_POST["titlestatement1_b"] ?? '');
			$val_titlestatement1_c=setDefaultForPostVar($_POST["titlestatement1_c"] ?? '');
			$val_vtitle1_a=setDefaultForPostVar($_POST["vtitle1_a"] ?? '');
			$val_vtitle1_b=setDefaultForPostVar($_POST["vtitle1_b"] ?? '');
			$val_vtitle1_g=setDefaultForPostVar($_POST["vtitle1_g"] ?? '');
			$val_physicaldesc1=setDefaultForPostVar($_POST["physicaldesc1"] ?? '');
			$val_physicaldesc1_b=setDefaultForPostVar($_POST["physicaldesc1_b"] ?? '');
			$val_physicaldesc1_c=setDefaultForPostVar($_POST["physicaldesc1_c"] ?? '');
			$val_physicaldesc1_e=setDefaultForPostVar($_POST["physicaldesc1_e"] ?? '');
			$val_contenttype1_a=setDefaultForPostVar($_POST["contenttype1_a"] ?? '');
			$val_contenttype1_2=setDefaultForPostVar($_POST["contenttype1_2"] ?? '');
			$val_mediatype1_a=setDefaultForPostVar($_POST["mediatype1_a"] ?? '');
			$val_mediatype1_2=setDefaultForPostVar($_POST["mediatype1_2"] ?? '');
			$val_carriertype1_a=setDefaultForPostVar($_POST["carriertype1_a"] ?? '');
			$val_carriertype1_2=setDefaultForPostVar($_POST["carriertype1_2"] ?? '');
			$val_series1_v=setDefaultForPostVar($_POST["series1_v"] ?? '');
			$val_subject_entry1a=setDefaultForPostVar($_POST["subject_entry1a"] ?? '');
			$val_subject_entry2a=setDefaultForPostVar($_POST["subject_entry2a"] ?? '');
			$val_subject_entry3a=setDefaultForPostVar($_POST["subject_entry3a"] ?? '');
			$val_sumber1=setDefaultForPostVar($_POST["sumber1"] ?? '');
			$val_sumber1_b=setDefaultForPostVar($_POST["sumber1_b"] ?? '');
			$val_sumber1_e=setDefaultForPostVar($_POST["sumber1_e"] ?? '');
			$val_location1_b=setDefaultForPostVar($_POST["location1_b"] ?? '');
			$val_location1_c=setDefaultForPostVar($_POST["location1_c"] ?? '');
			$val_summary1_a=setDefaultForPostVar($_POST["summary1_a"] ?? '');
			$val_se_pname1_a=setDefaultForPostVar($_POST["se_pname1_a"] ?? '');
			$val_se_pname1_x=setDefaultForPostVar($_POST["se_pname1_x"] ?? '');
			$val_se_pname1_y=setDefaultForPostVar($_POST["se_pname1_y"] ?? '');
			$val_pname1=setDefaultForPostVar($_POST["pname1"] ?? '');
			$val_pname2=setDefaultForPostVar($_POST["pname2"] ?? '');
			$val_pname3=setDefaultForPostVar($_POST["pname3"] ?? '');
			$val_pname4=setDefaultForPostVar($_POST["pname4"] ?? '');
			$val_pname5=setDefaultForPostVar($_POST["pname5"] ?? '');
			$val_pname6=setDefaultForPostVar($_POST["pname6"] ?? '');
			$val_pname7=setDefaultForPostVar($_POST["pname7"] ?? '');
			$val_pname8=setDefaultForPostVar($_POST["pname8"] ?? '');
			$val_pname9=setDefaultForPostVar($_POST["pname9"] ?? '');
			$val_pname10=setDefaultForPostVar($_POST["pname10"] ?? '');
			$val_i_020=setDefaultForPostVar($_POST["i_020"] ?? '');
			$val_i_022=setDefaultForPostVar($_POST["i_022"] ?? '');
			$val_i_041=setDefaultForPostVar($_POST["i_041"] ?? '');
			$val_i_090=setDefaultForPostVar($_POST["i_090"] ?? '');
			$val_i_100=setDefaultForPostVar($_POST["i_100"] ?? '');
			$val_i_245=setDefaultForPostVar($_POST["i_245"] ?? '');
			$val_i_246=setDefaultForPostVar($_POST["i_246"] ?? '');
			$val_i_250=setDefaultForPostVar($_POST["i_250"] ?? '');
			$val_i_264=setDefaultForPostVar($_POST["i_264"] ?? '');
			$val_i_300=setDefaultForPostVar($_POST["i_300"] ?? '');
			$val_i_336=setDefaultForPostVar($_POST["i_336"] ?? '');
			$val_i_337=setDefaultForPostVar($_POST["i_337"] ?? '');
			$val_i_338=setDefaultForPostVar($_POST["i_338"] ?? '');
			$val_i_490=setDefaultForPostVar($_POST["i_490"] ?? '');
			$val_i_500=setDefaultForPostVar($_POST["i_500"] ?? '');
			$val_i_502=setDefaultForPostVar($_POST["i_502"] ?? '');
			$val_i_520=setDefaultForPostVar($_POST["i_520"] ?? '');
			$val_i_600=setDefaultForPostVar($_POST["i_600"] ?? '');
			$val_i_650_1=setDefaultForPostVar($_POST["i_650_1"] ?? '');
			$val_i_650_2=setDefaultForPostVar($_POST["i_650_2"] ?? '');
			$val_i_650_3=setDefaultForPostVar($_POST["i_650_3"] ?? '');
			$val_i_700=setDefaultForPostVar($_POST["i_700"] ?? '');
			$val_i_700_2=setDefaultForPostVar($_POST["i_700_2"] ?? '');
			$val_i_700_3=setDefaultForPostVar($_POST["i_700_3"] ?? '');
			$val_i_700_4=setDefaultForPostVar($_POST["i_700_4"] ?? '');
			$val_i_700_5=setDefaultForPostVar($_POST["i_700_5"] ?? '');
			$val_i_700_6=setDefaultForPostVar($_POST["i_700_6"] ?? '');
			$val_i_700_7=setDefaultForPostVar($_POST["i_700_7"] ?? '');
			$val_i_700_8=setDefaultForPostVar($_POST["i_700_8"] ?? '');
			$val_i_700_9=setDefaultForPostVar($_POST["i_700_9"] ?? '');
			$val_i_700_10=setDefaultForPostVar($_POST["i_700_10"] ?? '');
			$val_i_710=setDefaultForPostVar($_POST["i_710"] ?? '');
			$val_i_852=setDefaultForPostVar($_POST["i_852"] ?? '');
			$val_i_856=setDefaultForPostVar($_POST["i_856"] ?? '');
			
			if (isset($_POST["isabstract1"]) && $_POST["isabstract1"] == '1') {$val_isabstract1 = 1;} else {$val_isabstract1 = 0;}		
			if (isset($_POST['fulltext1'])) {$val_fulltext1 = stringUltraClean($_POST["fulltext1"]);} else {$val_fulltext1 = '';}			
			if (isset($_POST['reference1'])) {$val_reference1 = stringUltraClean($_POST["reference1"]);} else {$val_reference1 = '';}	

			$search_cloud = 
			stringUltraClean(mysqli_real_escape_string($GLOBALS["conn"],
				$val_typestatement1." . ".$val_titlestatement1_b." . ".$val_titlestatement1_c." .  ".
				$val_vtitle1_a." .  ".$val_vtitle1_b." .  ".$val_vtitle1_g." .  ".
				$val_authorname1." . ".
				$val_pname1." . ".$val_pname2." . ".$val_pname3." . ".$val_pname4." . ".$val_pname5." . ".
				$val_pname6." . ".$val_pname7." . ".$val_pname8." . ".$val_pname9." . ".$val_pname10." . ".
				$val_notes1));
				
			echo "<table class=whiteHeader>";
			echo "<tr><td>";		

				if (!empty($_POST["titlestatement1"]) && !empty($_POST["typestatement1"]))
				{									
					// if insert new
					if ($_POST['submit_button'] == 'Insert')
					{
						//insert into eg_item
						mysqli_query($GLOBALS["conn"],
										"insert into eg_item values (DEFAULT,
										'$val_accessnum1','$val_typestatement1','$val_status1',
										'$val_isbn1','$val_issn1','$val_langcode1','$val_callnum1','$val_authorname1',
										'$val_titlestatement1','$val_edition1','$val_publication1','$val_physicaldesc1','$val_series1',
										'$val_notes1','$val_dissertation_note1','$val_sumber1','$val_location1','$val_link1',
										'".date("Y-m-d")."','".$_SESSION['username']."',									
										'FALSE','','',
										'',
										".stringUltraClean($_POST["instimestamp1"]).",
										0,
										'$val_fulltext1','$val_reference1','FALSE','',
										'$val_subjectheading1',
										'$val_isabstract1',NULL,'FALSE',
										'$search_cloud','1',0,'0'
										)");
						
						//prompt user to get the id for the item that has been inputted
						$queryN = "select id from eg_item where 41instimestamp='".stringUltraClean($_POST["instimestamp1"])."' and 39inputby='".$_SESSION['username']."'";
						$resultN = mysqli_query($GLOBALS["conn"],$queryN);
						$myrowN = mysqli_fetch_array($resultN);
						$id1 = $myrowN["id"];
						
						//48 fields on eg_item2
						mysqli_query($GLOBALS["conn"],"insert into eg_item2 values(DEFAULT,
							$id1,
							'$val_callnum1_b',
							'$val_authorname1_c','$val_authorname1_d','$val_authorname1_e','$val_authorname1_q',
							'$val_titlestatement1_b','$val_titlestatement1_c',
								'$val_vtitle1_a','$val_vtitle1_b','$val_vtitle1_g',
							'$val_publication1_b','$val_publication1_c',
							'$val_physicaldesc1_b','$val_physicaldesc1_c','$val_physicaldesc1_e',
								'$val_contenttype1_a','$val_contenttype1_2',
								'$val_mediatype1_a','$val_mediatype1_2',
								'$val_carriertype1_a','$val_carriertype1_2',
							'$val_series1_v',
							'$val_dissertation_note1_b','$val_dissertation_note1_c','$val_dissertation_note1_d',
								'$val_summary1_a',
								'$val_se_pname1_a','$val_se_pname1_x','$val_se_pname1_y',
							'$val_subject_entry1a','$val_subject_entry2a','$val_subject_entry3a',
							'$val_pname1','$val_pname2','$val_pname3','$val_pname4','$val_pname5','$val_pname6','$val_pname7','$val_pname8','$val_pname9','$val_pname10',
							'$val_sumber1_b','$val_sumber1_e','$val_location1_b','$val_location1_c'
							)");

						mysqli_query($GLOBALS["conn"],"insert into eg_item2_indicator values(DEFAULT,
							$id1,
							'$val_i_020','$val_i_022','$val_i_041','$val_i_090',
							'$val_i_100',
							'$val_i_245','$val_i_246','$val_i_250','$val_i_264',
							'$val_i_300','$val_i_336','$val_i_337','$val_i_338',
							'$val_i_490',
							'$val_i_500','$val_i_502','$val_i_520',
							'$val_i_600','$val_i_650_1','$val_i_650_2','$val_i_650_3',
							'$val_i_700','$val_i_700_2','$val_i_700_3','$val_i_700_4','$val_i_700_5','$val_i_700_6','$val_i_700_7','$val_i_700_8','$val_i_700_9','$val_i_700_10','$val_i_710',
							'$val_i_852','$val_i_856'
							)");
						
						echo "<br/><img src='../sw_images/tick.gif'><br/><br/>All provided data has been inputted into the database. The item ID will be displayed onto your screen, then press OK to continue.";
					}//if insert new

					//else if update existing
					else if ($_POST['submit_button'] == 'Update')
					{
						$id1=$_POST["id1"];
						$inputdate1=$_POST["inputdate1"];
						$status1_ori=$_POST["status1_ori"];

						//updating main table -----
						mysqli_query($GLOBALS["conn"],"update eg_item set 
						38title='$val_titlestatement1',38langcode='$val_langcode1',38typeid='$val_typestatement1', 
						38location='$val_location1', 
						38link='$val_link1', 
						38status='$val_status1', 
						38author='$val_authorname1', 
						38source='$val_sumber1', 
						38localcallnum='$val_callnum1', 
						38isbn='$val_isbn1',38issn='$val_issn1', 
						38edition='$val_edition1',38publication='$val_publication1',38physicaldesc='$val_physicaldesc1',38series='$val_series1', 
						38notes='$val_notes1', 
						40lastupdateby='".$_SESSION["username"]."', 
						41fulltexta='$val_fulltext1',41isabstract='$val_isabstract1',41reference='$val_reference1',
						41subjectheading='$val_subjectheading1', 						
						50search_cloud='$search_cloud'  
						where id=$id1");

						if ($val_status1 == 'EMBARGO' && $status1_ori != 'EMBARGO')
						{
							mysqli_query($GLOBALS["conn"],"update eg_item set 51_embargo_timestamp='".time()."' where id=$id1");
						}

						// updating secondary table ----- primary field
						mysqli_query($GLOBALS["conn"],"update eg_item2 set 
								38pname1='$val_pname1',38pname2='$val_pname2',38pname3='$val_pname3',38pname4='$val_pname4',38pname5='$val_pname5',
								38pname6='$val_pname6',38pname7='$val_pname7',38pname8='$val_pname8',38pname9='$val_pname9',38pname10='$val_pname10',
								38dissertation_note_b='$val_dissertation_note1_b',
								38publication_b='$val_publication1_b',38publication_c='$val_publication1_c',
								38vtitle_a='$val_vtitle1_a',38vtitle_b='$val_vtitle1_b',38vtitle_g='$val_vtitle1_g',
								38contenttype_a='$val_contenttype1_a',38contenttype_2='$val_contenttype1_2',
								38mediatype_a='$val_mediatype1_a',38mediatype_2='$val_mediatype1_2',
								38carriertype_a='$val_carriertype1_a',38carriertype_2='$val_carriertype1_2',
								38summary_a='$val_summary1_a',
								38se_pname_a='$val_se_pname1_a',38se_pname_x='$val_se_pname1_x',38se_pname_y='$val_se_pname1_y' 
								where eg_item_id='$id1'");

						// updating secondary table ----- secondary field only when marc record input is selected
						if ($_SESSION['viewmode'] == 'marc') {
							mysqli_query($GLOBALS["conn"],"update eg_item2 set 
								38localcallnum_b='$val_callnum1_b',
								38author_c='$val_authorname1_c',38author_d='$val_authorname1_d',38author_e='$val_authorname1_e',38author_q='$val_authorname1_q',
								38title_b='$val_titlestatement1_b',38title_c='$val_titlestatement1_c',
								38publication_b='$val_publication1_b',38publication_c='$val_publication1_c',
								38physicaldesc_b='$val_physicaldesc1_b',38physicaldesc_c='$val_physicaldesc1_c',38physicaldesc_e='$val_physicaldesc1_e',
								38series_v='$val_series1_v',
								38dissertation_note_c='$val_dissertation_note1_c',38dissertation_note_d='$val_dissertation_note1_d',
								38subject_entry1='$val_subject_entry1a',38subject_entry2='$val_subject_entry2a',38subject_entry3='$val_subject_entry3a',
								38source_b='$val_sumber1_b',38source_e='$val_sumber1_e',
								38location_b='$val_location1_b',38location_c='$val_location1_c' 
								where eg_item_id='$id1'");
							mysqli_query($GLOBALS["conn"],"update eg_item set 38dissertation_note='$val_dissertation_note1' where id=$id1");
						}

						//updating indicators when marc input is selected
						if ($_SESSION['viewmode'] == 'marc') {
							mysqli_query($GLOBALS["conn"],"update eg_item2_indicator set 
								i_020='$val_i_020',i_022='$val_i_022',i_041='$val_i_041',i_090='$val_i_090',
								i_100='$val_i_100',
								i_245='$val_i_245',i_246='$val_i_246',i_250='$val_i_250',i_264='$val_i_264',
								i_300='$val_i_300',i_336='$val_i_336',i_337='$val_i_337',i_338='$val_i_338',
								i_490='$val_i_490',
								i_500='$val_i_500',i_502='$val_i_502',i_520='$val_i_520',
								i_600='$val_i_600',	i_650_1='$val_i_650_1',i_650_2='$val_i_650_2',i_650_3='$val_i_650_3',
								i_700='$val_i_700',i_700_2='$val_i_700_2',i_700_3='$val_i_700_3',i_700_4='$val_i_700_4',i_700_5='$val_i_700_5',i_700_6='$val_i_700_6',i_700_7='$val_i_700_7',i_700_8='$val_i_700_8',i_700_9='$val_i_700_9',i_700_10='$val_i_700_10',i_710='$val_i_710',
								i_852='$val_i_852',i_856='$val_i_856' 
								where eg_item_id='$id1'");
						}		

						echo "<br/><img src='../sw_images/tick.gif'><br/><br/>All provided data has been updated into the database.";
					}//else if update existing
					
					//start uploading things regardless insert new or update the existing one
					$idUpload = $id1; //reassign and pass it to upload.php
					$timestampUpload = stringUltraClean($_POST["instimestamp1"]); //reassign and pass it to upload.php		
					if (isset($inputdate1))	{$dir_year = substr($inputdate1,0,4);} else {$dir_year = date("Y");}//pass it to upload.php
					
					//full text upload to server
					if (isset($_FILES['file1']['name']) && $_FILES['file1']['name'] != null)
					{
						echo "<br/><br/>File upload status: ";
						
						$affected_directory = "../$system_docs_directory/$dir_year";
						$affected_filefield = "file1";
						$targetted_filemaxsize = $GLOBALS["system_allow_document_maxsize"];
						$successful_upload_mesage = "File attachment uploaded successfully !";
						$incorrect_filetype_mesage = "Upload aborted ! Incorrect file type. Please update this record if you need to reupload the file using the record ID.";
						$incorrect_filesize_mesage = "Upload aborted ! Attachment file size > than expected. Please update this record if you need to reupload the file using the record ID.";
						$targetted_field_to_update = "41pdfattach";
						$allow_parser_to_parse_internally = true;
						$parse_txt_file = false;
						$upload_type = "text";

						include '../sw_includes/upl/upload.php';

						//run pdf indexer only with pdf files
						if ($_FILES['file1']['size'] <= $max_allow_parser_to_work && $affected_fileextension == 'pdf')
						{
							include '../sw_includes/upl/parser.php';
						}	
					}

					//guest file upload to server
					if (isset($_FILES['pfile1']['name']) && $_FILES['pfile1']['name'] != null)
					{
						echo "<br/><br/>Guest file upload status: ";

						$affected_directory = "../$system_pdocs_directory/$dir_year";
						$affected_filefield = "pfile1";
						$targetted_filemaxsize = $GLOBALS["system_allow_pdocument_maxsize"];
						$successful_upload_mesage = "Guest file attachment uploaded successfully !";
						$incorrect_filetype_mesage = "Upload aborted ! Incorrect file type. Please update this record if you need to reupload the file using the record ID.";
						$incorrect_filesize_mesage = "Upload aborted ! Attachment file size > than expected. Please update this record if you need to reupload the file using the record ID.";
						$targetted_field_to_update = "41ppdfattach";
						$allow_parser_to_parse_internally = false;
						$parse_txt_file = false;
						$upload_type = "text";

						include '../sw_includes/upl/upload.php';
					}				

					//txt upload to server
					if (isset($_FILES['txtindex_file1']['name']) && $_FILES['txtindex_file1']['name'] != null)
					{
						echo "<br/><br/>Index upload status: ";

						$affected_directory = "../$system_txts_directory/$dir_year";
						$affected_filefield = "txtindex_file1";
						$targetted_filemaxsize = $GLOBALS["system_allow_txt_maxsize"];
						$successful_upload_mesage = "Index attachment uploaded successfully !";
						$incorrect_filetype_mesage = "Upload aborted ! Incorrect file type. Please update this record if you need to reupload the file using the record ID.";
						$incorrect_filesize_mesage = "Upload aborted ! Attachment file size > than expected. Please update this record if you need to reupload the file using the record ID.";
						$targetted_field_to_update = "41pdfattach_fulltext";
						$allow_parser_to_parse_internally = false;
						$parse_txt_file = true;
						$upload_type = "text";

						include '../sw_includes/upl/upload.php';
					}					
				
					//image upload to server
					if (isset($_FILES['imageatt1']['name']) && $_FILES['imageatt1']['name'] != null)
					{
						echo "<br/><br/>Image upload status: ";

						$affected_directory = "../$system_albums_directory/$dir_year";
						$affected_watermark_directory = "../$system_albums_watermark_directory/$dir_year";
						$affected_thumbnail_directory = "../$system_albums_thumbnail_directory/$dir_year";						
						$affected_filefield = "imageatt1";
						$targetted_filemaxsize = $GLOBALS["system_allow_imageatt_maxsize"];
						$successful_upload_mesage = "Image attachment uploaded successfully !";
						$incorrect_filetype_mesage = "Upload aborted ! Incorrect image type. Please update this record if you need to reupload the file using the record ID.";
						$incorrect_filesize_mesage = "Upload aborted ! Attachment image size > than expected. Please update this record if you need to reupload the file using the record ID.";
						$targetted_field_to_update = "41imageatt";
						$allow_parser_to_parse_internally = false;
						$parse_txt_file = false;
						$upload_type = "image";

						include '../sw_includes/upl/upload.php';
					}

					//uploading additional images - new codes
					for ($x = 2; $x <= $maximum_num_imageatt_allowed; $x++) {
						$imagenumber = "$x"; 
						$imagefield = "imageatt$imagenumber"; 
						if (isset($_FILES[$imagefield]['name']) && $_FILES[$imagefield]['name'] != null) 
						{
							echo "<br/><br/>Image upload status: ";
							
							$affected_directory = "../$system_albums_directory/$dir_year/$idUpload"."_"."$timestampUpload";
							$affected_filefield = $imagefield;
							$targetted_filemaxsize = $GLOBALS["system_allow_imageatt_maxsize"];
							$successful_upload_mesage = "Additional image #$imagenumber uploaded successfully !";
							$incorrect_filetype_mesage = "Upload aborted ! Incorrect image type. Please update this record if you need to reupload the file using the record ID.";
							$incorrect_filesize_mesage = "Upload aborted ! Attachment image size > than expected. Please update this record if you need to reupload the file using the record ID.";
							$targetted_field_to_update = null;
							$allow_parser_to_parse_internally = false;
							$parse_txt_file = null;
							$upload_type = "multiimage";

							include '../sw_includes/upl/upload.php';
						}	
					}

					if ($_POST['submit_button'] == 'Insert') {
						echo "<br/><br/>Click <a href='reg.php'>here</a> to begin a new input.<br/><br/>";
					}
					else if ($_POST['submit_button'] == 'Update') {
						echo "<br/><br/><div style='text-align:center;'><a class='sButtonRedSmall' href='javascript:window.opener.location.reload(true);window.close();'><span class='fas fa-window-close'></span> Close</a></div>";	
					}
				}
			
				else
				{
					echo "	<img src='../sw_images/caution.png'>
							<br/><br/><span style='color:red;'>Your previous input has been cancelled.Check if any field(s) left emptied before posting.</span>
					    	<br/><br/>Click <a href='reg.php'>here</a> to begin a new input.<br/><br/>";
				}

			echo "</td></tr>";
			echo "</table><br/><br/>";
		}//if submitted
		
		//initialize variables for in use with populate all fields below
		$id="";$title="";$typeid="";$status="";$location="";$link="";$author="";$langcode="";$source="";
		$localcallnum="";$inputdate="";$dir_year="";$lastupdateby="";$fulltexta="";$reference="";$isabstract="";
		$pdfattach="";$pdfattach_fulltext="";$instimestamp="";$subjectheading="";$imageatt="";$ppdfattach="";$accessnum="";
		$isbn="";$issn="";$edition="";$publication="";$physicaldesc="";$series="";$notes="";$dissertation_note="";
		$localcallnum_b="";$author_c="";$author_d="";$author_e="";$author_q="";$title_b="";$title_c="";
			$vtitle_a	= "";$vtitle_b	= "";$vtitle_g	= "";$publication_b="";$publication_c="";$physicaldesc_b ="";$physicaldesc_c="";$physicaldesc_e="";
			$contenttype_a="";$contenttype_2="";$mediatype_a="";$mediatype_2="";$carriertype_a="";$carriertype_2="";$series_v="";
		$dissertation_note_b="";$dissertation_note_c="";$dissertation_note_d="";$summary_a="";$se_pname_a="";$se_pname_x="";$se_pname_y="";
		$subject_entry1="";$subject_entry2="";$subject_entry3="";
		$pname1="";$pname2="";$pname3="";$pname4="";$pname5="";$pname6="";$pname7="";$pname8 ="";$pname9="";$pname10="";$source_b="";$source_e="";$location_b ="";$location_c="";
		$i_020="";$i_022="";$i_041="";$i_090="";$i_100="";$i_245="";$i_246="";$i_250="";$i_264="";$i_300="";$i_336="";$i_337="";$i_338="";$i_490="";$i_500="";$i_502="";
		$i_520="";$i_600="";$i_650_1="";$i_650_2="";$i_650_3="";$i_700="";$i_700_2="";$i_700_3="";$i_700_4="";$i_700_5="";$i_700_6="";$i_700_7="";$i_700_8="";$i_700_9="";$i_700_10="";$i_710="";$i_852="";$i_856="";

		//populate all fields with values for update operation
		$is_upd = false;
		if (isset($_GET["upd"]) && is_numeric($_GET["upd"]))
		{
			$get_id_upd = $_GET["upd"];
			$is_upd = true;

			//main table
			$query_value = "select * from eg_item where id = $get_id_upd";
			$result_value = mysqli_query($GLOBALS["conn"],$query_value);
			$myrow_value = mysqli_fetch_array($result_value);			
				$id = $myrow_value["id"];
				$title = stripslashes(htmlspecialchars($myrow_value["38title"],ENT_QUOTES));
				$typeid = $myrow_value["38typeid"];$status = $myrow_value["38status"];$location = $myrow_value["38location"];$link = $myrow_value["38link"];						
				$author = stripslashes($myrow_value["38author"]);				
				$langcode = $myrow_value["38langcode"];
				$source = $myrow_value["38source"];
				$localcallnum = $myrow_value["38localcallnum"];
				$inputdate = $myrow_value["39inputdate"];
					$dir_year = substr("$inputdate",0,4);
				$lastupdateby = $myrow_value["40lastupdateby"];
				$fulltexta = $myrow_value["41fulltexta"];$reference = $myrow_value["41reference"];$isabstract = $myrow_value["41isabstract"];$pdfattach = $myrow_value["41pdfattach"];$pdfattach_fulltext = $myrow_value["41pdfattach_fulltext"];
				$instimestamp = $myrow_value["41instimestamp"];
				$subjectheading = $myrow_value["41subjectheading"];
				$imageatt = $myrow_value["41imageatt"];	$ppdfattach = $myrow_value["41ppdfattach"];				
				$accessnum = $myrow_value["38accessnum"];
				$isbn = $myrow_value["38isbn"];$issn = $myrow_value["38issn"];
				$edition = $myrow_value["38edition"];$publication = $myrow_value["38publication"];$physicaldesc = $myrow_value["38physicaldesc"];$series = $myrow_value["38series"];
				$notes = $myrow_value["38notes"];$dissertation_note = $myrow_value["38dissertation_note"];	
			
			//secondary table
			$query_value2 = "select * from eg_item2 where eg_item_id = $get_id_upd";
			$result_value2 = mysqli_query($GLOBALS["conn"],$query_value2);
			if (mysqli_num_rows($result_value2) >= 1)
			{
				$myrow_value2 = mysqli_fetch_array($result_value2);

				$localcallnum_b = $myrow_value2["38localcallnum_b"];
				$author_c = $myrow_value2["38author_c"];$author_d = $myrow_value2["38author_d"];$author_e = $myrow_value2["38author_e"];$author_q = $myrow_value2["38author_q"];
				$title_b = $myrow_value2["38title_b"];$title_c = $myrow_value2["38title_c"];
					$vtitle_a	= $myrow_value2["38vtitle_a"];$vtitle_b	= $myrow_value2["38vtitle_b"];$vtitle_g	= $myrow_value2["38vtitle_g"];
				$publication_b = $myrow_value2["38publication_b"];$publication_c = $myrow_value2["38publication_c"];
				$physicaldesc_b = $myrow_value2["38physicaldesc_b"];$physicaldesc_c = $myrow_value2["38physicaldesc_c"];$physicaldesc_e = $myrow_value2["38physicaldesc_e"];
					$contenttype_a = $myrow_value2["38contenttype_a"];$contenttype_2 = $myrow_value2["38contenttype_2"];
					$mediatype_a = $myrow_value2["38mediatype_a"];$mediatype_2 = $myrow_value2["38mediatype_2"];
					$carriertype_a = $myrow_value2["38carriertype_a"];$carriertype_2 = $myrow_value2["38carriertype_2"];
				$series_v = $myrow_value2["38series_v"];
				$dissertation_note_b = $myrow_value2["38dissertation_note_b"];$dissertation_note_c = $myrow_value2["38dissertation_note_c"];$dissertation_note_d = $myrow_value2["38dissertation_note_d"];	
					$summary_a = $myrow_value2["38summary_a"];
					$se_pname_a = $myrow_value2["38se_pname_a"];$se_pname_x = $myrow_value2["38se_pname_x"];$se_pname_y = $myrow_value2["38se_pname_y"];
				$subject_entry1 = $myrow_value2["38subject_entry1"];$subject_entry2 = $myrow_value2["38subject_entry2"];$subject_entry3 = $myrow_value2["38subject_entry3"];	
				$pname1 = $myrow_value2["38pname1"];$pname2 = $myrow_value2["38pname2"];$pname3 = $myrow_value2["38pname3"];$pname4 = $myrow_value2["38pname4"];$pname5 = $myrow_value2["38pname5"];
				$pname6 = $myrow_value2["38pname6"];$pname7 = $myrow_value2["38pname7"];$pname8 = $myrow_value2["38pname8"];$pname9 = $myrow_value2["38pname9"];$pname10 = $myrow_value2["38pname10"];
				$source_b = $myrow_value2["38source_b"];$source_e = $myrow_value2["38source_e"];
				$location_b = $myrow_value2["38location_b"];$location_c = $myrow_value2["38location_c"];
			}
			
			//loading indicators
			$query_value3 = "select * from eg_item2_indicator where eg_item_id = $get_id_upd";
			$result_value3 = mysqli_query($GLOBALS["conn"],$query_value3);
			if (mysqli_num_rows($result_value3) >= 1)
			{
				$myrow_value3 = mysqli_fetch_array($result_value3);	

				$i_020 = $myrow_value3["i_020"];$i_022 = $myrow_value3["i_022"];$i_041 = $myrow_value3["i_041"];$i_090 = $myrow_value3["i_090"];
				$i_100 = $myrow_value3["i_100"];
				$i_245 = $myrow_value3["i_245"];$i_246 = $myrow_value3["i_246"];$i_250 = $myrow_value3["i_250"];$i_264 = $myrow_value3["i_264"];
				$i_300 = $myrow_value3["i_300"];$i_336 = $myrow_value3["i_336"];$i_337 = $myrow_value3["i_337"];$i_338 = $myrow_value3["i_338"];
				$i_490 = $myrow_value3["i_490"];
				$i_500 = $myrow_value3["i_500"];$i_502 = $myrow_value3["i_502"];$i_520 = $myrow_value3["i_520"];
				$i_600 = $myrow_value3["i_600"];$i_650_1 = $myrow_value3["i_650_1"];$i_650_2 = $myrow_value3["i_650_2"];$i_650_3 = $myrow_value3["i_650_3"];
				$i_700 = $myrow_value3["i_700"];$i_700_2 = $myrow_value3["i_700_2"];$i_700_3 = $myrow_value3["i_700_3"];$i_700_4 = $myrow_value3["i_700_4"];$i_700_5 = $myrow_value3["i_700_5"];$i_700_6 = $myrow_value3["i_700_6"];
					$i_700_7 = $myrow_value3["i_700_7"];$i_700_8 = $myrow_value3["i_700_8"];$i_700_9 = $myrow_value3["i_700_9"];$i_700_10 = $myrow_value3["i_700_10"];$i_710 = $myrow_value3["i_710"];
				$i_852 = $myrow_value3["i_852"];$i_856 = $myrow_value3["i_856"];
			}
		}
	?>
			
	<?php
	//the main form for this page
		if (!isset($_REQUEST['submitted']))
		{	
	?>
			<?php
				if ($default_view_input == 'marc') {
					$_SESSION['viewmode'] = 'marc';
				}
				else {
					$_SESSION['viewmode'] = 'simple';
				}
			?>
			<table class=yellowHeader >
				<tr>
					<td>
						<strong>Fill in the fields for inserting a new record: </strong>
						<?php
							if ($debug_search == 'yes') {
								echo "<br/><em><span style='color:grey;font-size:10px'>Server Upload Setting (php.ini) > Post Max Size:</span> <span style='color:orange;'>".ini_get("post_max_size")."</span></em> <em><span style='color:grey;font-size:10px'>Upload Max Size:</span> <span style='color:orange;'>".ini_get("upload_max_filesize")."</span></em>";	
							}
						?>				
					</td>
				</tr>
			</table>
			
			<form name="swadahform" action="reg.php" method="post" enctype="multipart/form-data" data-parsley-validate>
				<table class=greyBody>
				
						<tr>
							<td style='text-align:right;vertical-align:top;'><strong>Type </strong></td>
							<td>: 
							<?php if ($_SESSION['viewmode'] == 'marc') {?><span style='color:lightgrey;'>|a</span><?php }?>
								<select name="typestatement1">						
								<?php
									$queryB = "select 38typeid, 38type, 38default from eg_item_type";
									$resultB = mysqli_query($GLOBALS["conn"],$queryB);
									while ($myrowB=mysqli_fetch_array($resultB))
										{
											echo "<option value='".$myrowB["38typeid"]."' "; 
												if (($is_upd && isset($typeid) && $typeid == $myrowB["38typeid"]) || (!$is_upd && $myrowB["38default"] == 'TRUE')) {echo "selected";}
											echo ">".$myrowB["38type"]."</option>";
										}
								?>						
								</select>
							</td>
						</tr>
						
						<?php if ($init_status_visibility == 'show') {?>
						<tr>
							<td style='text-align:right;vertical-align:top;'><strong>Initial status </strong></td>
							<td>: 
								<?php if ($_SESSION['viewmode'] == 'marc') {?><span style='opacity:0.0;'>|?<?php }?></span>
								<input type='hidden' name='status1_ori' value='<?php echo $status;?>'>
								<select name="status1">
									<option value="AVAILABLE" <?php if (isset($status) && $is_upd && $status == 'AVAILABLE') {echo "selected";}?>>Available - Public</option>
									<option value="LIMITED" <?php if (isset($status) && $is_upd && $status == 'LIMITED') {echo "selected";}?>>Limited - For Registered User</option>								
									<option value="FINALPROCESSING" <?php if (isset($status) && $is_upd && $status == 'FINALPROCESSING') {echo "selected";}?>>Final Processing</option>								
									<option value="EMBARGO" <?php if (isset($status) && $is_upd && $status == 'EMBARGO') {echo "selected";}?>>Embargo</option>
								</select>
							</td>
						</tr>
						<?php } else { echo "<input type='hidden' name='status1' value='AVAILABLE'>"; }?>
						
						<?php if ($enable_subject_entry) {?>
						<tr>
							<td style='text-align:right;vertical-align:top;'><strong><?php echo $subject_heading_as;?> </strong></td>
							<td>: 
								<?php if ($_SESSION['viewmode'] == 'marc') {?><span style='opacity:0.0;'>|?<?php }?></span>
								<input type="text" id="subjectheading1" name="subjectheading1" style="width:60%" maxlength="150" value="<?php if (isset($subjectheading)) {echo $subjectheading;}?>"/>
								<input type="button" name="subjectheadingButton" value="..." onClick="showList()" />
								<input type="button" name="clearSH" value="Clear" onClick="subjectheading1.value='';">
							</td>
						</tr>
						<?php }?>
						
						<?php if ($show_accession_number) {?>
						<tr>
							<td style='text-align:right;vertical-align:top;'><strong>Accession Number </strong></td>
							<td>: 
								<?php if ($_SESSION['viewmode'] == 'marc') {?><span style='opacity:0.0;'>|?</span><?php }?>
								<?php
									if (!$is_upd)
									{
										echo "<input type='text' name='accessnum1' style='width:80%' maxlength='150' readonly value='";echo millitime()."'/>";
									}
									else if ($is_upd && isset($accessnum)){
										echo $accessnum."<input type='hidden' name='accessnum1' value='$accessnum'>";
									}
								?>
							</td>
						</tr>
						<?php } else { echo "<input type='hidden' name='accessnum1' value='";echo millitime();echo "'/>"; }?>

						<?php 
							//generate form fields using special functions
							
							regRowGenerate(false,$tag_020_show,$_SESSION['viewmode'],$tag_020,$tag_020_simple,"",true,"i_020","","isbn1","","|a","80%","60",$is_upd,$isbn,$i_020);
							regRowGenerate(false,$tag_022_show,$_SESSION['viewmode'],$tag_022,$tag_022_simple,"",true,"i_022","","issn1","","|a","80%","60",$is_upd,$issn,$i_022);

							if ($tag_041_show) {
								regRowGenerateSelectBox($tag_041_show,$_SESSION['viewmode'],$tag_041,$tag_041_simple,"i_041","|a",$tag_041_selectable,$tag_041_selectable_default,"langcode1",$tag_041_inputtype,true,$is_upd,$langcode,$i_041);
							}

							regRowGenerate(false,$tag_090_show,$_SESSION['viewmode'],$tag_090,$tag_090_simple,"",true,"i_090","","callnum1","","|a","80%","70",$is_upd,$localcallnum,$i_090);
							if ($_SESSION['viewmode'] == 'marc')
							{
								regRowGenerate(false,$tag_090_show,$_SESSION['viewmode'],"","","",false,"","","callnum1_b","","|b","80%","70",$is_upd,$localcallnum_b);
							}
					
							regRowGenerate(false,$tag_100_show,$_SESSION['viewmode'],$tag_100,$tag_100_simple,"",true,"i_100",$tag_100_default_ind,"authorname1","","|a","80%","150",$is_upd,$author,$i_100);
							if ($_SESSION['viewmode'] == 'marc')
							{
								regRowGenerate(false,$tag_100_show,$_SESSION['viewmode'],"","","",false,"","","authorname1_c","","|c","80%","150",$is_upd,$author_c);
								regRowGenerate(false,$tag_100_show,$_SESSION['viewmode'],"","","",false,"","","authorname1_d","","|d","80%","150",$is_upd,$author_d);
								regRowGenerate(false,$tag_100_show,$_SESSION['viewmode'],"","","",false,"","","authorname1_e","","|e","80%","150",$is_upd,$author_e);
								regRowGenerate(false,$tag_100_show,$_SESSION['viewmode'],"","","",false,"","","authorname1_q","","|q","80%","150",$is_upd,$author_q);
							}

							regRowGenerate(true,$tag_245_show,$_SESSION['viewmode'],"<sup style='color:red;'>REQUIRED</sup> ".$tag_245,$tag_245_simple,"",true,"i_245",$tag_245_default_ind,"titlestatement1","","|a","80%","255",$is_upd,$title,$i_245);
							if ($_SESSION['viewmode'] == 'marc')
							{
								regRowGenerate(false,$tag_245_show,$_SESSION['viewmode'],"","","",false,"","","titlestatement1_b","","|b","80%","255",$is_upd,$title_b);
								regRowGenerate(false,$tag_245_show,$_SESSION['viewmode'],"","","",false,"","","titlestatement1_c","","|c","80%","255",$is_upd,$title_c);
							}

							regRowGenerate(false,$tag_246_show,$_SESSION['viewmode'],$tag_246,$tag_246_simple,"",true,"i_246",$tag_246_default_ind,"vtitle1_a","","|a","80%","255",$is_upd,$vtitle_a,$i_246);
							if ($_SESSION['viewmode'] == 'marc')
							{
								regRowGenerate(false,$tag_246_show,$_SESSION['viewmode'],"","","",false,"","","vtitle1_b","","|b","80%","255",$is_upd,$vtitle_b);
								regRowGenerate(false,$tag_246_show,$_SESSION['viewmode'],"","","",false,"","","vtitle1_g","","|g","80%","255",$is_upd,$vtitle_g);
							}

							regRowGenerate(false,$tag_250_show,$_SESSION['viewmode'],$tag_250,$tag_250_simple,"",true,"i_250","","edition1","","|a","80%","255",$is_upd,$edition,$i_250);						
						
							regRowGenerate(false,$tag_264_show,$_SESSION['viewmode'],$tag_264,$tag_264_simple,"Place of production",true,"i_264",$tag_264_default_ind,"publication1",$tag_264_a_default,"|a","80%","255",$is_upd,$publication,$i_264);
							regRowGenerate(false,$tag_264_show,$_SESSION['viewmode'],"","","Name of publisher",false,"","","publication1_b","","|b","68%","255",$is_upd,$publication_b,"","text","<input type='button' name='publisherButton' value='...' onClick=\"showPublisher()\" />");
							regRowGenerate(false,$tag_264_show,$_SESSION['viewmode'],"","","Year of publication",false,"","","publication1_c","","|c","80%","255",$is_upd,$publication_c);
												
							regRowGenerate(false,$tag_300_show,$_SESSION['viewmode'],$tag_300,$tag_300_simple,"",true,"i_300",$tag_300_default_ind,"physicaldesc1","","|a","80%","255",$is_upd,$physicaldesc,$i_300);
							if ($_SESSION['viewmode'] == 'marc') {
								regRowGenerate(false,$tag_300_show,$_SESSION['viewmode'],"","","",false,"","","physicaldesc1_b","","|b","80%","255",$is_upd,$physicaldesc_b);
								regRowGenerate(false,$tag_300_show,$_SESSION['viewmode'],"","","",false,"","","physicaldesc1_c","","|c","80%","255",$is_upd,$physicaldesc_c);
								regRowGenerate(false,$tag_300_show,$_SESSION['viewmode'],"","","",false,"","","physicaldesc1_e","","|e","80%","255",$is_upd,$physicaldesc_e);
							}

							regRowGenerate(false,$tag_336_show,$_SESSION['viewmode'],$tag_336,$tag_336_simple,"Content type term",true,"i_336",$tag_336_default_ind,"contenttype1_a",$tag_336_default_a,"|a","80%","255",$is_upd,$contenttype_a,$i_336);
							regRowGenerate(false,$tag_336_show,$_SESSION['viewmode'],"","","Source",false,"","","contenttype1_2",$tag_336_default_2,"|2","80%","255",$is_upd,$contenttype_2);

							regRowGenerate(false,$tag_337_show,$_SESSION['viewmode'],$tag_337,$tag_337_simple,"Media type term",true,"i_337",$tag_337_default_ind,"mediatype1_a",$tag_337_default_a,"|a","80%","255",$is_upd,$mediatype_a,$i_337);
							regRowGenerate(false,$tag_337_show,$_SESSION['viewmode'],"","","Source",false,"","","mediatype1_2",$tag_337_default_2,"|2","80%","255",$is_upd,$mediatype_2);

							regRowGenerate(false,$tag_338_show,$_SESSION['viewmode'],$tag_338,$tag_338_simple,"Carrier type term",true,"i_338",$tag_338_default_ind,"carriertype1_a",$tag_338_default_a,"|a","80%","255",$is_upd,$carriertype_a,$i_338);
							regRowGenerate(false,$tag_338_show,$_SESSION['viewmode'],"","","Source",false,"","","carriertype1_2",$tag_338_default_2,"|2","80%","255",$is_upd,$carriertype_2);

							regRowGenerate(false,$tag_490_show,$_SESSION['viewmode'],$tag_490,$tag_490_simple,"",true,"i_490","","series1","","|a","80%","150",$is_upd,$series,$i_490);
							if ($_SESSION['viewmode'] == 'marc') {
								regRowGenerate(false,$tag_490_show,$_SESSION['viewmode'],"","","",false,"","","series1_v","","|v","80%","150",$is_upd,$series_v);
							}

							regRowGenerate(false,$tag_500_show,$_SESSION['viewmode'],$tag_500,$tag_500_simple,"",true,"i_500",$tag_500_default_ind,"notes1","","|a","80%","0",$is_upd,$notes,$i_500,"textarea");
						 
							if ($_SESSION['viewmode'] == 'marc') {
								regRowGenerate(false,$tag_502_show,$_SESSION['viewmode'],$tag_502,$tag_502_simple,"",true,"i_502","","dissertation_note1","","|a","80%","150",$is_upd,$dissertation_note,$i_502);
							}

							regRowGenerateSelectBox($tag_502_show,$_SESSION['viewmode'],"",$tag_502_simple,'i_502',"|b",$tag_502_b_selectable,$tag_502_b_selectable_default,"dissertation_note1_b",$tag_502_inputtype,false,$is_upd,$dissertation_note_b);

							if ($_SESSION['viewmode'] == 'marc') {
								regRowGenerate(false,$tag_502_show,$_SESSION['viewmode'],"","","",false,"","","dissertation_note1_c","","|c","80%","50",$is_upd,$dissertation_note_c);
								regRowGenerate(false,$tag_502_show,$_SESSION['viewmode'],"","","",false,"","","dissertation_note1_d","","|d","80%","50",$is_upd,$dissertation_note_d);
							}
						
							regRowGenerate(false,$tag_520_show,$_SESSION['viewmode'],$tag_520,$tag_520_simple,"",true,"i_520",$tag_520_default_ind,"summary1_a","","|a","80%","0",$is_upd,$summary_a,$i_520,"textarea");
	
							regRowGenerate(false,$tag_600_show,$_SESSION['viewmode'],$tag_600,$tag_600_simple,"Personal name",true,"i_600",$tag_600_default_ind,"se_pname1_a","","|a","80%","255",$is_upd,$se_pname_a,$i_600);
								regRowGenerate(false,$tag_600_show,$_SESSION['viewmode'],"","","General subdivision",false,"","","se_pname1_x","","|x","80%","255",$is_upd,$se_pname_x);
								regRowGenerate(false,$tag_600_show,$_SESSION['viewmode'],"","","Chronological subdivision",false,"","","se_pname1_y","","|y","80%","255",$is_upd,$se_pname_y);
						 
							if ($_SESSION['viewmode'] == 'marc') {
								regRowGenerate(false,$tag_650_show,$_SESSION['viewmode'],$tag_650,$tag_650_simple,"",true,"i_650_1","","subject_entry1a","","|a","80%","255",$is_upd,$subject_entry1,$i_650_1);
								regRowGenerate(false,$tag_650_show,$_SESSION['viewmode'],$tag_650,$tag_650_simple,"",true,"i_650_2","","subject_entry2a","","|a","80%","255",$is_upd,$subject_entry2,$i_650_2);
								regRowGenerate(false,$tag_650_show,$_SESSION['viewmode'],$tag_650,$tag_650_simple,"",true,"i_650_3","","subject_entry3a","","|a","80%","255",$is_upd,$subject_entry3,$i_650_3);							
						 	}

							regRowGenerateRepeats($tag_700_show,10,$_SESSION['viewmode'],$tag_700,$tag_700_simple,"i_700",$tag_700_default_ind,"|a","pname",$is_upd,$pname1,$i_700);

							regRowGenerate(false,$tag_710_show,$_SESSION['viewmode'],$tag_710,$tag_710_simple,"",true,"i_710",$tag_710_default_ind,"sumber1",$tag_710_a_default,"|a","80%","255",$is_upd,$source,$i_710);
							if ($_SESSION['viewmode'] == 'marc') {
									regRowGenerate(false,$tag_710_show,$_SESSION['viewmode'],"","","",false,"","","sumber1_b",$tag_710_b_default,"|b","80%","255",$is_upd,$source_b);
									regRowGenerate(false,$tag_710_show,$_SESSION['viewmode'],"","","",false,"","","sumber1_e",$tag_710_e_default,"|e","80%","255",$is_upd,$source_e);
								}

							regRowGenerate(false,$tag_852_show,$_SESSION['viewmode'],$tag_852,$tag_852_simple,"",true,"i_852","","location1","","|a","80%","255",$is_upd,$location,$i_852);
							if ($_SESSION['viewmode'] == 'marc') {
									regRowGenerate(false,$tag_852_show,$_SESSION['viewmode'],"","","",false,"","","location1_b","","|b","80%","255",$is_upd,$location_b);
									regRowGenerate(false,$tag_852_show,$_SESSION['viewmode'],"","","",false,"","","location1_c","","|c","80%","255",$is_upd,$location_c);
								}

							regRowGenerate(false,$tag_856_show,$_SESSION['viewmode'],$tag_856,$tag_856_simple,"",true,"i_856","","link1","","|u","80%","255",$is_upd,$link,$i_856);
						 
							if ($system_function != 'photo' && $allow_guestpdf_insert_by_admin) 
							{ 
								regRowGenerateFileUploadBox("Guest PDF",$system_allow_pdocument_maxsize,"pfile1",$system_allow_pdocument_extension,$is_upd,"../$system_pdocs_directory/$dir_year/"."$id"."_"."$instimestamp".".pdf");
							}

							regRowGenerateFileUploadBox("Full Text PDF",$system_allow_document_maxsize,"file1",$system_allow_document_extension,$is_upd,"../$system_docs_directory/$dir_year/"."$id"."_"."$instimestamp".".pdf");
							
							if ($system_function != 'photo' && $allow_txt_insert_by_admin)
							{
								regRowGenerateFileUploadBox("TXT ",$system_allow_txt_maxsize,"txtindex_file1",$system_allow_txt_extension,$is_upd,null);
							}

							if ($allow_image_insert_by_admin)
							{
								regRowGenerateFileUploadBox("JPG ",$system_allow_imageatt_maxsize,"imageatt1",$system_allow_imageatt_extension,$is_upd,"../$system_albums_directory/$dir_year/"."$id"."_"."$instimestamp".".jpg");
								if ($system_function == 'photo') 
								{
									for ($x = 2; $x <= $maximum_num_imageatt_allowed; $x++) {
										$currentDisplay_image = "$x"; 
										$currentInput_image = "imageatt$currentDisplay_image"; 
										if ($x == ($maximum_num_imageatt_allowed)) {$requirenext=false;}
										else {$requirenext=true;} 
										if ($is_upd) {include "../sw_includes/update_imagefield.php";}
										else {include "../sw_includes/reg_imagefield.php";}
									}						
							 	}
							}

						?>		
						
						<?php if ($system_function != 'photo' && $enable_fulltext_abstract_composer) {?>
							<tr>
								<td style='text-align:center;vertical-align:top;' colspan=2>
									<br/><br/><strong>Full Text Composer </strong>: <input type=checkbox name="isabstract1" value="1" <?php if (($is_upd && $isabstract == '1') || (!$is_upd && $default_is_abstract)) {echo "checked";} ?>>This is Abstract
								</td>
							</tr>						
							<tr>
								<td style='vertical-align:top;' colspan=2>
									<textarea <?php if ($fulltext_abstract_composer_type == 'richtext') {echo "id='wadahComposer'";} ?> style="resize: none; width: 100%;" name="fulltext1" cols="72" rows="30"><?php echo $fulltexta;?></textarea>
								</td>
							</tr>
						<?php }?>

						<?php if ($system_function != 'photo' && $enable_reference_composer) {?>
							<tr>
								<td style='text-align:center;vertical-align:top;' colspan=2>
									<br/><br/><strong>Reference Composer </strong>:
							</td>
							</tr>							
							<tr>
								<td style='vertical-align:top;' colspan=2>
									<textarea <?php if ($reference_composer_type == 'richtext') {echo "id='referenceComposer'";} ?> style="resize: none; width: 100%;" name="reference1" cols="72" rows="30"><?php echo $reference;?></textarea>
								</td>
							</tr>
						<?php }?>

						<tr><td colspan='2' style='text-align:center;vertical-align:top;'>
							<input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
							<?php
							if (!$is_upd)
							{
								echo "
									<input type='hidden' name='instimestamp1' value='".time()."' />
									<input type='hidden' name='submitted' value='TRUE' />
									<br/>
									<input type='submit' name='submit_button' value='Insert' />									
								";
							}
							else if ($is_upd)
							{
								echo "
									<input type='hidden' name='id1' value='$id' />	
									<input type='hidden' name='instimestamp1' value='$instimestamp' />
									<input type='hidden' name='inputdate1' value='$inputdate' />
									<input type='hidden' name='submitted' value='TRUE' />
									<br/>
									<input type='submit' name='submit_button' value='Update' />
								";
								echo "<br/><br/><div style='text-align:center;'><a class='sButtonRedSmall' href='javascript:window.opener.location.reload(true);window.close();'><span class='fas fa-window-close'></span> Close</a></div>";
							}
							?>
							<br/><br/>
						</td></tr>
				</table>		
			</form>		

	<?php
		}//if submitted
	?>

		<hr>
		<?php if (!$is_upd) {include '../sw_includes/footer.php';}?>

	</body>

	</html>
	
	<?php mysqli_close($GLOBALS["conn"]); exit(); ?>