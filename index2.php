<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 
	include 'sw_includes/access_isset.php';
	include 'core.php'; include 'sw_includes/access_allowed_adminip.php';
	include 'sw_includes/functions.php';	
	$thisPageTitle = "Administration";
	$_SESSION['ref'] = 'index2.php';
	
	//route tracing for future page
	$_SESSION['route1'] = '1';

	//clear browser route
	unset($_SESSION['whichbrowser']);
?>

<?php
	
	if ($_SESSION['username'] == 'admin' && ($aes_key_warning && $password_aes_key == "45C799DB3EBC65DFBC69A0F36F605E6CA2447CD519C50B7DA0D0D45D2B0F2431"))
	{
		echo "<html lang='en'><table style='width:100%;text-align:center;'><tr><td style='color:yellow;background-color:red;'>";
				echo "Please change AES KEY. All functions will not be available until you change this value. Follow instruction in config.default.php.
				<br/>Please keep this page runnning. After changing, refresh this page.";
		echo "</td></tr></table></html>";
		exit;
	}

	if ($_SESSION['needtochangepwd'])
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;url=sw_admin/passchange.php?upd=.g\" />"; exit;
	}

	//forward to depoadmin if sWADAH is set to function as self deposit repository
	if ($system_function == "depo")
	{
		echo "<meta http-equiv=\"refresh\" content=\"0;url=sw_depos/depoadmin.php?v=entry\" />"; exit;
	}
	
	//clear all session variable for searching if sc = cl
	if (isset($_GET['sc']) && $_GET['sc'] == 'cl')
	{
		unset($_SESSION['sear_scstr']);
		unset($_SESSION['sear_sctype']);
		unset($_SESSION['sear_page']);
		if ($debug_search == 'yes') {echo "sc = cl | ;</script>";}
	}

	//handling back from other pages -- if user manipulated the query string using url, and alter the scstr value, it will automatically unset the current page the user is in
	if ((isset($_GET['scstr']) && isset($_SESSION['sear_scstr']) && $_GET['scstr'] != $_SESSION['sear_scstr']) || (isset($_GET['sctype']) && isset($_SESSION['sear_sctype']) && $_GET['sctype'] != $_SESSION['sear_sctype'])) 
	{
		unset($_SESSION['sear_page']);
		if ($debug_search == 'yes') {echo "handling back from other pages | ";}
	}

	//session management for search fields
	if (isset($_GET['scstr'])) {$_SESSION['sear_scstr'] = just_clean($_GET['scstr']);}
	if (isset($_GET['sctype']) && (is_numeric($_GET['sctype']) || $_GET['sctype'] == 'EveryThing' || $_GET['sctype'] == 'Control Number' || $_GET['sctype'] == 'Author' )) {$_SESSION['sear_sctype'] = $_GET['sctype'];}
	if (isset($_GET['page']) && is_numeric($_GET['page'])) {$_SESSION['sear_page'] = $_GET['page'];}
	
	//debugging
	if ($debug_search == 'yes') 
	{
		echo " Session variables>> ";
		if (isset($_SESSION['sear_scstr'])) {echo "<em>sear_scst</em>:".$_SESSION['sear_scstr']." ";}
		if (isset($_SESSION['sear_sctype'])) {echo "<em>sear_sctyp</em>:".$_SESSION['sear_sctype']." ";}
		if (isset($_SESSION['sear_page'])) {echo "<em>sear_page</em>:".$_SESSION['sear_page'];}
	}
?>

<html lang='en'>

<head><?php include 'sw_includes/header.php'; ?></head>

