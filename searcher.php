<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);	
	include 'core.php';
	include 'sw_includes/access_ip.php';
	include 'sw_includes/functions.php';
	$thisPageTitle = "Guest Mode";
	$_SESSION['ref'] = 'searcher.php';
	
	check_is_blocked("$blocked_file_location/","");

	//clear browser route
	unset($_SESSION['whichbrowser']);

	//clear all session variable for searching if sc = cl
	if (isset($_GET['sc']) && $_GET['sc'] == 'cl')
	{
		unset($_SESSION['sear_scstr']);
		unset($_SESSION['sear_sctype']);
		unset($_SESSION['sear_page']);
		if ($debug_search == 'yes') {echo "sc_cl | ";}
	}
	//limitation of use of sc variable. make sure that only cl is permitted. or will result in complete banned (to be implemented later.).
	else if (isset($_GET['sc']) && $_GET['sc'] != 'cl')
	{
		record_block("$blocked_file_location/");
	}

	//handling back from other pages -- if user manipulated the query string using url, and alter the scstr value, it will automatically unset the current page the user is in
	if (
		(isset($_GET['scstr']) && isset($_SESSION['sear_scstr']) && $_GET['scstr'] != $_SESSION['sear_scstr']) 
		|| (isset($_GET['sctype']) && isset($_SESSION['sear_sctype']) && $_GET['sctype'] != $_SESSION['sear_sctype'])
		)
	{
		unset($_SESSION['sear_page']);
		if ($debug_search == 'yes') {echo "back_handling | ";}
	}

	//handling scstr from search fields
	if (isset($_GET['scstr'])) 
	{
		$_SESSION['sear_scstr'] = just_clean($_GET['scstr']);//clean the string inserted by users
		$all_upper_scstr = strtoupper($_SESSION['sear_scstr']);//change them to upper
		checkstring_Exist_And_redirect_to_parent($all_upper_scstr,"SCSTR","searcher.php?sc=cl");//prevent user misusing scstr
	}

	//handling sctype from search fields
	if (isset($_GET['sctype'])) 
	{
		$_SESSION['sear_sctype'] = htmlspecialchars($_GET['sctype']);
		if (!is_numeric($_SESSION['sear_sctype']) && $_SESSION['sear_sctype'] != 'EveryThing')
		{
			record_block("$blocked_file_location/");
		}
	}
	
	//handling page from pagination
	if (isset($_GET['page'])) 
	{
		$_SESSION['sear_page'] = htmlspecialchars($_GET['page']);
		if (!is_numeric($_SESSION['sear_page']))
		{
			record_block("$blocked_file_location/");
		}
	}
	
	//debugging
	if ($debug_search == 'yes') 
	{
		echo " Session variables: ";
		if (isset($_SESSION['sear_scstr'])) {echo "<em>sear_scstr</em>:".$_SESSION['sear_scstr']." ";}
		if (isset($_SESSION['sear_sctype'])) {echo "<em>sear_sctype</em>:".$_SESSION['sear_sctype']." ";}
		if (isset($_SESSION['sear_page'])) {echo "<em>sear_page</em>:".$_SESSION['sear_page'];}
	}

	//if from app
	if (isset($_GET['from']) && $_GET['from'] == 'app') {$_SESSION['fromapp'] = true;}

?>
<html lang='en'>

