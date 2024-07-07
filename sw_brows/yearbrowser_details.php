<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php';
	include '../sw_includes/functions.php';
	$thisPageTitle = "Year -Details";
	$_SESSION['whichbrowser'] = "yr";

	$get_subacr = htmlspecialchars($_GET["subacr"]);

	//check if get_subacr is not numeric then block access
	if (!is_numeric($get_subacr)) {
		echo "<script>window.location.replace('yearbrowser.php');</script>";
		mysqli_close($GLOBALS["conn"]);
		exit();
	}
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>

	<?php 		
		include '../sw_includes/loggedinfo.php';          
            
		if (isset($_GET['page'])) {$currentPage = $_GET['page'];}
		include '../sw_includes/paging-p1.php';
		
		$query1 = "select eg_item.id as id, eg_item.38title as 38title, eg_item.38typeid as 38typeid, eg_item.38source as 38source 
		from eg_item left join eg_item2 on eg_item.id=eg_item2.eg_item_id 
		where eg_item2.38publication_c like '$get_subacr' 
		group by id order by 38title LIMIT $offset, $rowsPerPage";
		$result1 = mysqli_query($GLOBALS["conn"],$query1);
		
		$query_count = "select count(distinct(eg_item2.eg_item_id)) as total from eg_item left join eg_item2 on eg_item.id=eg_item2.eg_item_id where eg_item2.38publication_c like '$get_subacr'";
		$result_count = mysqli_query($GLOBALS["conn"],$query_count);

		$paging_type = 2;
		include '../sw_includes/paging-p2.php';	
	?>
						
	<hr>
	
	<div style='text-align:center;'>

		<table class=yellowHeader>
			<tr><td>Year: 
			<?php 
				echo "<strong>".$_GET["subacr"]."</strong> (<em>$num_results_affected_paging items</em>)";
			?>
			</td></tr>
		</table>

		<table class=whiteHeaderNoCenter>
			<tr class=whiteHeader style='text-decoration:underline;'><td></td><td>Title</td><td style='width:150;'>Type</td></tr></tr>
			<?php																												
					$n = $offset + 1;
					
					while ($myrow1 = mysqli_fetch_array($result1))
					{
						echo "<tr class=yellowHover>";						
							$id2 = $myrow1["id"];
							$type2 = $myrow1["38typeid"];
							if ($searcher_marker_to_hide == null) {
								$title2 = reverseStringUltraClean($myrow1["38title"]);
							}
							else {
								$title2 = reverseStringUltraClean(str_replace($searcher_marker_to_hide, "", $myrow1["38title"]));
							}
							$source2 = $myrow1["38source"];
							
							echo "<td width=40>$n</td>";

							echo "<td style='text-align:left;'>";
							if (!isset($_SESSION['username'])) {
								echo "<a class=thisonly href='../detailsg.php?det=$id2'>$title2</a>";
							}
							else {
								echo "<a class=thisonly href='../details.php?det=$id2'>$title2</a>";
							}
							echo "</td>";

							echo "<td>";
								echo getTypeNameFromID($type2);						
							echo "</td>";
						echo "</tr>";
																
						$n = $n +1 ;
					}											
			?>
		</table>
		
		<?php 
			$appendpaging = "&subacr=$get_subacr";
			include '../sw_includes/paging-p3.php'; 
		?>
		
		<br/>
		
		<?php
			echo "<a class='sButton' href='yearbrowser.php'><span class='fas fa-arrow-circle-left'></span> Go to Year browser</a> ";
		?>		
		
	</div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>