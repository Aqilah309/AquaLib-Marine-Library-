<?php 
//Version 20220427.1205 Alpha 3
//OAI-PMH 2.0 connector for MySQL/MariaDB database for sWADAH
//Author: Khairul Asyrani Sulaiman | asyrani@upsi.edu.my
//Specification: OAI_DC, UKETD_DC
//If you can read this, you are probably want to change something in this sea of codes.

include 'core.php';

if (!$enable_oai_pmh)
{
  echo "OAI PMH is not enabled on this server.";
}

else
{
  header('Content-Type: text/xml');
	session_start();  

  //user permittable configuration
  $oai_resultPerPage = 100;
  
  //function declaration
  function validateDate($date)
	{
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {$return_val = true;} 
    else {$return_val = false;}

    return $return_val;
  } 

  function returnCleanAmp($thisString) 
  {
    return preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $thisString);
  }
  
  function compareDate($from,$until)
  {
	  $a = new DateTime($from);
	  $b = new DateTime($until);
	  
	  if ($a > $b) {$return_val =  true;}
	  else {$return_val =  false;}

    return $return_val;
  }  
  
  function checkStringDuplicate($text) {return substr_count($text, 'metadataPrefix');}
  
  function tomorrow()
  {
    $datetime = new DateTime('tomorrow + 12hours');
    return $datetime->format('Y-m-d\TH:i:s\Z');
  }
  
  function strip_to_bare_text($stringtobare)
  {
		$str1 = str_replace('&nbsp;','',strip_tags($stringtobare));
			$str1 = str_replace('&ndash;','-',strip_tags($str1));
				$str1 = str_replace('&','&amp;',strip_tags($str1));
					$str1 = str_replace('< ','&lt;',strip_tags($str1));
						$str1 = str_replace('> ','&gt;',strip_tags($str1));
	
		return preg_replace('/[[:^print:]]/', "",$str1);
  }

  function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0){ return '';}
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
  
  //system default - WARNING ! Do not alter this
  $illegalGET_Argument = false;
  $illegalSET_isset = false;
  $isnumericToken = true;
  $dateInFormat = true;
  $metadataPrefixIsOn = false;
  $fromBiggerThanUntil = false;
  $metadataPrefixCount = 1;
  $lateThanInstalledDate = false;
  $earlyThanInstalledDate = false;
  $legitIdentifier = true;

  if (isset($_REQUEST["verb"])) {
    $get_verb = strtoupper($_REQUEST["verb"]);//get verb forwarded by either clicking one of the links or automated harvester inquiry
  }
  else {
    $get_verb = null;
  }

  //get first record
  $queryFirst = "select id from eg_item order by id limit 1";
  $resultFirst = mysqli_query($GLOBALS["conn"],$queryFirst);
  $myrowFirst=mysqli_fetch_array($resultFirst);
  $firstID_of_record = $myrowFirst["id"];

  echo "<?xml version='1.0' encoding='UTF-8' ?>\n";
  echo "<?xml-stylesheet type='text/xsl' href='sw_styles/oai2.xsl' ?>\n";
  echo "<OAI-PMH xmlns='http://www.openarchives.org/OAI/2.0/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd'>\n";
  echo "  <responseDate>".gmdate("Y-m-d\TH:i:s\Z")."</responseDate>\n";

  if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {$fullURL = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];} else {$fullURL = null;}
  if (isset(explode("Identify&",$fullURL)[1]) && strlen(explode("Identify&",$fullURL)[1]) >= 1) {$illegalGET_Argument = true;}	

  if (isset($_REQUEST["resumptionToken"]))
  {
    if (is_numeric($_REQUEST["resumptionToken"])) {
      $isnumericToken = true;
    }
    else 
    {
        //to handle resumptionToken criteria with %26 (&) and %3D (=)
        if (strpos($_REQUEST["resumptionToken"], "&metadataPrefix=oai_dc") !== false) {$_REQUEST["metadataPrefix"] = "oai_dc";}
        else if (strpos($_REQUEST["resumptionToken"], "&metadataPrefix=uketd_dc") !== false) {$_REQUEST["metadataPrefix"] = "uketd_dc";}
        
        $hold_resumptionTokenValue = $_REQUEST["resumptionToken"];

        $_REQUEST["resumptionToken"] = strtok($hold_resumptionTokenValue, "&metadataPrefix");
        
        if (strpos($hold_resumptionTokenValue, "&set=") !== false) {
          $_REQUEST["set"] = substr($hold_resumptionTokenValue, strpos($hold_resumptionTokenValue, "&set=") + 5);  
        }

        if (is_numeric(strtok($hold_resumptionTokenValue, "&")))
          {$isnumericToken = true;}
        else
         { $isnumericToken = false;}
    }
  }

  if (isset($_REQUEST["set"]) && isset($_REQUEST["metadataPrefix"]) && isset($_REQUEST["from"]) && isset($_REQUEST["until"])) {$illegalSET_isset = true;}
	if (isset($_REQUEST['from'])) {$dateInFormat = validateDate($_REQUEST['from']);}
	if (isset($_REQUEST['until'])) {$dateInFormat = validateDate($_REQUEST['until']);}
	if (isset($_REQUEST['metadataPrefix'])) {if ($_REQUEST['metadataPrefix'] == 'oai_dc' || $_REQUEST['metadataPrefix'] == 'uketd_dc') {$metadataPrefixIsOn = true;} else {$metadataPrefixIsOn = false;}}
	if ((isset($_REQUEST['from']) && validateDate($_REQUEST['from'])) && (isset($_REQUEST['until']) && validateDate($_REQUEST['until']))) {$fromBiggerThanUntil = compareDate($_REQUEST['from'],$_REQUEST['until']);}
	if (checkStringDuplicate("$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") >= 2) {$metadataPrefixCount = checkStringDuplicate("$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");}
	if (isset($_REQUEST['until']) && (validateDate($_REQUEST['from']) && validateDate($_REQUEST['until'])) && compareDate($installed_date,$_REQUEST['until'])) {$lateThanInstalledDate = true;}
	if (isset($_REQUEST['until']) && validateDate($_REQUEST['until']) && compareDate($installed_date,$_REQUEST['until'])) {$earlyThanInstalledDate = true;}
	if (isset($_REQUEST['identifier']) && strpos($_REQUEST['identifier'],"oai:") !== false) {$legitIdentifier = true;} else {$legitIdentifier = false;}
  if ((isset($_REQUEST['verb']) && $_REQUEST['verb'] == 'ListIdentifiers') && isset($_REQUEST['resumptionToken']) && isset($_REQUEST['metadataPrefix'])) {$overblownIdentifier = true;} else {$overblownIdentifier = false;}
  
  //handling from until for LISTRECORDS and LISTIDENTIFIERS
  if (isset($_REQUEST['from']) && isset($_REQUEST['until'])) {$_SESSION['appendFromUntil'] = "and STR_TO_DATE(eg_item.39inputdate, '%Y-%m-%d') BETWEEN '".$_REQUEST['from']."' AND '".$_REQUEST['until']."'";}
	else if (isset($_REQUEST['from'])) {$_SESSION['appendFromUntil'] = "and STR_TO_DATE(eg_item.39inputdate, '%Y-%m-%d') >= '".$_REQUEST['from']."'";}
	else if (isset($_REQUEST['until'])) {$_SESSION['appendFromUntil'] = "and STR_TO_DATE(eg_item.39inputdate, '%Y-%m-%d') <= '".$_REQUEST['until']."'";}
  else if (!isset($_REQUEST['resumptionToken'])) {$_SESSION['appendFromUntil']='';}

  //handling set
  if (isset($_REQUEST['set']) && is_numeric($_REQUEST['set'])) 
  {
    $_SESSION['appendSet'] = "and eg_item.38typeid='".$_REQUEST['set']."'";
    $setSpec = $_REQUEST['set'];
  }
  else 
  {
    $_SESSION['appendSet']='';
    $setSpec = null;
  }

  //handling metadataPrefix
  if (isset($_REQUEST['metadataPrefix']) && $_REQUEST['metadataPrefix'] == 'uketd_dc') 
  {
    $_SESSION['metaDataSelected'] = 'uketd_dc';
  }
  else if (isset($_REQUEST['metadataPrefix']) && $_REQUEST['metadataPrefix'] == 'oai_dc') 
  {
    $_SESSION['metaDataSelected'] = 'oai_dc';
  }

