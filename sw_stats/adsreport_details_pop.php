<!DOCTYPE HTML>
<?php
	session_start();define('includeExist', TRUE);	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "Report Details";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>

	<div style="text-align:center">
		<?php
			if ($_SESSION['editmode'] == 'SUPER')
				{$get_user = $_GET["user"];}
			else
				{$get_user = $_SESSION["username"];}

			echo "<h2>Input summary for $get_user of ".$_GET["month"]."/".$_GET["year"]."</h2>";

			$query_fsb = "select 38typeid, 38type from eg_item_type";
			$result_fsb = mysqli_query($GLOBALS["conn"],$query_fsb);

			while ($row_fsb = mysqli_fetch_array($result_fsb))
				{					
					$param = $_GET["year"]."-".$_GET["month"]."%";
					$stmt_total = $new_conn->prepare("select count(*) as total from eg_item where 39inputby=? and 39inputdate like ? and 38typeid=?");
					$stmt_total->bind_param("ssi",$get_user,$param,$row_fsb['38typeid']);
					$stmt_total->execute();
					$stmt_total->bind_result($num_results_affected);
					$stmt_total->fetch();				
					$stmt_total->close();
					
					echo "<font size=3>".$row_fsb['38type']." - ".$num_results_affected."</font><br/>";
				}
		?>
		<br/><br/>
		<a class='sButtonRedSmall' href='javascript:window.close();'><span class='fas fa-window-close'></span> Close</a>
	</div>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>
