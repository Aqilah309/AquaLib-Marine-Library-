<?php 
	session_start();define('includeExist', TRUE); 
	include '../sw_includes/access_super.php';	
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	
	header("Content-type: text/csv");	
	header("Content-disposition: attachment;filename=output_".date("Ymd").".csv");
	
	$n = 1;
	$query = "SELECT  id, 38accessnum, 38typeid, 38location, 38isbn, 38issn, 38localcallnum, 38publication, 38title, 38author FROM eg_item;";
	$result = mysqli_query($GLOBALS["conn"],$query);
	$output = '';
	
	while ($myrow = mysqli_fetch_array($result))
	{
		$accessnum = $myrow["38accessnum"];
		
		$queryType = "SELECT 38type FROM eg_item_type WHERE 38typeid=".$myrow["38typeid"].";";
		$resultType = mysqli_query($GLOBALS["conn"],$queryType);
		$myrowType = mysqli_fetch_array($resultType);
		$type = $myrowType["38type"];		
		
		$location = $myrow["38location"];
		
		if ($myrow["38isbn"] == '') {
			$isbnissn = $myrow["38issn"];
		}
		else {
			$isbnissn = $myrow["38isbn"];
		}
			
		
		$queryNext = "SELECT 38localcallnum_b, 38publication_b, 38publication_c FROM eg_item2 WHERE eg_item_id=".$myrow["id"].";";
		$resultNext = mysqli_query($GLOBALS["conn"],$queryNext);
		$myrowNext = mysqli_fetch_array($resultNext);
		$localcallnum_b = $myrowNext["38localcallnum_b"];		
		$publication_b = $myrowNext["38publication_b"];		
		$publication_c = $myrowNext["38publication_c"];		
		
		$localcallnum = $myrow["38localcallnum"]." ".$myrowNext["38localcallnum_b"];
		$publication = $myrow["38publication"]." ".$publication_b." ".$publication_c;
		
		
		$title = str_replace('"','',$myrow["38title"])." ".$myrow["38author"];
		
		$output .= "\"$accessnum\",\"$type\",\"$location\",\"$isbnissn\",\"$localcallnum\",\"$publication\",\"$title\"\n";
	}	
	echo "$output";
?>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>