?>
<?php 




if ($get_verb == 'IDENTIFY' && !$illegalGET_Argument) 
{
?>
  <request verb='Identify'><?php echo $system_path;?>https://localhost/sWADAH/oai2.php</request>
  <Identify>
    <repositoryName><?php echo $system_title;?></repositoryName>  
    <baseURL><?php echo $system_path."https://localhost/sWADAH/oai2.php";?></baseURL>
    <protocolVersion>2.0</protocolVersion>
    <adminEmail><?php echo $system_admin_email;?></adminEmail>
    <earliestDatestamp><?php echo $installed_date;?></earliestDatestamp>
    <deletedRecord>no</deletedRecord> 
    <granularity>YYYY-MM-DD</granularity>
    <description>
      <oai-identifier xmlns="http://www.openarchives.org/OAI/2.0/oai-identifier" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd">
        <scheme>oai</scheme>
        <repositoryIdentifier><?php echo $system_identifier;?></repositoryIdentifier>
        <delimiter>:</delimiter>
        <sampleIdentifier>oai:<?php echo $system_identifier;?>:<?php echo $firstID_of_record;?></sampleIdentifier>
      </oai-identifier>
    </description>
  </Identify>
<?php 
} 




else if (($get_verb == 'LISTMETADATAFORMATS' && !isset($_REQUEST['identifier']) && !$overblownIdentifier) || ($get_verb == 'LISTMETADATAFORMATS' && $legitIdentifier && !$overblownIdentifier)) 
{
?>
  <request verb="ListMetadataFormats" identifier="oai:<?php echo $system_identifier;?>:<?php echo $firstID_of_record;?>"><?php echo $system_path."https://localhost/sWADAH/oai2.php";?></request>
  <ListMetadataFormats>
    <metadataFormat>
      <metadataPrefix>oai_dc</metadataPrefix>
      <schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>
      <metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>
    </metadataFormat>
    <metadataFormat>
      <metadataPrefix>uketd_dc</metadataPrefix>
      <schema>http://naca.central.cranfield.ac.uk/ethos-oai/2.0/uketd_dc.xsd</schema>
      <metadataNamespace>http://naca.central.cranfield.ac.uk/ethos-oai/2.0/</metadataNamespace>
    </metadataFormat>
  </ListMetadataFormats>
<?php 
} 