<body>
	
	<?php include 'sw_includes/loggedinfo.php'; ?>		

	<?php 
		echo "<table class=whiteHeaderNoCenter><tr><td>";
				echo "<span style='color:blue;'>You have logged in as : </span>";
				echo "<strong>";
					echo $_SESSION['username']."  <a href='sw_stats/adsreport_details.php'><span class=\"fas fa-info-circle\" onmouseover=\"Tip('View your data input history')\" onmouseout=\"UnTip()\"></span></a>";
					$username = $_SESSION['username']; $lastlogin = $_SESSION['lastlogin'];					
				echo "</strong>";
				echo "<br/><em><span style='color:black;font-size:10px'>Last logged in: $lastlogin</span></em>";	
				echo "<br/><em><span style='color:grey;font-size:10px'>Current Session ID: ".session_id()."</span></em>";	
				if ($system_mode == "demo") {echo "<br/><br/><span style='color:red;font-size:10px'>You are running DEMO mode. Some functionality are disabled.</span>";}					
				if ($system_mode == "maintenance") {echo "<br/><br/><span style='color:red;font-size:10px'>You are running MAINTENANCE mode. User functionality are disabled.</span>";}					
				echo "<br/><br/>Running sWADAH $system_version<br/>".showDevelopedRight();
		echo "</td></tr></table>";					
	?>		
																	
	<hr>
							
	<div style='text-align:center;background-color:white;padding-top:20px;'>					
		<form action="index2.php" method="get" enctype="multipart/form-data">
			
			<input type="text" placeholder="Enter search terms here and press Search" name="scstr" style="width:50%;font-size:14px" maxlength="255" 
				value="<?php if (isset($_SESSION["sear_scstr"]) && $_SESSION["sear_scstr"] <> '') {echo $_SESSION["sear_scstr"];}?>"/>						
						
			<select style="width:15%;font-size:14px" id="sctype" name="sctype" style="font-size:11px;">
			<?php 
				if (isset($_SESSION['sear_sctype']))
					{$sctype_select = $_SESSION['sear_sctype'];}
				else
					{$sctype_select = '';}
					
				echo '<option value="EveryThing" '; if ($sctype_select == 'EveryThing') {echo 'selected';} echo ' >Everything</option> ';
				
				$query_typelist= "select 38typeid, 38type from eg_item_type";
				$result_typelist = mysqli_query($GLOBALS["conn"],$query_typelist);		
				while ($row_typelist = mysqli_fetch_array($result_typelist))
					{
						echo '<option value="'.$row_typelist['38typeid'].'"'; if($row_typelist['38typeid']==$sctype_select) {echo ' selected';} echo '> Type: '. $row_typelist['38type'] . '</option>'."\n";
					}			
		
				if (isset($_SESSION['username']))
					{
						echo '<option value="Control Number" '; if ($sctype_select == 'Control Number') {echo 'selected';} echo ' >Control Number</option>';
						echo '<option value="Author" '; if ($sctype_select == 'Author') {echo 'selected';} echo ' >Main Author</option> ';
					}
			?>
			</select>	

			<input type="hidden" name='sc' value='cl'/>
			<button type="submit" style="color:black;"><span class="fa fa-search"></span> Search</button>

		</form><br/>
		<?php 
			if ($show_browser_bar_admin) {include './sw_includes/browser_bar.php';}
		?>
	</div>				
							
	<div style='text-align:center;'>
		<?php
			//start paging 1
			if (isset($_SESSION['sear_page'])) {$currentPage = $_SESSION['sear_page'];}			
			include './sw_includes/paging-p1.php';							
			
			if (isset($_SESSION["sear_scstr"]) || (isset($_SESSION["sear_sctype"]) && $_SESSION['sear_sctype'] <> NULL))
			{
				$scstr_term = $_SESSION["sear_scstr"];									
				$latest = "FALSE";				
				include './sw_includes/index2_s_boolean.php';											
			}
			
			else 
			{
				$latest = "TRUE";
				$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item order by id desc LIMIT $offset, $rowsPerPage";
			}
									
			$time_start = getmicrotime();												
			$result1 = mysqli_query($GLOBALS["conn"],$query1);
													
			//start paging 2
			include './sw_includes/paging-p2.php';	

			$time_end = getmicrotime();
			$time = round($time_end - $time_start, 5);

			if ($latest == "FALSE")
			{
				echo "<table class=whiteHeaderNoCenter><tr><td>";
					echo "<strong>Total records found :</strong> $num_results_affected_paging ";
					echo "<strong>in</strong> $time seconds <strong>for</strong> ' $scstr_term '";
				echo "</td></tr></table>";
				
				include './sw_includes/index2_sgmtdsrch.php';//load segmented search box
			}
									
			else
			{												
				echo "<table class=whiteHeaderNoCenter><tr><td><strong>Latest addition to the database : </strong></td></tr></table>";					
			}
	
			echo "<table class=whiteHeaderNoCenter>";										
																
			$n = $offset + 1;
	
			while ($myrow1 = mysqli_fetch_array($result1))
			{
				echo "<tr class=yellowHover>";
				
					$id2 = $myrow1["id"];
					$status2 = $myrow1["38status"];
					$titlestatement2 = reverseStringUltraClean($myrow1["38title"]);
					$typestatement2 = $myrow1["38typeid"];
					$authorname2 = reverseStringUltraClean($myrow1["38author"]);
					$link2 = $myrow1["38link"];
					$inputdate2 = $myrow1["39inputdate"];
					$hits2 = $myrow1["41hits"];
					$inputby2 = $myrow1["39inputby"];
					$fulltext2 = $myrow1["41fulltexta"];
					$pdfattach2 = $myrow1["41pdfattach"];
					$imageatt2 = $myrow1["41imageatt"];	
					$pdfattach_fulltext2 = $myrow1["41pdfattach_fulltext"];
					$isabstract2 = $myrow1["41isabstract"];
					$localcallnum2 = $myrow1["38localcallnum"];
					$instimestamp2 = $myrow1["41instimestamp"];
					$accessnum2 = $myrow1["38accessnum"];
					$item_status2 = $myrow1["50item_status"];
					$reference2 = $myrow1["41reference"];
					$dir_year2 = substr("$inputdate2",0,4);			
					
					echo "<td style='text-align:center;vertical-align:top;' width=40>";
						echo "<a href=javascript:window.scrollTo(0,0)><img src='./sw_images/topofpage.gif' onmouseover=\"Tip('Go to top of this page')\" onmouseout=\"UnTip()\"></a><br/>";
					echo "$n</td>";

					if ($system_function == 'photo')
					{
						echo "<td style='text-align:center;vertical-align:top;' width=40>";
							echo "<img class='centered-and-cropped' src='sw_tools/image.php?d=$id2&t=t' width=64px height=64px onerror=this.src='sw_images/no_image.png'>";
						echo "</td>";
					}
										
					echo "<td style='text-align:left;vertical-align:top;'>";
						if (!$_SESSION['needtochangepwd']) {
							echo "<a class=myclassOri href='details.php?det=$id2'>";
								if (isset($scstr_term) && $scstr_term <> null) {echo highlight($titlestatement2,$scstr_term);}
								else {echo $titlestatement2;}
							echo "</a>";
						}
						else
							{echo $titlestatement2;}

						if ($authorname2 != '')
							{echo "<br/><a class=myclass2 href='index2.php?scstr=$authorname2&sctype=Author&sc=cl'>$authorname2</a>";}
						
						if ($item_status2 == '1')
						{
							echo "<br/>".getTypeNameFromID($typestatement2)."<br/>";

							if ($fulltext2 != null && $fulltext2 != '<html />')
							{
								if ($isabstract2 == 1)
									{echo "<img src='./sw_images/abstract_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('HTML Abstract.')\" onmouseout=\"UnTip()\">";}
								else
									{echo "<img src='./sw_images/html_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('HTML Full Text.')\" onmouseout=\"UnTip()\">";}
							}

							if ($link2 <> NULL)
							{
								$link2 = urldecode($link2);
								if (substr($link2,0,4) != 'http') {$link2 = 'http://'.$link2;}
								{echo " <a href='$link2' target='_blank'><img src='./sw_images/url_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('Quick access to online version of this material.')\" onmouseout=\"UnTip()\"></a>";}
							}						
												
							if(is_file("$system_docs_directory/$dir_year2/$id2"."_"."$instimestamp2.pdf")) 
								{echo " <a href='$system_docs_directory/$dir_year2/$id2"."_"."$instimestamp2.pdf' target='_blank'><img src='./sw_images/pdf_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('PDF Available.')\" onmouseout=\"UnTip()\"></a>";}
	
							if(is_file("$system_albums_directory/$dir_year2/$id2"."_"."$instimestamp2.jpg")) 
								{echo " <img width=24 src='./sw_images/image_yes.png' alt='Click to view' onmouseover=\"Tip('Photo Available')\" onmouseout=\"UnTip()\">";}
													
							if ($pdfattach_fulltext2 != '')
								{echo " <img src='./sw_images/pdf_yes_indexed.png' width=24 alt='Click to view' onmouseover=\"Tip('PDF contents indexed in the database.')\" onmouseout=\"UnTip()\">";}

							if(is_file("$system_pdocs_directory/$dir_year2/$id2"."_"."$instimestamp2.pdf"))
								{echo " <img src='./sw_images/pdf_yes_g.png' width=24 alt='Click to view' onmouseover=\"Tip('Guest File Available.')\" onmouseout=\"UnTip()\">";}
							
							if ($reference2 != '')
								{echo " <img src='./sw_images/reference_yes.png' width=24 alt='Click to view' onmouseover=\"Tip('Reference is entered.')\" onmouseout=\"UnTip()\">";}
						}
						if ($item_status2 == '0')				
							{echo "<br/><em>Item is undiscoverable.</em>";}
					echo "</td>";
													
					echo "<td style='vertical-align:top;font-size:10px;' width=100>";
						switch ($status2)	{
							case 'AVAILABLE' : $colorStatus = 'green'; break;
							case 'FINALPROCESSING' : $colorStatus = 'blue'; break;
							case 'EMBARGO' : $colorStatus = 'red'; break;
							default: $colorStatus = 'orange'; break;
						}		
						echo "<span style='color:$colorStatus;'>$status2</span>
								<br/>
								<strong><span style='color:black;'>Added by :</span>
								<br/>
								</strong>".namePatronfromUsername($inputby2)."
								<br/>
								<strong><span style='color:black;'>Date Added</span> :</strong> $inputdate2 ";
					echo "</td>";					
				
				echo "</tr>";								
				$n = $n +1 ;
			}
			echo "</table>";							
								
		//start paging 3
		if ($maxPage > 1)
		{
			$appendpaging = '';
			include './sw_includes/paging-p3.php';		
		}										
	
		?>			
	</div>
	
	<hr>
		
	<?php include './sw_includes/footer.php';?>	

</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>