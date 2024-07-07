<?php

session_start();define('includeExist', TRUE);	
include '../sw_includes/access_isset.php';
include '../core.php';		
include '../sw_includes/functions.php';	

if (isset($_GET['defg']) && is_numeric($_GET['defg']))
{
	$get_id_det = $_GET["defg"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
	
	$del_path = "$dir_year/$get_id_det"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delfilename = $del_path;} else {$get_delfilename = "NODIR";}

	if (is_file("../$system_pdocs_directory/".$get_delfilename.".pdf")) 
	{
		unlink("../$system_pdocs_directory/".$get_delfilename.".pdf");
	}

	echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been deleted. This windows will close automatically.</div></body>
		";
	exit();
}

if (isset($_GET['deff']) && is_numeric($_GET['deff']))
{
	$get_id_det = $_GET["deff"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
	
	$del_path = "$dir_year/$get_id_det"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delfilename = $del_path;} else {$get_delfilename = "NODIR";}

	if (is_file("../$system_docs_directory/".$get_delfilename.".pdf")) 
	{
		unlink("../$system_docs_directory/".$get_delfilename.".pdf");
	}

	echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been deleted. This windows will close automatically.</div></body>
		";
	exit();
}

if (isset($_GET['defj']) && is_numeric($_GET['defj']))
{
	$get_id_det = $_GET["defj"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
	
	$del_path = "$dir_year/$get_id_det"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delimgname = $del_path;} else {$get_delimgname = "NODIR";}

	if (is_file("../$system_albums_directory/".$get_delimgname.".jpg")) 
	{
		unlink("../$system_albums_directory/".$get_delimgname.".jpg");
	}
	if (is_file("../$system_albums_watermark_directory/".$get_delimgname.".jpg")) 
	{
		unlink("../$system_albums_watermark_directory/".$get_delimgname.".jpg");
	}
	if (is_file("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg")) 
	{
		unlink("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg");
	}

	echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been deleted. This windows will close automatically.</div></body>
		";
	exit();
}

if (isset($_GET['defi']) && isset($_GET['pic']) && is_numeric($_GET['defi']) && is_numeric($_GET['pic']))
{
	$get_id_det = $_GET["defi"];
	$get_id_pic = $_GET["pic"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_det);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
	
	$del_path = "$dir_year/$get_id_det"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delimgname = $del_path;} else {$get_delimgname = "NODIR";}

	if (is_file("../$system_albums_directory/".$get_delimgname."/$get_id_pic.jpg")) 
	{
		unlink("../$system_albums_directory/".$get_delimgname."/$get_id_pic.jpg");
	}

	if (is_file("../$system_albums_directory/".$get_delimgname."/$get_id_pic"."_wm.jpg")) 
	{
		unlink("../$system_albums_directory/".$get_delimgname."/$get_id_pic"."_wm.jpg");
	}

	echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been deleted. This windows will close automatically.</div></body>
		";
	exit();
}

if ((isset($_GET["del"]) && $_GET["del"] <> NULL && is_numeric($_GET["del"])) && $_SESSION['editmode'] == 'SUPER')//only SUPER user will enable to delete items
{
	$get_id_del = $_GET["del"];

	//get what item to delete
	$stmt_item = $new_conn->prepare("select * from eg_item where id=?");
	$stmt_item->bind_param("i", $get_id_del);
	$stmt_item->execute();
	$result_item = $stmt_item->get_result();
	$num_results_affected = $result_item->num_rows;
	$myrow_item = $result_item->fetch_assoc();
		$inputdate = $myrow_item["39inputdate"];
		$dir_year = substr("$inputdate",0,4);
		$instimestamp = $myrow_item["41instimestamp"];
	
	$del_path = "$dir_year/$get_id_del"."_"."$instimestamp";

	if ($num_results_affected >= 1) {$get_delfilename = $del_path;} else {$get_delfilename = "NODIR";}
	if ($num_results_affected >= 1) {$get_delimgname = $del_path;} else {$get_delimgname = "NODIR";}

	if ($delete_method == 'permanent')
	{	
		mysqli_query($GLOBALS["conn"],"delete from eg_item where id='$get_id_del'");//delete traces from eg_item
		mysqli_query($GLOBALS["conn"],"delete from eg_item2 where eg_item_id='$get_id_del'");//delete traces from eg_item2
		mysqli_query($GLOBALS["conn"],"delete from eg_item2_indicator where eg_item_id='$get_id_del'");//delete traces from eg_item2_indicator
		mysqli_query($GLOBALS["conn"],"delete from eg_item_access where eg_item_id='$get_id_del'");//delete traces from eg_item_access
				
		if (is_file("../$system_docs_directory/".$get_delfilename.".pdf")) 
		{
			unlink("../$system_docs_directory/".$get_delfilename.".pdf");
		}
		if (is_file("../$system_pdocs_directory/".$get_delfilename.".pdf")) 
		{
			unlink("../$system_pdocs_directory/".$get_delfilename.".pdf");
		}
		if (is_file("../$system_albums_directory/".$get_delimgname.".jpg")) 
		{
			unlink("../$system_albums_directory/".$get_delimgname.".jpg");
		}
		if (is_file("../$system_albums_watermark_directory/".$get_delimgname.".jpg")) 
		{
			unlink("../$system_albums_watermark_directory/".$get_delimgname.".jpg");
		}
		if (is_file("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg")) 
		{
			unlink("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg");
		}
		echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been deleted. This windows will close automatically.</div></body>
		";
	}
	else if ($delete_method == 'takecover')
	{
		mysqli_query($GLOBALS["conn"],"update eg_item set 50item_status='0' where id='$get_id_del'");//set invisible traces from eg_item
		
		if (is_file("../$system_docs_directory/".$get_delfilename.".pdf")) 
		{
			rename("../$system_docs_directory/".$get_delfilename.".pdf","../$system_docs_directory/".$get_delfilename.".pdf.deleted");
		}
		if (is_file("../$system_pdocs_directory/".$get_delfilename.".pdf")) 
		{
			rename("../$system_pdocs_directory/".$get_delfilename.".pdf","../$system_pdocs_directory/".$get_delfilename.".pdf.deleted");
		}
		if (is_file("../$system_albums_directory/".$get_delimgname.".jpg")) 
		{
			rename("../$system_albums_directory/".$get_delimgname.".jpg","../$system_albums_directory/".$get_delimgname.".jpg.deleted");
		}
		if (is_file("../$system_albums_watermark_directory/".$get_delimgname.".jpg")) 
		{
			rename("../$system_albums_watermark_directory/".$get_delimgname.".jpg","../$system_albums_watermark_directory/".$get_delimgname.".jpg.deleted");
		}
		if (is_file("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg")) 
		{
			rename("../$system_albums_thumbnail_directory/".$get_delimgname.".jpg","../$system_albums_thumbnail_directory/".$get_delimgname.".jpg.deleted");
		}
		echo "
		<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout('self.close()', 500)</script>
		<body onbeforeunload='refreshAndClose();'><div align=center>Item has been set to undiscoverable. This windows will close automatically.</div></body>
		";
	}
}
else if ((isset($_GET["del"] ) && $_GET["del"] <> NULL && is_numeric($_GET["del"])) && $_SESSION['editmode'] == 'STAFF')//when STAFF user try to manipulate the GET method, this will appear.
{
	echo "<script>alert('Trying doing something illegal arent you ?');</script>";
}
?>