else if ($get_verb == 'LISTRECORDS' && $isnumericToken && $dateInFormat && !$lateThanInstalledDate && !$earlyThanInstalledDate && (isset($_REQUEST["resumptionToken"]) || $metadataPrefixIsOn)) 
{ 
?>
<?php 
  if (isset($_REQUEST['from'])) {$fromAppend = "from=\"".$_REQUEST['from']."\"";} else {$fromAppend = null;}
  if (isset($_REQUEST['until'])) {$untilAppend = "until=\"".$_REQUEST['until']."\"";} else {$untilAppend = null;}
?>
  <request verb="ListRecords"<?php if ($fromAppend <> '') {echo " $fromAppend";} if ($untilAppend <> '') {echo " $untilAppend";}?> metadataPrefix="<?php echo $_SESSION['metaDataSelected'];?>"><?php echo $system_path;?>https://localhost/sWADAH/oai2.php</request>
<?php
  if (isset($_REQUEST["resumptionToken"])) {$pageNum = $_REQUEST["resumptionToken"];}
  else {$pageNum= 1;}
    
    $offset = ($pageNum - 1) * $oai_resultPerPage;
    $pageNum = $pageNum + 1;
    
    $query1 = "select 
    eg_item.id as id, 
    eg_item.38langcode as langcode, 
    eg_item.38title as title, 
    eg_item.38typeid as typeid, 
    eg_item.38publication as publication, 
    eg_item.38location as location, 
    eg_item.38author as author, 
    eg_item.38source as source, 
    eg_item.39inputdate as inputdate, 
    eg_item.41instimestamp as instimestamp, 
    eg_item.41subjectheading as subjectheading, 
    eg_item.41fulltexta as abstract_text, 
    eg_item.41reference as reference_text, 
    eg_item2.38publication_c as yearpublish, 
    eg_item2.38publication_b as frompublisher, 
    eg_item2.38dissertation_note_b as qualificationlevel, 
    eg_item_type.38type as typename 
    from eg_item left join eg_item2 on eg_item.id = eg_item2.eg_item_id left join eg_item_type on eg_item.38typeid = eg_item_type.38typeid where 38status='AVAILABLE' and 50item_status='1' ".$_SESSION['appendFromUntil']." ".$_SESSION['appendSet']." order by eg_item.id limit $offset,$oai_resultPerPage";
    $result1 = mysqli_query($GLOBALS["conn"],$query1);
    $num_results_affected1 = mysqli_num_rows($result1);
    
    $query1complete = "select count(id) as completelist from eg_item where 38status='AVAILABLE' and 50item_status='1' ".$_SESSION['appendFromUntil']." ".$_SESSION['appendSet'];
		$result1complete = mysqli_query($GLOBALS["conn"],$query1complete);
		$myrow1complete = mysqli_fetch_array($result1complete);
		$oai_completeListSize = $myrow1complete["completelist"];    
    
    if ($num_results_affected1 < 1)
      {echo "<error code=\"noRecordsMatch\">No items match. None. None at all. Not even deleted ones.</error>";}
    else
    {    
?>
  <ListRecords>
<?php
    while ($myrow=mysqli_fetch_array($result1))
    {
      $id5=$myrow["id"];

      if ($searcher_marker_to_hide == null)
        {$title5=strip_to_bare_text($myrow["title"]);}
	    else
        {$title5=strip_to_bare_text(str_replace($searcher_marker_to_hide, "", $myrow["title"]));}

      $typeid5=strip_to_bare_text($myrow["typeid"]);

      if ($myrow["typename"] != '') {$typename5=strip_to_bare_text($myrow["typename"]);}
        else {$typename5="N/A";}

      if ($myrow["location"] != '') {$location5=strip_to_bare_text($myrow["location"]);}
        else {$location5="N/A";}

      $author5=strip_to_bare_text($myrow["author"]);
        if ($author5 == '') 
        {
          if ($myrow["source"] != '') {$author5 = strip_to_bare_text($myrow["source"]);}
          else {$author5="N/A";}
        }

      if ($myrow["source"] != '') {$source5 = strip_to_bare_text($myrow["source"]);}
        else {$source5="N/A";}

      if ($myrow["inputdate"] != '') {$inputdate5 = date('Y-m-d', strtotime(str_replace('/', '-', $myrow["inputdate"])));} 
        else {$inputdate5 = "N/A";}

      $instimestamp5 =$myrow["instimestamp"];
         
      if ($myrow["yearpublish"] != '') {$yearpublish5 = $myrow["yearpublish"];}
        else {$yearpublish5="N/A";}

      if ($myrow["publication"] != '') {$publication5 = strip_to_bare_text($myrow["publication"]);}
        else {$publication5="N/A";}

      $langcode5 = $myrow["langcode"];

      if ($myrow["frompublisher"] != '') {$frompublisher5 = strip_to_bare_text($myrow["frompublisher"]);}
        else {$frompublisher5="N/A";}

      if ($myrow["qualificationlevel"] != '') {$qualificationlevel5 = $myrow["qualificationlevel"];}
        else {$qualificationlevel5="N/A";}

      if ($myrow["abstract_text"] != '') {$abstract5 = strip_to_bare_text($myrow["abstract_text"]);}
        else {$abstract5="N/A";}

      if ($myrow["reference_text"] != '') {$reference5 = strip_to_bare_text($myrow["reference_text"]);}
        else {$reference5="N/A";}
      
      $subjectheading5 =$myrow["subjectheading"];  
      $returnSH = "N/A";
      if ($subjectheading5 != '')
      {
        $returnSH = "";
        $subjectheadings = explode("|", $subjectheading5);
        $totalsubjectheadings = count($subjectheadings) - 1;
        $i = 1;
        foreach($subjectheadings as $subjectheading) 
        {		
          $subjectheading = trim($subjectheading);
          $queryTx = "select 43subject from eg_subjectheading where 43acronym = '$subjectheading'";
          $resultTx = mysqli_query($GLOBALS["conn"],$queryTx);
          $myrowTy=mysqli_fetch_array($resultTx);										
          if ($subject_heading_selectable == "multi")
            {
              $returnSH .= preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $myrowTy["43subject"]);
              if ($i < $totalsubjectheadings) {$returnSH .= ", ";}
              $i=$i+1;
            }
          else
          {
            $returnSH = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $myrowTy["43subject"])."";
            break;//just show the first subject heading found. disable this to show all in one liner
          }
        }
      }  
?>
    <record>
    <header>
      <identifier>oai:<?php echo $system_identifier;?>:<?php echo $id5;?></identifier>
      <datestamp><?php echo $inputdate5;?></datestamp>
      <setSpec><?php echo $typeid5;?></setSpec>
    </header>
<?php
    //uketd_dc view
    if ($_SESSION['metaDataSelected'] == "uketd_dc")
    {
?>
    <metadata>
      <uketd_dc:uketddc xmlns:uketd_dc="http://naca.central.cranfield.ac.uk/ethos-oai/2.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:uketdterms="http://naca.central.cranfield.ac.uk/ethos-oai/terms/" xsi:schemaLocation="http://naca.central.cranfield.ac.uk/ethos-oai/2.0/ http://naca.central.cranfield.ac.uk/ethos-oai/2.0/uketd_dc.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:title><?php echo htmlspecialchars($title5);?></dc:title>
        <dc:date><?php echo $yearpublish5;?></dc:date>
        <dc:creator><?php echo htmlspecialchars($author5);?></dc:creator>
        <dc:subject><?php echo $returnSH;?></dc:subject>
		    <dcterms:abstract><?php echo $abstract5;?></dcterms:abstract>
		    <dcterms:issued><?php echo $yearpublish5;?></dcterms:issued>
        <dc:type><?php echo $typename5;?></dc:type>
		    <dcterms:isReferencedBy><?php echo $system_path."detailsg.php?det=$id5";?></dcterms:isReferencedBy>
        <dc:identifier xsi:type="dcterms:URI"><?php echo $system_path."detailsg.php?det=$id5";?></dc:identifier>
		    <dc:format><?php echo $oai_main_format;?></dc:format>
        <dc:language><?php echo $langcode5;?></dc:language>        
        <dcterms:accessRights><?php echo $oai_rights;?></dcterms:accessRights>
        <uketdterms:qualificationname></uketdterms:qualificationname>
        <uketdterms:qualificationlevel><?php echo $qualificationlevel5;?></uketdterms:qualificationlevel>
        <uketdterms:institution><?php echo $source5;?></uketdterms:institution>
		    <uketdterms:department><?php echo $frompublisher5;?></uketdterms:department>
		    <dcterms:references><?php echo $reference5;?></dcterms:references>
      </uketd_dc:uketddc>
    </metadata>
<?php
    }
    //dc view
    else
    {
?>
    <metadata>
      <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="http://purl.org/dc/elements/1.1/" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:relation><?php echo $system_path."detailsg.php?det=$id5";?></dc:relation>
        <dc:title><?php echo htmlspecialchars($title5);?></dc:title>
        <dc:creator><?php echo htmlspecialchars($author5);?></dc:creator>
        <dc:subject><?php echo $returnSH;?></dc:subject>
        <dc:publisher><?php echo htmlspecialchars($publication5);?></dc:publisher>
        <dc:date><?php echo $yearpublish5;?></dc:date>
        <dc:type><?php echo $typename5;?></dc:type>
        <dc:format><?php echo $oai_main_format;?></dc:format>
        <dc:language><?php echo $langcode5;?></dc:language>
        <dc:identifier><?php echo $system_path."detailsg.php?det=$id5";?></dc:identifier>
        <dc:rights><?php echo $oai_rights;?></dc:rights>
        <dc:description><?php echo htmlspecialchars($title5)." by ".htmlspecialchars($author5);?></dc:description>
      </oai_dc:dc>
    </metadata>
<?php
    }
?>
    </record>
<?php
    }//while
    if (($num_results_affected1 == $oai_resultPerPage) && ($oai_completeListSize != $oai_resultPerPage))
    {
      echo "<resumptionToken completeListSize=\"".$oai_completeListSize."\" expirationDate=\"".tomorrow()."\">$pageNum";        
        if ($_SESSION['metaDataSelected'] != '') {echo "&amp;metadataPrefix=".$_SESSION['metaDataSelected'];}
        if ($setSpec != '') {echo "&amp;set=$setSpec";}
      echo "</resumptionToken>";
    }
