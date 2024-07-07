<?php
session_start();define('includeExist', TRUE);
include '../sw_includes/access_isset.php';
include '../core.php'; 
include '../sw_includes/functions.php';	

if ($_GET["rec"] <> NULL && is_numeric($_GET["rec"]) && $_SESSION['editmode'] == 'SUPER')//only SUPER user will enable to delete items
{
	$get_id_rec = $_GET["rec"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_rec);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
		
	$del_path = "$dir_year/$get_id_rec"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delfilename = $del_path;} else {$get_delfilename = "00XXYY";}
	if ($num_results_affected >= 1) {$get_delimgname = $del_path;} else {$get_delimgname = "00XXYY";}
	
	mysqli_query($GLOBALS["conn"],"update eg_item set 50item_status='1' where id='$get_id_rec'");//set invisible traces from eg_item

	if (is_file("../$system_docs_directory/".$get_delfilename.".pdf.deleted")) 
	{
		rename("../$system_docs_directory/".$get_delfilename.".pdf.deleted","../$system_docs_directory/".$get_delfilename.".pdf");
	}
	if (is_file("../$system_pdocs_directory/".$get_delfilename.".pdf.deleted")) 
	{
		rename("../$system_pdocs_directory/".$get_delfilename.".pdf.deleted","../$system_pdocs_directory/".$get_delfilename.".pdf");
	}
	if (is_file("../$system_albums_directory/".$get_delimgname.".jpg.deleted")) 
	{
		rename("../$system_albums_directory/".$get_delimgname.".jpg.deleted","../$system_albums_directory/".$get_delimgname.".jpg");
	}
	if (is_file("../$system_albums_watermark_directory/".$get_delimgname.".jpg.deleted")) 
	{
		rename("../$system_albums_watermark_directory/".$get_delimgname.".jpg.deleted","../$system_albums_watermark_directory/".$get_delimgname.".jpg");
	}
	if (is_file("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg.deleted")) 
	{
		rename("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg.deleted","../$system_albums_thumbnail_directory/".$get_delimgname.".jpg");
	}
	echo "
	<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout(\"self.close()\", 500)</script>
	<body onbeforeunload=\"refreshAndClose();\"><br/><div align=center>Recovery done. This windows will close automatically.</div></body>";
}
else if ($_GET["rec"] <> NULL && is_numeric($_GET["rec"]) && $_SESSION['editmode'] == 'STAFF')//when STAFF user try to manipulate the GET method, this will appear.
{
	echo "<script>alert('Trying doing something illegal arent you ?');</script>";
}
?>