<?php
session_start();define('includeExist', TRUE);
include '../sw_includes/access_isset.php';
include '../core.php'; 
include '../sw_includes/functions.php';	

if ($_GET["itemid"] <> NULL && is_numeric($_GET["itemid"]))//only SUPER user will enable to delete items
{
	$get_id = $_GET["itemid"];
	$get_reason = stringUltraClean(mysqli_real_escape_string($GLOBALS["conn"],$_GET["reason"]));

	mysqli_query($GLOBALS["conn"],"update eg_item set 
						39proposedelete='TRUE', 
						39proposedeleteby='".$_SESSION['username']."', 
						39proposedelete_reason='$get_reason' 
						where id=$get_id");

	echo "<script>function refreshAndClose() {window.opener.location.reload(true);window.close();}setTimeout(\"self.close()\", 1000)</script>
	<body onbeforeunload=\"refreshAndClose();\"><br/><div align=center>Delete request has been forwarded to administrator. This windows will close automatically.</div></body>";
}

?>