?>
  </ListRecords>
<?php 
    }
} 






else if ($get_verb == 'LISTIDENTIFIERS' && !$illegalSET_isset && $isnumericToken && $dateInFormat && !$fromBiggerThanUntil && (isset($_REQUEST["resumptionToken"]) || ($metadataPrefixIsOn && $metadataPrefixCount == 1)) && !$overblownIdentifier) 
{ 
 if (isset($_REQUEST['from'])) {$fromAppend = "from=\"".$_REQUEST['from']."\"";} else {$fromAppend = '';}
 if (isset($_REQUEST['until'])) {$untilAppend = "until=\"".$_REQUEST['until']."\"";} else {$untilAppend = '';}
?>
  <request verb="ListIdentifiers"<?php if ($fromAppend <> '') {echo " $fromAppend";} if ($untilAppend <> '') {echo " $untilAppend";}?> metadataPrefix="<?php echo $_SESSION['metaDataSelected'];?>"><?php echo $system_path;?>https://localhost/sWADAH/oai2.php</request>
<?php
    if (isset($_REQUEST["resumptionToken"])) {$pageNum = $_REQUEST["resumptionToken"];}
    else {$pageNum= 1;}
    
    $offset = ($pageNum - 1) * $oai_resultPerPage;
    $pageNum = $pageNum + 1;

    $query1 = "select * from eg_item where 38status='AVAILABLE' and 50item_status='1' ".$_SESSION['appendFromUntil']." ".$_SESSION['appendSet']." order by id limit $offset,$oai_resultPerPage";
    $result1 = mysqli_query($GLOBALS["conn"],$query1);
    $num_results_affected1 = mysqli_num_rows($result1);	 

    $query1complete = "select count(id) as completelist from eg_item where 38status='AVAILABLE' and 50item_status='1' ".$_SESSION['appendFromUntil']." ".$_SESSION['appendSet'];
		$result1complete = mysqli_query($GLOBALS["conn"],$query1complete);
		$myrow1complete = mysqli_fetch_array($result1complete);
		$oai_completeListSize = $myrow1complete["completelist"];    

    if ($num_results_affected1 < 1)
      {echo "  <error code=\"noRecordsMatch\">No items match. None. None at all. Not even deleted ones.</error>";}
    else
    {    
?>
  <ListIdentifiers>
<?php  
    while ($myrow=mysqli_fetch_array($result1))
    {
      $id5=$myrow["id"];     
      $inputdate5 = date('Y-m-d', strtotime(str_replace('/', '-', $myrow["39inputdate"])));
?>
    <header>
      <identifier><?php echo $overblownIdentifier;?> oai:<?php echo $system_identifier;?>:<?php echo $id5;?></identifier>
      <datestamp><?php echo $inputdate5;?></datestamp>
    </header>
<?php
    }
    if (($num_results_affected1 == $oai_resultPerPage) && ($oai_completeListSize != $oai_resultPerPage))
      {
        echo "<resumptionToken completeListSize=\"".$oai_completeListSize."\" expirationDate=\"".tomorrow()."\">$pageNum";          
          if ($_SESSION['metaDataSelected'] != '') {echo "&amp;metadataPrefix=".$_SESSION['metaDataSelected'];}
          if ($setSpec != '') {echo "&amp;set=$setSpec";}
        echo "</resumptionToken>";
      }
  ?>
  </ListIdentifiers>
<?php 
    }
} 






