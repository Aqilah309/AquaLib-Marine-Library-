<?php
		defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
		
		//all seing search for title and author - available only for guest mode
		$queryAppend = "";
		if ($sctype_select != 'EveryThing')
		{
			$queryAppend = "and 38typeid = '$sctype_select'";
		}
			
		$scstr_term = mysqli_real_escape_string($GLOBALS["conn"],$scstr_term);

		if ($scstr_term == '')
		{
			$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where id<>0";
			$query1 .= " $queryAppend and 50item_status='1' order by id desc LIMIT $offset, $rowsPerPage";
		}
		else
		{
			include 'common_wordlist.php';		 
			 
			$query1 = "select SQL_CALC_FOUND_ROWS *, match (38title,38author) against ('$scstr_term' in boolean mode) as score from eg_item where id<>0";
			$query1 .= " $queryAppend and 50item_status='1' and match (38title,41fulltexta,41pdfattach_fulltext,38author,50search_cloud)";
			$query1 .= " against ('$scstr_term' in boolean mode) order by score desc LIMIT $offset, $rowsPerPage";
		}

?>