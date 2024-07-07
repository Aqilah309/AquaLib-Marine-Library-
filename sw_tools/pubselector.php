<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';		
	$thisPageTitle = "Publisher Selector";
?>

<html lang='en'>

<head></head>

<body>
	<script language="JavaScript">
		function pick(symbol) {
		  if (window.opener && !window.opener.closed)
			{
				window.opener.document.swadahform.publication1_b.value = symbol;
			}
		 	window.close();
		}
	</script>
	
	Select a publisher related to the item :
	
	<hr>	
						
	<u>ID</u> <u>Publisher</u>
		
	<br/><br/>
	
	<?php		
		$queryC = "select 43publisher, 43acronym from eg_publisher order by 43acronym";
		$resultC = mysqli_query($GLOBALS["conn"],$queryC);
		
		while ($myrowC = mysqli_fetch_array($resultC))
			{
				$publisher = $myrowC["43publisher"];
				$pubAcr = $myrowC["43acronym"];
				echo "<span style='font-size:14px'>$pubAcr : <a href=\"javascript:pick('$publisher')\">$publisher</a></span><br/><br/>";
			}
	?>
	
	<br/><br/>

	<div style="text-align:center">[<a href='javascript:window.close();'>Close</a>]</div>
	<br/>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>