else if ($get_verb == 'LISTSETS') 
{ 
?>
  <request verb="ListSets"><?php echo $system_path;?>https://localhost/sWADAH/oai2.php</request>
  <ListSets>
<?php
  $query1 = "select * from eg_item_type";
  $result1 = mysqli_query($GLOBALS["conn"],$query1);
  while ($myrow=mysqli_fetch_array($result1))
  {
?>
    <set>
      <setSpec><?php echo $myrow['38typeid'];?></setSpec>
      <setName><?php echo $myrow['38type'];?></setName>
    </set>
<?php
  }
?>
  </ListSets>
<?php 
} 






else if ($get_verb == 'GETRECORD' && $metadataPrefixIsOn) 
{ 
  unset($_SESSION['appendSet']);
  $get_identifier = substr($_REQUEST['identifier'], strrpos($_REQUEST['identifier'], ':') + 1); 
	if ($get_identifier == '' || !$legitIdentifier || $overblownIdentifier) {$get_identifier = 0;}
?>
  <request verb="GetRecord" identifier='oai:<?php echo "$system_identifier:$get_identifier";?>' metadataPrefix="<?php echo $_SESSION['metaDataSelected'];?>"><?php echo $system_path;?>oai2.php</request>
<?php
    $query1 = "select 
    eg_item.id as id, 
    eg_item.38typeid as typeid, 
    eg_item.38langcode as langcode, 
    eg_item.38title as title, 
    eg_item.38publication as publication, 
    eg_item.38location as location, 
    eg_item.38author as author, 
    eg_item.38source as source, 
    eg_item.39inputdate as inputdate, 
    eg_item.41instimestamp as instimestamp, 
    eg_item.41subjectheading as subjectheading, 
    eg_item2.38publication_c as yearpublish, 
    eg_item.41fulltexta as abstract_text, 
    eg_item.41reference as reference_text, 
    eg_item2.38publication_c as yearpublish, 
    eg_item2.38publication_b as frompublisher, 
    eg_item2.38dissertation_note_b as qualificationlevel, 
    eg_item_type.38type as typename 
    from eg_item left join eg_item2 on eg_item.id = eg_item2.eg_item_id left join eg_item_type on eg_item.38typeid = eg_item_type.38typeid where eg_item.id=$get_identifier limit 1";
    $result1 = mysqli_query($GLOBALS["conn"],$query1);
    $num_results_affected1 = mysqli_num_rows($result1);

    if ($num_results_affected1 < 1)
	  {
		  echo " <error code=\"idDoesNotExist\">'oai:$system_identifier:$get_identifier' is not a valid item in this repository</error>";
		  echo " <error code=\"badArgument\">Illegal Parameter Detected</error>";
	  }
    else
    {    
?>
  <GetRecord>
<?php
    $myrow=mysqli_fetch_array($result1);    
    
    $id5=$myrow["id"];

    if ($searcher_marker_to_hide == null)
      {$title5=strip_to_bare_text($myrow["title"]);}
    else
      {$title5=strip_to_bare_text(str_replace($searcher_marker_to_hide, "", $myrow["title"]));}

    $typeid5=strip_to_bare_text($myrow["typeid"]);

    if ($myrow["typename"] != '') {$typename5=strip_to_bare_text($myrow["typename"]);}
      else {$typename5="N/A";}

    if ($myrow["location"] != '') {$location5=strip_to_bare_text($myrow["location"]);}
      else {$location5="N/A";}

    $author5=strip_to_bare_text($myrow["author"]);
      if ($author5 == '') 
      {
        if ($myrow["source"] != '') {$author5 = strip_to_bare_text($myrow["source"]);}
        else {$author5="N/A";}
      }

    if ($myrow["source"] != '') {$source5 = strip_to_bare_text($myrow["source"]);}
      else {$source5="N/A";}

    if ($myrow["inputdate"] != '') {$inputdate5 = date('Y-m-d', strtotime(str_replace('/', '-', $myrow["inputdate"])));}
      else {$inputdate5 = "N/A";}

    $instimestamp5 =$myrow["instimestamp"];
        
    if ($myrow["yearpublish"] != '') {$yearpublish5 = $myrow["yearpublish"];}
      else {$yearpublish5="N/A";}

    if ($myrow["publication"] != '') {$publication5 = strip_to_bare_text($myrow["publication"]);}
      else {$publication5="N/A";}

    $langcode5 = $myrow["langcode"];

    if ($myrow["frompublisher"] != '') {$frompublisher5 = strip_to_bare_text($myrow["frompublisher"]);}
      else {$frompublisher5="N/A";}

    if ($myrow["qualificationlevel"] != '') {$qualificationlevel5 = $myrow["qualificationlevel"];}
      else {$qualificationlevel5="N/A";}

    if ($myrow["abstract_text"] != '') {$abstract5 = strip_to_bare_text($myrow["abstract_text"]);}
      else {$abstract5="N/A";}

    if ($myrow["reference_text"] != '') {$reference5 = strip_to_bare_text($myrow["reference_text"]);}
      else {$reference5="N/A";}
    
    $subjectheading5 =$myrow["subjectheading"];  
    $returnSH = "N/A";
    if ($subjectheading5 != '')
    {
      $returnSH = "";
      $subjectheadings = explode("|", $subjectheading5);
      $totalsubjectheadings = count($subjectheadings) - 1;
      $i = 1;
      foreach($subjectheadings as $subjectheading) 
      {		
        $subjectheading = trim($subjectheading);
        $queryTx = "select 43subject from eg_subjectheading where 43acronym = '$subjectheading'";
        $resultTx = mysqli_query($GLOBALS["conn"],$queryTx);
        $myrowTy=mysqli_fetch_array($resultTx);										
        if ($subject_heading_selectable == "multi")
          {
            $returnSH .= preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $myrowTy["43subject"]);
            if ($i < $totalsubjectheadings) {$returnSH .= ", ";}
            $i=$i+1;
          }
        else
        {
          $returnSH = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $myrowTy["43subject"])."";
          break;//just show the first subject heading found. disable this to show all in one liner
        }
      }
    }  
