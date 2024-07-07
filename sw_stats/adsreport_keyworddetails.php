<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle  = "Keyword Stats";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>

	<br/>
	
	<div style="text-align:center">

		<?php
			if(isset($_GET['show']))
				{$show = $_GET['show'];}
			else 
				{$show = null;}

			if (isset($_GET['page'])) {$currentPage = $_GET['page'];}
			include '../sw_includes/paging-p1.php';
																				
			if ($show == 'popular')
				{$query_userlog = "select * from eg_userlog order by 37freq desc LIMIT $offset, $rowsPerPage";}		
			else
				{$query_userlog = "select * from eg_userlog order by id desc LIMIT $offset, $rowsPerPage";}
			
			$result_userlog = mysqli_query($GLOBALS["conn"],$query_userlog);			
			
			$query_count = "select count(*) as total from eg_userlog order by id desc";												
			$result_count = mysqli_query($GLOBALS["conn"],$query_count);
			
			$paging_type = 2;
			include '../sw_includes/paging-p2.php';																			

			echo "<table class=whiteHeaderNoCenter>";
				echo "<tr class=yellowHeaderCenter><td colspan=4>";
					echo "Total recorded unique search terms : <strong>$num_results_affected_paging</strong>";
					echo "<br/>Sort by: ";
						if (isset ($_GET['show']) && $show == 'popular')
							{echo "<a href='adsreport_keyworddetails.php?show=latest'>Latest keyword</a> | <strong>Keyword popularity</strong>";}
						else 
							{echo "<strong>Latest keyword</strong> | <a href='adsreport_keyworddetails.php?show=popular'>Keyword popularity</a>";}
				echo "</td></tr>";
				
				echo "<tr class=whiteHeaderNoCenter><td></td><td>Terms</td><td>Total Hits</td><td>Last used</td></tr>";
																
				$n = $offset + 1;				
				while ($myrow_userlog = mysqli_fetch_array($result_userlog))
				{
					echo "<tr class=yellowHover><td>$n</td><td style='text-align:left;'>".$myrow_userlog["37keyword"]."</td><td>".$myrow_userlog["37freq"]."</td><td>".$myrow_userlog["37lastlog"]."</td></tr>";
					$n = $n +1 ;
				}
			echo "</table>";
			
			$appendpaging = "&show=$show";
			include '../sw_includes/paging-p3.php'; 			
		?> 
	
		<br/><br/>
	
		<a class='sButton' href='adsreport.php?toggle=3'><span class='fas fa-arrow-circle-left'></span> Back to report page</a>				

	</div>	

	<hr>

	<?php include '../sw_includes/footer.php';?>

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>