<head>
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>
	
	<?php include 'sw_includes/loggedinfo.php'; ?>

	<br/>

	<?php if (!isset($_SESSION['sear_scstr'])) {include './sw_includes/popularkeywords.php';} ?>	

	<table class=whiteHeader>
		<tr><td>			
		<form  action="searcher.php" method="get" enctype="multipart/form-data" style="margin:auto;max-width:100%">							
			<input type="text" placeholder="Enter search terms" name="scstr" style='width:50%;font-size:14px' maxlength="255" value="<?php if (isset($_SESSION['sear_scstr'])) {echo $_SESSION['sear_scstr'];}?>" />
			<select style="width:20%;font-size:14px" id="sctype" name="sctype">
				<?php 
					$sctype_select = $_SESSION['sear_sctype'] ?? '';
											
					echo '<option value="EveryThing" '; if ($sctype_select == 'EveryThing') {echo 'selected';} echo '>Everything</option>';					
					$query_typelist = "select 38typeid, 38type from eg_item_type";
					$result_typelist = mysqli_query($GLOBALS["conn"],$query_typelist);			
					while ($row_typelist = mysqli_fetch_array($result_typelist))
					{
						echo '<option value="'.$row_typelist['38typeid'].'"'; if($row_typelist['38typeid']==$sctype_select) {echo ' selected';} echo '>'. $row_typelist['38type'] . '</option>'."\n";
					}
				?>
			</select>
			<button type="submit" style="color:black;"><span class="fa fa-search"></span> Search</button>
			<input type="hidden" name='sc' value='cl'/>
		</form>
		<tr><td>
			<?php if ($show_browser_bar_guest) {include './sw_includes/browser_bar.php';} ?>	
		</td></tr>
	</table>					


		
	<div style='text-align:center;'>
		<?php												
				//start paging 1
				if (isset($_SESSION['sear_page'])) {$currentPage = $_SESSION['sear_page'];}				
				include './sw_includes/paging-p1.php'; 											
				
				if (isset($_SESSION["sear_scstr"]) || isset($_SESSION["sear_sctype"]))
				{							
					$latest = "FALSE";
					$scstr_term = $_SESSION["sear_scstr"];	
					include './sw_includes/index2_insertintostat.php';//insert search term into log table		
					include './sw_includes/index2_s_allseing.php';								
				}				
				else //first time run only
				{															
					$latest = "TRUE";
					$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where 50item_status='1' order by id desc LIMIT $offset, $rowsPerPage";
				}		
																			
				$result1 = mysqli_query($GLOBALS["conn"],$query1);
				
				//start paging 2
				include './sw_includes/paging-p2.php';			
				
				//show table header depending on the user interaction with the page
				if ($latest == "FALSE")
				{
					echo "<table class=whiteHeaderNoCenter><tr><td>Total records found : $num_results_affected_paging</td></tr></table>";					
					include './sw_includes/index2_sgmtdsrch.php';//load segmented search box
				}				
				else
				{
					echo "<table class=whiteHeaderNoCenter><tr><td><strong>Latest addition to the database :</strong></td></tr></table>";
				}	
				
				//if function other than photo mode
				if ($system_function != 'photo')
				{
					$n = $offset + 1;
					echo "<table class=whiteHeaderNoCenter>";
					while ($myrow1 = mysqli_fetch_array($result1))
					{
						echo "<tr class=yellowHover>";
					
							$id2 = $myrow1["id"];					
							if ($searcher_marker_to_hide == null)
								{$titlestatement2 = reverseStringUltraClean($myrow1["38title"]);}
							else
								{$titlestatement2 = str_replace($searcher_marker_to_hide, "", $myrow1["38title"]);}
							$typestatement2 = $myrow1["38typeid"];
							$authorname2 = reverseStringUltraClean($myrow1["38author"]);
							$sumber2 = $myrow1["38source"];
							$inputdate2 = $myrow1["39inputdate"];
								$dir_year2 = substr("$inputdate2",0,4);
							$hits2 = $myrow1["41hits"];
							$link2 = $myrow1["38link"];													
							$fulltext2 = $myrow1["41fulltexta"];
							$isabstract2 = $myrow1["41isabstract"];
							$pdfattach2 = $myrow1["41pdfattach"];
							$localcallnum2 = $myrow1["38localcallnum"];
							$publication2 = $myrow1["38publication"];
							$instimestamp2 = $myrow1["41instimestamp"];						
							
							echo "<td style='text-align:center;vertical-align:top;width:50px;'>";
								echo "<a href=javascript:window.scrollTo(0,0)><img src='./sw_images/topofpage.gif' onmouseover=\"Tip('Go to top of this page')\" onmouseout=\"UnTip()\"></a><br/>";
								echo "<strong>$n</strong>";
							echo "</td>";
							
							if (!$detect->isMobile())
							{
								echo "<td style='text-align:center;vertical-align:top;color:#0066CC;font-size:10px;' width=28>";
									echo "<strong>".getTypeNameFromID($typestatement2)."</strong><br/><img width=24 src='./sw_images/docSmall.gif'>";
								echo "</td>";
							}
							
							echo "<td style='text-align:left;'>";				
								//title
								if (isset($scstr_term) && $scstr_term <> null)
									{echo " <a style='font-size:$searcher_title_font_size;' title='Click here to view detail' class='myclassOri' href='detailsg.php?det=$id2&highlight=$scstr_term'>".highlight(htmlspecialchars_decode($titlestatement2),$scstr_term)."</a>";}
								else
									{echo " <a style='font-size:$searcher_title_font_size;' title='Click here to view detail' class='myclassOri' href='detailsg.php?det=$id2'>".htmlspecialchars_decode($titlestatement2)."</a>";}
								
								//author
								if ($authorname2 != '') {
									echo "<br/><a style='font-size:$searcher_author_font_size;' title='Click here to search more titles from this author' class=myclass2 href='searcher.php?scstr=$authorname2&sctype=EveryThing&sc=cl'>$authorname2</a>";
								}
								else
								{
									if ($publication2 != ''){ echo "<br/><span style='font-size:$searcher_author_font_size;'>$publication2</span>";}
									else {echo "<br/><em>Unknown author</em>";}
								}					
								
								//hits view
								echo "<br/><em style='font-size:$searcher_hits_font_size;'>$hits2 hits</em><br/>";
							
								//indicators
								if ($searcher_show_icon_indicator)
								{
									if ($fulltext2 != null && $fulltext2 != '<html />')
									{
										if ($isabstract2 == 1)
											{echo "<img src='./sw_images/abstract_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('HTML Abstract')\" onmouseout=\"UnTip()\">";}
										else
											{echo "<img src='./sw_images/html_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('HTML Full Text')\" onmouseout=\"UnTip()\">";}	
									}

									if ($link2 <> null)
									{
										$link2 = urldecode($link2);
										if (substr($link2,0,4) != 'http') {$link2 = 'http://'.$link2;}
										echo " <img src='./sw_images/url_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('HTML Link Available')\" onmouseout=\"UnTip()\">";
									}	

									if(is_file("$system_docs_directory/$dir_year2/$id2"."_"."$instimestamp2.pdf")) 
											{echo " <img src='./sw_images/pdf_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('PDF Available')\" onmouseout=\"UnTip()\">";}
								}
															
								if ($detect->isMobile()) {echo "<hr>";}							
							echo "</td>";	

						echo "</tr>";																
						$n = $n +1 ;
					}
					echo "</table>";
				}
				
				//for photo mode
				else if ($system_function == 'photo')
				{					
					echo "<table style='border: 1px solid lightgrey; max-width:100%;overflow-x: auto;'>";				
						$n = $offset + 1;		
						echo "<tr>";	
							while ($myrow1 = mysqli_fetch_array($result1))
							{											
								$id2 = $myrow1["id"];				
								if ($searcher_marker_to_hide == null)
									{$titlestatement2 = $myrow1["38title"];}
								else
									{$titlestatement2 = str_replace($searcher_marker_to_hide, "", $myrow1["38title"]);}
								
								$authorname2 = $myrow1["38author"];
								if (!$authorname2) {$authorname2 = "N/A";}
								
								$inputdate2 = $myrow1["39inputdate"];
									$dir_year2 = substr("$inputdate2",0,4);
								$instimestamp2 = $myrow1["41instimestamp"];									
								
								echo "<td style='font-size:8pt;display: inline-block; padding: 5px; width:350px;'>";								
									echo "<a title='Click here to view detail' class='myclassOri' href='detailsg.php?det=$id2'>";
											if(is_file("$system_albums_watermark_directory/$dir_year2/$id2"."_"."$instimestamp2.jpg"))
												{echo "<img class='centered-and-cropped' src='sw_tools/image.php?d=$id2&t=t' width=300px height=300px>";}
											else
												{echo "<img src='sw_images/no_image.png' width=300px width=300px>";}									
									echo "</a>";
									echo "<br/>$titlestatement2<br/><span style='color:green;'>$authorname2</span>";
								echo "</td>";					
																								
								$n = $n +1 ;
							}
						echo "</tr>";
					echo "</table>";
				}

			//start paging 3
			if ($maxPage > 1)
			{
				$appendpaging = '';
				include './sw_includes/paging-p3.php';		
			}								
		
		?>		
	</div>
	
	<br/><hr>
	
	<?php include './sw_includes/footer.php'; ?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>