?>
    <record>
    <header>
      <identifier>oai:<?php echo $system_identifier;?>:<?php echo $id5;?></identifier>
      <datestamp><?php echo $inputdate5;?></datestamp>
      <setSpec><?php echo $typeid5;?></setSpec>
    </header>
<?php
    if ($_SESSION['metaDataSelected'] == "uketd_dc")
    {
?>
    <metadata>
      <uketd_dc:uketddc xmlns:uketd_dc="http://naca.central.cranfield.ac.uk/ethos-oai/2.0/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:uketdterms="http://naca.central.cranfield.ac.uk/ethos-oai/terms/" xsi:schemaLocation="http://naca.central.cranfield.ac.uk/ethos-oai/2.0/ http://naca.central.cranfield.ac.uk/ethos-oai/2.0/uketd_dc.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:title><?php echo htmlspecialchars($title5);?></dc:title>
        <dc:date><?php echo $yearpublish5;?></dc:date>
        <dc:creator><?php echo htmlspecialchars($author5);?></dc:creator>
        <dc:subject><?php echo $returnSH;?></dc:subject>
        <dcterms:abstract><?php echo $abstract5;?></dcterms:abstract>
        <dcterms:issued><?php echo $yearpublish5;?></dcterms:issued>
        <dc:type><?php echo $typename5;?></dc:type>
        <dcterms:isReferencedBy><?php echo $system_path."detailsg.php?det=$id5";?></dcterms:isReferencedBy>
        <dc:identifier xsi:type="dcterms:URI"><?php echo $system_path."detailsg.php?det=$id5";?></dc:identifier>
        <dc:format><?php echo $oai_main_format;?></dc:format>
        <dc:language><?php echo $langcode5;?></dc:language>        
        <dcterms:accessRights><?php echo $oai_rights;?></dcterms:accessRights>
        <uketdterms:qualificationname></uketdterms:qualificationname>
        <uketdterms:qualificationlevel><?php echo $qualificationlevel5;?></uketdterms:qualificationlevel>
        <uketdterms:institution><?php echo $source5;?></uketdterms:institution>
        <uketdterms:department><?php echo $frompublisher5;?></uketdterms:department>
        <dcterms:references><?php echo $reference5;?></dcterms:references>
      </uketd_dc:uketddc>
    </metadata>
<?php
    }
    else
    {
?>
    <metadata>
      <oai_dc:dc xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" xmlns:dc="http://purl.org/dc/elements/1.1/" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
        <dc:relation><?php echo $system_path."detailsg.php?det=$id5";?></dc:relation>
        <dc:title><?php echo htmlspecialchars($title5);?></dc:title>
        <dc:creator><?php echo htmlspecialchars($author5);?></dc:creator>
        <dc:subject><?php echo $returnSH;?></dc:subject>
        <dc:publisher><?php echo htmlspecialchars($publication5);?></dc:publisher>
        <dc:date><?php echo $yearpublish5;?></dc:date>
        <dc:type><?php echo $typename5;?></dc:type>
        <dc:format><?php echo $oai_main_format;?></dc:format>
        <dc:language><?php echo $langcode5;?></dc:language>
        <dc:identifier><?php echo $system_path."detailsg.php?det=$id5";?></dc:identifier>
        <dc:rights><?php echo $oai_rights;?></dc:rights>
        <dc:description><?php echo htmlspecialchars($title5)." by ".htmlspecialchars($author5);?></dc:description>
      </oai_dc:dc>
    </metadata>
<?php
    }
