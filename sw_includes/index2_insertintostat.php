<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
	
	$datePattern = date("D d/m/Y h:i a");
	
	if ($scstr_term<>'' && !isset($_GET['page']))
	{
		if ($sctype_select != 'Author')
			{$sctype_morph = 'EveryThing';}
		else
			{$sctype_morph = 'Author';}

		$scstr_term = mysqli_real_escape_string($GLOBALS["conn"],$scstr_term);

		$stmt_pattern = $new_conn->prepare("select id, 37keyword, 37freq from eg_userlog where 37keyword=? and 37type=?");
		$stmt_pattern->bind_param("ss", $scstr_term,$sctype_morph);
		$stmt_pattern->execute();
		$stmt_pattern->store_result();
			$num_results_Pattern = $stmt_pattern->num_rows;
		$stmt_pattern->bind_result($idPattern, $titlestatementPattern, $freqPattern);
		$stmt_pattern->fetch();
		$stmt_pattern->close();
													
		if ($num_results_Pattern == 0)
		{
			$freqPattern = 1;
		
			$stmt_insert = $new_conn->prepare("insert into eg_userlog values(DEFAULT,?,?,?,?)");
			$stmt_insert->bind_param("ssis", $scstr_term, $sctype_morph,$freqPattern,$datePattern);
			$stmt_insert->execute();
			$stmt_insert->close();		
		}
													
		else
		{
			$freqPattern = $freqPattern + 1;
			
			$stmt_update = $new_conn->prepare("update eg_userlog set 37freq=?, 37lastlog=? where id=?");
			$stmt_update->bind_param("ssi", $freqPattern, $datePattern, $idPattern);
			$stmt_update->execute();
			$stmt_update->close();										
		}
		
		$ip = $_SERVER['REMOTE_ADDR'];	
		
		$stmt_insert = $new_conn->prepare("insert into eg_userlog_det values(DEFAULT,?,?,?,?)");
		$stmt_insert->bind_param("ssss", $scstr_term, $datePattern, $ip, $sctype_morph);
		$stmt_insert->execute();
		$stmt_insert->close();																												  
	}																		
?>