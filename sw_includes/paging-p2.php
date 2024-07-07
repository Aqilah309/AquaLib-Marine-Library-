<?php
		defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
		
		if (isset($paging_type) && $paging_type == 2)
		{
			$row = mysqli_fetch_array($result_count);
			$num_results_affected_paging = $row["total"];
		}
		else
		{
			$row = mysqli_fetch_row(mysqli_query($GLOBALS["conn"],"SELECT FOUND_ROWS()"));
			$num_results_affected_paging = $row[0];
		}
												
		$maxPage = ceil($num_results_affected_paging/$rowsPerPage);
		$self = htmlspecialchars($_SERVER['PHP_SELF']);
?>