?>
    </record>
  </GetRecord>
<?php 
    }
} 





else 
{ 
  if ($get_verb!='IDENTIFY' && $get_verb!='LISTIDENTIFIERS' && $get_verb!='LISTMETADATAFORMATS' && $get_verb!='LISTRECORDS' && $get_verb!='LISTSETS' && $get_verb!='GETRECORD')
	{
?>
  <request><?php echo $system_path;?>oai2.php</request>
  <error code="badVerb">Requires verb argument</error>
<?php 	
  }
  else
  {
    if ($illegalGET_Argument || !$metadataPrefixIsOn || !$dateInFormat || $fromBiggerThanUntil || $metadataPrefixCount >= 2 || $lateThanInstalledDate)
	  {
?>
  <request><?php echo $system_path;?>oai2.php</request>
  <error code="badArgument">Illegal Parameter Detected</error>
<?php
	  }
    if (!$isnumericToken || $overblownIdentifier)
	  {
?>
  <request><?php echo $system_path;?>oai2.php</request>
  <error code="badResumptionToken">Bad Resumption Token Detected</error>
<?php
	  }
	  if ($fromBiggerThanUntil || $earlyThanInstalledDate)
	  {
?>
  <request><?php echo $system_path;?>oai2.php</request>
  <error code="noRecordsMatch">No records found.</error>
<?php
    }
  }
}
?>
</OAI-PMH><?php }//else OAI-PMH enable = true?>