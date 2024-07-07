<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../core.php';
	include '../sw_includes/functions.php';

	$thisPageTitle = "$subject_heading_as -Details";
	$_SESSION['whichbrowser'] = "sj";

	$get_subacr = htmlspecialchars($_GET["subacr"]);

	//check if get_subacr is not numeric then block access
	if (!is_numeric($get_subacr)) {
		echo "<script>window.location.replace('subjectbrowser.php');</script>";
		mysqli_close($GLOBALS["conn"]);
		exit();
	}
?>

<html lang='en'>

<head>
	<?php include '../sw_includes/header.php'; ?>
</head>

<body>

	<div style='text-align:center;'>
	
	<?php 		
		include '../sw_includes/loggedinfo.php';      
		
		echo "<hr>";

		$query_sub = "select 43subject, 43acronym from eg_subjectheading where 43subjectid=".$get_subacr;						
		$result_sub = mysqli_query($GLOBALS["conn"],$query_sub);
		$row_sub = mysqli_fetch_array($result_sub);

		if (mysqli_num_rows($result_sub) >= 1) 
		{
			$sub_name = $row_sub['43subject'];
			$sub_acronym = $row_sub['43acronym'];

			if (isset($_GET['page'])) {$currentPage = $_GET['page'];}
			include '../sw_includes/paging-p1.php';
			
			if ($subject_heading_selectable == "multi") {
				$query1 = "select id, 38title, 38typeid, 38source 
				from eg_item 
				where 41subjectheading like '%$sub_acronym".$subject_heading_delimiter."%'  
				or  41subjectheading like '$sub_acronym".$subject_heading_delimiter."%'  
				or  41subjectheading like '$sub_acronym' 
				order by 38title LIMIT $offset, $rowsPerPage";
			}
			else {
				$query1 = "select id, 38title, 38typeid, 38source from eg_item where 41subjectheading like '$sub_acronym' order by 38title LIMIT $offset, $rowsPerPage";
			}
			
			$result1 = mysqli_query($GLOBALS["conn"],$query1);

			if ($subject_heading_selectable == "multi") {
				$query_count = "select count(id) as total from eg_item where 41subjectheading like '%$sub_acronym".$subject_heading_delimiter."%' or  41subjectheading like '$sub_acronym".$subject_heading_delimiter."%'  or  41subjectheading like '$sub_acronym'";
			}
			else {
				$query_count = "select count(id) as total from eg_item where 41subjectheading like '$sub_acronym'";
			}
			
			$result_count = mysqli_query($GLOBALS["conn"],$query_count);

			$paging_type = 2;
			include '../sw_includes/paging-p2.php';	
	?>

			<table class=yellowHeader>
				<tr><td><?php echo $subject_heading_as;?>: 
				<?php 
					echo "<strong>$sub_name</strong> (<em>$num_results_affected_paging items</em>)";
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
								echo"</td>";

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
		}
		else {
			echo "<br/>No result found.<br/><br/>";
		}
		
		echo "<a class='sButton' href='subjectbrowser.php'><span class='fas fa-arrow-circle-left'></span> Go to $subject_heading_as browser</a> ";
	?>		
		
	</div>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>