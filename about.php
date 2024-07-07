<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include 'core.php'; 
	$thisPageTitle = "About";
?>

<html lang='en'>

<head>
	<?php include 'sw_includes/header.php'; ?>
</head>

<body>
		
	<?php include './sw_includes/loggedinfo.php'; ?>		

	<table class=whiteHeader>
		<tr>
		<td>
			<img alt='Main Logo' width='<?php echo $main_logo_width;?>' src="./<?php echo $main_logo;?>"><br/>
			sWADAH is developed by Perpustakaan Tuanku Bainun, Universiti Pendidikan Sultan Idris and 
			it is targetted as an alternative digital repository for institutions. It features an easy to deploy system, 
			easy to manage web based file repository system, following Google Scholar inclusion guideline, 
			support OAI-PMH and also with fast, reliable and user-friendly interface.<br/><br/>
			For build numbering please refer to the footer part of this page.<br/><br/>
		</td>
		</tr>
	</table>

	<hr>
	
	<div style='text-align:center;'>
	<?php
		include './sw_includes/footer.php';
	?>
	</div>

</body>	

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>