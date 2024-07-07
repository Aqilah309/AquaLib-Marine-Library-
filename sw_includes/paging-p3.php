<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
	
	echo "<table class=whiteHeader>";
		echo "<tr>";
			if ($pageNum > 1)
			{
				$page = $pageNum - 1;
				$prev = " [ <a href=\"$self?page=$page".$appendpaging."\">Prev</a> ] ";				
				$first = " [ <a href=\"$self?page=1".$appendpaging."\">First</a> ] ";
			} 
			else
			{
				$prev  = " [Prev] ";
				$first = " [First] ";
			}

			if ($pageNum < $maxPage)
			{
				$page = $pageNum + 1;
				$next = " [ <a href=\"$self?page=$page".$appendpaging."\">Next</a> ] ";					
				$last = " [ <a href=\"$self?page=$maxPage".$appendpaging."\">Last</a> ] ";
			} 
			else
			{
				$next = " [Next] ";
				$last = " [Last] ";
			}
	
			if ($num_results_affected_paging > $rowsPerPage) {
				echo "<td style='text-align:right;width:33%;'>" . $first . $prev . "</td><td style='text-align:center;width:34%;'> Page <strong>$pageNum</strong> of <strong>$maxPage</strong> </td><td style='text-align:left;width:33%;'>" . $next . $last . "</td>";
			}
		echo "</tr>";
	echo "</table>";
?>