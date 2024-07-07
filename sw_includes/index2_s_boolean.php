<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

	if (isset($_GET['mf']) && is_numeric($_GET['mf'])) {$appendmflimit = $_GET['mf'];}//total number of result from duplicate finder page
	else {$appendmflimit = "$offset, $rowsPerPage";}

	$queryAppendVisibleOnly = "and 50item_status='1'";//show only item with item_status=1

	if ($sctype_select == 'EveryThing')
	{
		if ($scstr_term == '')
		{
			$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item ";
		 	$query1 .= " order by id desc LIMIT $appendmflimit";
		}
		else
		{
			include 'common_wordlist.php';		 
		
			$query1 = "select SQL_CALC_FOUND_ROWS *, match (38title) against ('$scstr_term' in boolean mode) as score from eg_item where ";
			$query1 .= " match (";
			
			//if isset onlytitle, then ignore below
			if (isset($_GET['onlytitle']) && $_GET['onlytitle'] == 'yes')
				{$query1 .= "38title";}
			else
				{$query1 .= "38title,41fulltexta,41pdfattach_fulltext,50search_cloud";}
				
			$query1 .= ") against ('$scstr_term' in boolean mode)";
			$query1 .= " order by score desc LIMIT $appendmflimit";
		}
	}
	
	else if (isset($_SESSION['username']) && ($sctype_select == 'Control Number'))
		{
			if (is_numeric($scstr_term))
			{
				if ($scstr_term <> '')
					{$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where id=$scstr_term LIMIT $appendmflimit";}
				else
					{$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item LIMIT $appendmflimit";}
			}
			else
			{
				$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item LIMIT $appendmflimit";
				echo "<script>alert('The input you have typed is not numerical. Please retype.');</script>";														
			}
		}
	
	else if ($sctype_select == 'Author')
		{
			if ($scstr_term <> '')
				{$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where 38author like '%$scstr_term%' LIMIT $appendmflimit";}
			else
				{$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item LIMIT $appendmflimit";}
		}
	
	else
	{		
		if ($scstr_term == '')
		{
			$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where ";
		 	$query1 .= " 38typeid = '$sctype_select' order by id desc LIMIT $appendmflimit";
		}
		 
		else
		{
			include 'common_wordlist.php';
			 
			$query1 = "select SQL_CALC_FOUND_ROWS *, match (38title) against ('$scstr_term' in boolean mode) as score from eg_item where ";
			$query1 .= " 38typeid = '$sctype_select' and match (38title,41fulltexta,41pdfattach_fulltext,50search_cloud)";
			$query1 .= " against ('$scstr_term' in boolean mode)";
			$query1 .= " order by score desc LIMIT $appendmflimit";														
		}
	}

?>