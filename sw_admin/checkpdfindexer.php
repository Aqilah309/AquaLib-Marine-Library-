<!DOCTYPE HTML>
<?php 
	session_start();define('includeExist', TRUE);
	include '../sw_includes/access_super.php';
	include '../core.php'; include '../sw_includes/access_allowed_adminip.php';
	include '../sw_includes/functions.php';	
	$thisPageTitle = "Check PDF Indexer";
?>

<html lang='en'>

<head><?php include '../sw_includes/header.php'; ?></head>

<body>
	
	<?php include '../sw_includes/loggedinfo.php'; ?>	
	
	<hr>

	<?php
	
		echo "<strong><a target='_blank' href='temp/test.pdf'>test.pdf</a></strong> output:<br/></h2>start of file--<br/><br/>";		

		include '../vendor/autoload.php';
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile('temp/test.pdf');		
		$text = $pdf->getText();
		echo $text;
		echo "<br/><br/>--end of file";

		$pdfTotalPages = getPDFPages2($appendroot,"temp/test.pdf");
		
		echo "<br/><br/><strong><u>Total page(s) detected</u></strong>:<br/> $pdfTotalPages page(s)";
	?>
	
	<hr>
	
	<?php include '../sw_includes/footer.php';?>
	
</body>

</html>
<?php mysqli_close($GLOBALS["conn"]); exit(); ?>