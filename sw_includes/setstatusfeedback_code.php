<?php
session_start();define('includeExist', TRUE);
include '../sw_includes/access_isset.php';
include '../core.php'; 
include '../sw_includes/functions.php';	


if (isset($_GET["fid"]) && is_numeric($_GET["fid"]))
{						
	if ($_GET['ms'] == 'YES') {$setstatus = 'NO';}
	else if ($_GET['ms'] == 'NO') {$setstatus = 'YES';}
	
	$timestamp_for_approval = time();
	
	$stmt_update = $new_conn->prepare("update eg_item_feedback set 39moderated_status=?, 39moderated_by=?, 39moderated_timestamp=? where id=?");
		$stmt_update->bind_param("sssi",$setstatus,$_SESSION['username'],$timestamp_for_approval,$_GET["fid"]);	
		$stmt_update->execute();
		$stmt_update->close();		
	
	echo "<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout(\"self.close()\", 1000)</script>
	<body onbeforeunload=\"refreshAndClose();\"><br/><div align=center>Setting status for feedback. This windows will close automatically.</div></body>";
}

?>