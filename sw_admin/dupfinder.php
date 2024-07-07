<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_isset.php';
	include '../core.php';include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Duplicate Finder";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>
	
	<hr>
			
	<div style='text-align:center'>
		<?php
				
			$query_dup = "select count(*) as num, 38title, 38typeid from eg_item ";
			if (isset($_GET['typeref']) && is_numeric($_GET['typeref']) && ($_GET['typeref'] != 'All')) {
				$query_dup .= "where 38typeid=".mysqli_real_escape_string($GLOBALS["conn"], $_GET['typeref'])." ";
			}
			$query_dup .= "group by 38title having count(*) > 1 order by 38typeid,38title";
			
			$result_dup = mysqli_query($GLOBALS["conn"],$query_dup);																											

		?>
				<table class=whiteHeader><tr class=yellowHeaderCenter><td>
					<strong>Detected Duplicates </strong>
					<br/><br/>
					<form action="dupfinder.php" method="get" enctype="multipart/form-data">
						<em>Filter by :</em>
						<select name="typeref">	
						<option value='All'>All</option>
						<?php
							$query_type = "select 38typeid, 38type from eg_item_type";
							$result_type = mysqli_query($GLOBALS["conn"],$query_type);
							
							while ($myrow_type=mysqli_fetch_array($result_type))
								{
									$type=$myrow_type["38type"];
									$typeid=$myrow_type["38typeid"];
									echo "<option value='$typeid' ";
									if (isset($_GET['typeref']) && $_GET['typeref'] == $typeid) {
										echo "selected";
									}
									echo ">$type</option>";
								}
						?>						
						</select>
						<input type="submit" name="do" value="Filter" />
					</form>
				</td></tr></table>		

		<?php						
				echo "<table class=whiteHeader>";										
					echo "<tr style='text-decoration:underline;'>";
						echo "<td></td>";
						echo "<td style='text-align:left;'>Title</td>";
						echo "<td width=150>Type</td>";
						echo "<td width=65>Duplicate</td>";
					echo "</tr>";
																					
					$n = 1;											
					while ($myrow_dup = mysqli_fetch_array($result_dup))
					{
						echo "<tr class=yellowHover>";
							$num2 = $myrow_dup["num"];
							$titlestatement2 = $myrow_dup["38title"];
							$type2 = $myrow_dup["38typeid"];
							
							echo "<td width=50 style='text-align:center;'>$n</td>";
							
							$titlestatement2converted = str_replace('\"', '&#34;', $titlestatement2);//replace all dual quotes with &#34;
							$titlestatement2converted = str_replace('\'s', '', $titlestatement2converted);//removes all single quote with s
							$titlestatement2converted = str_replace('\'', '', $titlestatement2converted);//removes all single quotes
							$searchurl = '../index2.php?scstr='.$titlestatement2converted.'&sctype=EveryThing&onlytitle=yes&mf=2';

							echo "<td style='text-align:left;'><a href=\"$searchurl\">$titlestatement2</a></td>";
							
							echo "<td>".getTypeNameFromID($type2)."</td>";
							
							echo "<td>$num2</td>";
						echo "</tr>";																			
						$n = $n +1 ;
					}
				echo "</table>";												
		?>
	</div>

	<hr>
		
	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>