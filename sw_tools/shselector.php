<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE); 	
	include '../sw_includes/access_isset.php';
	include '../core.php';	
	$thisPageTitle = "$subject_heading_as Selector";
?>

<html lang='en'>

<head></head>

<body>

	<script language="JavaScript">
		function pick(symbol) {
		  if (window.opener && !window.opener.closed)
			{
				<?php 
					if ($subject_heading_selectable == 'multi')
					{
							echo "
							if (window.opener.document.swadahform.subjectheading1.value == '')
								window.opener.document.swadahform.subjectheading1.value = symbol + '$subject_heading_delimiter';
							else
								window.opener.document.swadahform.subjectheading1.value 
								= window.opener.document.swadahform.subjectheading1.value + symbol + '$subject_heading_delimiter';
							";
					}
					else if ($subject_heading_selectable == 'single')
					{
							echo "
								window.opener.document.swadahform.subjectheading1.value = symbol;
							";
					}
				?>
				
			}
		 	window.close();
		}
	</script>
	
	Select a <?php echo $subject_heading_as;?> related to the item :
			
	<hr>			
				
	<u>ID</u> <u><?php echo $subject_heading_as;?></u><br/><br/>

	<?php		
		$queryC = "select 43subject, 43acronym from eg_subjectheading order by 43acronym";
		$resultC = mysqli_query($GLOBALS["conn"],$queryC);
		
		while ($myrowC = mysqli_fetch_array($resultC))
			{
				$subject = $myrowC["43subject"];
				$subjectAcr = $myrowC["43acronym"];
				echo "<span style='font-size:16px'>$subjectAcr <a href=\"javascript:pick('$subjectAcr')\">$subject</a></span><br/><br/>";
			}
	?>
	
	<br/><br/>
	<div style="text-align:center">[<a href='javascript:window.close();'>Close</a>]</div>
	<br/>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>