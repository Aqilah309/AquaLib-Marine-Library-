<?php 
	/*
	sWadah SEARCHER API
	version 2.1.20220330.PS Beta
	Welcome to sWADAH Searcher API. 
	If you can read this, you are probably want to change something in this sea of codes.
	*/

	define('includeExist', TRUE);	
	include 'core.php';
	include 'sw_includes/functions.php';	

	check_is_blocked("$blocked_file_location/","");

	if (isset($_GET['sctype']) && (!is_numeric($_GET['sctype']) && $_GET['sctype'] != 'EveryThing')) {
		record_block("$blocked_file_location/");
	}
	if (isset($_GET['sctype'])) {$sctype_select = $_GET['sctype'];} else {$sctype_select = 'EveryThing';}
	
	if (isset($_GET['scstr'])){ $scstr_term = mysqli_real_escape_string($GLOBALS["conn"],just_clean($_GET['scstr']));} else {$scstr_term = '';}

	if (isset($_GET['sctype']) && isset($_GET['scstr']))
	{		
		if ($scstr_term == '' && $sctype_select == 'EveryThing')
		{
			$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where id<>0 and 50item_status='1' order by id desc";
			
			$stmt = $new_conn->prepare($query1);
			
		}
		else if ($scstr_term == '' && is_numeric($sctype_select))
		{
			$query1 = "select SQL_CALC_FOUND_ROWS * from eg_item where id<>0 and 38typeid = ? and 50item_status='1' order by id desc";
			
			$stmt = $new_conn->prepare($query1);
			$stmt->bind_param("s", $queryAppend);
		}
		else if ($scstr_term != '' && $sctype_select == 'EveryThing')
		{
			include 'sw_includes/common_wordlist.php';		 
			 
			$query1 = "
			select SQL_CALC_FOUND_ROWS *, match (38title,38author) against (? in boolean mode) as score from eg_item where id<>0 
			and 50item_status='1' and match (38title,41fulltexta,41pdfattach_fulltext,38author,50search_cloud) against (? in boolean mode) order by score desc
			";
			
			$stmt = $new_conn->prepare($query1);
			$stmt->bind_param("ss",$scstr_term,$scstr_term);
		}
		else if ($scstr_term != '' && is_numeric($sctype_select))
		{
			include 'sw_includes/common_wordlist.php';		 
			 
			$query1 = "
			select SQL_CALC_FOUND_ROWS *, match (38title,38author) against (? in boolean mode) as score from eg_item where id<>0 
			and 38typeid = ? and 50item_status='1' and match (38title,41fulltexta,41pdfattach_fulltext,38author,50search_cloud) against (? in boolean mode) order by score desc
			";
			
			$stmt = $new_conn->prepare($query1);
			$stmt->bind_param("sss",$scstr_term,$sctype_select,$scstr_term);
		}

		$stmt->execute();
		$result_term = $stmt->get_result();

		$posts = array();
		while ($myrow_term = $result_term->fetch_assoc())
		{	
			$posts[] = array(
				'id'=>$myrow_term["id"],
				'title'=>htmlspecialchars($myrow_term["38title"]),
				'author'=>htmlspecialchars($myrow_term["38author"]),
				'type'=>getTypeNameFromID($myrow_term["38typeid"]),
				'swadah_link'=>$system_path."detailsg.php?det=".$myrow_term['id']
			);
		}
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 

	}

	else if (isset($_GET['scpub']))
	{
		$scpub2 = htmlspecialchars($_GET['scpub']);
		
		$param = "%".$scpub2."%";
		$stmt_term = $new_conn->prepare("
		select 
		eg_item.id as id, eg_item.38title as 38title, eg_item.38author as 38author, eg_item.38typeid as 38typeid, 
		eg_item2.38publication_b as 38publication_b 
		from eg_item inner join eg_item2 on eg_item.id=eg_item2.eg_item_id where eg_item2.38publication_b like ?
		");
		$stmt_term->bind_param("s", $param);//s string
		$stmt_term->execute();
		$result_term = $stmt_term->get_result();
		
		$posts = array();

		while($myrow_term = $result_term->fetch_assoc())	
		{	
			$posts[] = array(
				'id'=>$myrow_term["id"],
				'title'=>htmlspecialchars($myrow_term["38title"]),
				'author'=>htmlspecialchars($myrow_term["38author"]),
				'type'=>getTypeNameFromID($myrow_term["38typeid"]),
				'publication'=>htmlspecialchars($myrow_term["38publication_b"]),
				'swadah_link'=>$system_path."detailsg.php?det=".$myrow_term['id']
			);
		}
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else if (isset($_GET['scsub']))
	{
		$scsub2 = just_clean($_GET['scsub']);

		$param = "%".$scsub2."%";
		$stmt_term = $new_conn->prepare("select id, 38title, 38author, 38typeid, 41subjectheading from eg_item where 41subjectheading like ?");
		$stmt_term->bind_param("s", $param);//s string
		$stmt_term->execute();
		$result_term = $stmt_term->get_result();

		$posts = array();
		while ($myrow_term = mysqli_fetch_array($result_term))
		{	
			$posts[] = array(
				'id'=>$myrow_term["id"],
				'title'=>htmlspecialchars($myrow_term["38title"]),
				'author'=>htmlspecialchars($myrow_term["38author"]),
				'type'=>getTypeNameFromID($myrow_term["38typeid"]),
				'subject'=>htmlspecialchars($myrow_term["41subjectheading"]),
				'swadah_link'=>$system_path."detailsg.php?det=".$myrow_term['id']
			);
		}
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else if (isset($_GET['listype']) && is_numeric($_GET['listype']) && $_GET['listype'] == '1')
	{
		$stmt_term = $new_conn->prepare("select 38typeid, 38type from eg_item_type");
		$stmt_term->execute();
		$result_term = $stmt_term->get_result();

		$posts = array();	
		while ($row_typelist = mysqli_fetch_array($result_term))
		{
			$posts[] = array(
				'id'=>$row_typelist['38typeid'],
				'type'=>$row_typelist['38type']
			);
		}		
		array_push($posts,array(
			'id'=>'EveryThing',
			'type'=>'This will list everything'));
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else if (isset($_GET['listpub']) && is_numeric($_GET['listpub']) && $_GET['listpub'] == '1')
	{
		$stmt_term = $new_conn->prepare("select 43pubid, 43acronym, 43publisher from eg_publisher");
		$stmt_term->execute();
		$result_term = $stmt_term->get_result();
		
		$posts = array();		
		while ($row_publist = mysqli_fetch_array($result_term))
		{
			$posts[] = array(
				'id'=>$row_publist['43pubid'],
				'acronym'=>$row_publist['43acronym'],
				'publisher'=>$row_publist['43publisher']
			);
		}		
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else if (isset($_GET['listsub']) && is_numeric($_GET['listsub']) && $_GET['listsub'] == '1')
	{
		$stmt_term = $new_conn->prepare("select 43subjectid, 43acronym, 43subject from eg_subjectheading");
		$stmt_term->execute();
		$result_term = $stmt_term->get_result();

		$posts = array();		
		while ($row_sublist = mysqli_fetch_array($result_term))
		{
			$posts[] = array(
				'id'=>$row_sublist['43subjectid'],
				'acronym'=>$row_sublist['43acronym'],
				'subject'=>$row_sublist['43subject']
			);
		}		
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else if ((isset($_GET['itemid']) && is_numeric($_GET['itemid'])) || (isset($_GET['mitemid']) && is_numeric($_GET['mitemid'])))
	{
		if (isset($_GET['itemid'])) {$iid = $_GET['itemid'];}
		else if (isset($_GET['mitemid'])) {$iid = $_GET['mitemid'];}

		$stmt_detail = $new_conn->prepare("select * from eg_item where id=?");
		$stmt_detail->bind_param("i", $iid);//i integer
		$stmt_detail->execute();
		$result_detail = $stmt_detail->get_result();
		$row_detail = $result_detail->fetch_assoc();
		
		if ($row_detail["41isabstract"] == 1) {$fulltext = 'abstract';}
		else {$fulltext = 'fulltext';}

		//generate downloadkey -start
		if(empty($_SERVER['REQUEST_URI'])) {$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];}				
		$url = preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);			
		$folderpath = $system_path;
		$key = uniqid(md5(rand()));					
		$time = date('U');					
		$ip_address = $_SERVER["REMOTE_ADDR"];		
		//generate downloadkey -end

		$guest_file = "";
		$pdocs_file = "";
		$image_file = "";
		$albums_file = "";

		if ($allow_guest_access_to_pt && $row_detail["41pdfattach"] == 'TRUE' && is_file("$system_pdocs_directory/".substr($row_detail["39inputdate"],0,4)."/".$row_detail['id'].""."_".$row_detail["41instimestamp"].".pdf"))
		{
			$guest_file = "$system_path"."doc.php?t=p&id=".$key;
			$pdocs_file = "$system_pdocs_directory/".substr($row_detail["39inputdate"],0,4)."/".$row_detail['id'].""."_".$row_detail["41instimestamp"].".pdf";
		}
		
		if ($row_detail["41imageatt"] == 'TRUE' && is_file("$system_albums_directory/".substr($row_detail["39inputdate"],0,4)."/".$row_detail['id'].""."_".$row_detail["41instimestamp"].".jpg"))
		{
			$image_file = "$system_path"."doc.php?t=a&id=".$key;
			$albums_file = "$system_albums_directory/".substr($row_detail["39inputdate"],0,4)."/".$row_detail['id'].""."_".$row_detail["41instimestamp"].".jpg";
		}
					
		//create database entry for permission and granted access to the downloadkey
		$registerid = mysqli_query($GLOBALS["conn"],"INSERT INTO eg_downloadkey (uniqueid,eg_item_id,ip_address,timestamped,pdocs,docs,albums) VALUES('$key','".$row_detail['id']."','$ip_address','$time','$pdocs_file','','$albums_file')");
		
		if (isset($_GET['itemid']))
		{
			$posts = array(
				'id'=>$row_detail['id'],
				'title'=>$row_detail["38title"],
				'author'=>$row_detail["38author"],
				'type'=>getTypeNameFromID($row_detail['38typeid']),
				'publication'=>$row_detail["38publication"],
				'source'=>$row_detail["38source"],
				$fulltext=>htmlspecialchars(strip_tags($row_detail["41fulltexta"])),
				'reference'=>htmlspecialchars(strip_tags($row_detail["41reference"])),
				'weblink'=>urldecode($row_detail["38link"]),
				'guest_file'=>$guest_file,
				'image_file'=>$image_file,
				'swadah_link'=>$system_path."detailsg.php?det=".$row_detail['id'],
			);
		}
		else
		{
			$posts = array(
				'id'=>$row_detail['id'],
				'title'=>$row_detail["38title"],
				'author'=>$row_detail["38author"],
				'type'=>getTypeNameFromID($row_detail['38typeid']),
				'publication'=>$row_detail["38publication"],
				'source'=>$row_detail["38source"],
				'weblink'=>urldecode($row_detail["38link"]),
				'guest_file'=>$guest_file,
				'image_file'=>$image_file,
				'swadah_link'=>$system_path."detailsg.php?det=".$row_detail['id'],
			);
		}
		
		header('Content-type:application/json;charset=utf-8');
		echo stripslashes(json_encode($posts)); 
	}

	else
	{
		echo "<h2>Welcome to sWADAH Searcher API.</h2><strong>Please provide parameters to the API.</strong><br/>";
		
		echo "<br/><em><strong>Listing: types, publishers and subject headings.</strong></em>";
		echo "<br/>For list of type, <a href='searcherapi.php?listype=1'>searcherapi.php?listype=1</a>";
		echo "<br/>For list of publisher, <a href='searcherapi.php?listpub=1'>searcherapi.php?listpub=1</a>";
		echo "<br/>For list of subject headings, <a href='searcherapi.php?listsub=1'>searcherapi.php?listsub=1</a>";

		echo "<br/><br/><em><strong>Search parameter:</strong></em>";
		echo "<br/>Search paramater: <strong>scstr</strong> (search string), <strong>sctype</strong> (type as integer-ID, refer to json output of searcherapi.php?listype=1) ";
		echo "<ul><li>E.g. <a href='searcherapi.php?scstr=education&sctype=1'>searcherapi.php?scstr=education&sctype=1</a></li>";
		echo "<li>E.g. <a href='searcherapi.php?scstr=education&sctype=2'>searcherapi.php?scstr=education&sctype=2</a></li>";
		echo "<li>E.g. <a href='searcherapi.php?scstr=education&sctype=EveryThing'>searcherapi.php?scstr=education&sctype=EveryThing</a> (<em>EveryThing will search all regardless the types</em>)</li>";
		echo "<li>E.g. <a href='searcherapi.php?scstr=&sctype=EveryThing'>searcherapi.php?scstr=&sctype=EveryThing</a> (<em>You may also use empty scstr to search for ALL or any available types.</em>)</li></ul>";

		echo "<br/><em><strong>List item by publisher / subject heading:</strong></em>";
		echo "<ul><li>List item by publisher: <strong>scpub</strong> (publisher in full string, refer to list of publisher above)";
		echo " E.g. <a href='searcherapi.php?scpub=Fakulti+Pembangunan+Manusia'>searcherapi.php?scpub=Fakulti+Pembangunan+Manusia</a></li>";
		
		echo "<li>List item by subject heading: <strong>scsub</strong> (subject heading <code>acronym</code>)";
		echo " E.g. <a href='searcherapi.php?scsub=A'>searcherapi.php?scsub=A</a></li></ul>";
		
		echo "<br/><em><strong>Item detailing:</strong></em>";
		echo "<ul><li>Details parameter (full including references, abstract): <strong>itemid</strong> (item <code>id</code> returned from search parameter) E.g. <a href='searcherapi.php?itemid=10'>searcherapi.php?itemid=10</a></li>";
		echo "<li>Min. Details parameter (minimum excluding references, abstract): <strong>mitemid</strong> (item <code>id</code> returned from search parameter) E.g. <a href='searcherapi.php?mitemid=10'>searcherapi.php?mitemid=10</a></li></ul>";
	}
	
	mysqli_close($GLOBALS["conn"]); exit(); 

?>		