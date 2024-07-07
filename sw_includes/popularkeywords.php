<?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");
	
	$datePatternP = date("D d/m/Y");
	$queryP = "select 37keyword, 37type, 37freq  from eg_userlog where 37lastlog like '$datePatternP%' order by 37freq desc LIMIT 0, 10";
	$resultP = mysqli_query($GLOBALS["conn"],$queryP);
	$num_resultsP = mysqli_num_rows($resultP);
	
	if ($num_resultsP <> 0)
	{
	$n=1;
		echo "<table class=whiteHeader>";
		echo "<tr><td colspan=5><img src='./sw_images/favorites.gif'> ";
		echo "<span style='color:maroon;'><strong>Today's popular searches :</strong></span></td></tr>";
		while ($myrowP=mysqli_fetch_array($resultP))
			{
				if (($n == 6) || ($n == 1))
					{echo "<tr><td>";}
				else
					{echo "<td>";}
				
					$keywordP=$myrowP["37keyword"];
					$typeP=$myrowP["37type"];
					$freqP=$myrowP ["37freq"];//variable for showing search term frequency
					
					$keywordPex=urlencode($keywordP);//to encode + sign properly before displaying to popular searches
					echo "<a href='searcher.php?sc=cl&scstr=$keywordPex&sctype=$typeP'>";
					echo "$keywordP";
					if ($typeP == 'Author')
						{echo " (Author)";}
					if ($searcher_show_frequency_of_toprateditems)
						{echo " ($freqP)";}
					echo "</a>";

				echo"</td>";
				
				if (($n == 5) || ($n == 10))
					{echo "</td></tr>";}
				else
					{echo "</td>";}
				
				$n++;
			}
		echo "</table>";
	}
?>										