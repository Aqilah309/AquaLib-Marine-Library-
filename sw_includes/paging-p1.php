<?php
		defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
		
		$rowsPerPage = $system_wide_resultPerPage;
		$pageNum = 1;
		if(isset($currentPage)) {$pageNum = $currentPage;}			
		$offset = ($pageNum - 1) * $rowsPerPage;
?>