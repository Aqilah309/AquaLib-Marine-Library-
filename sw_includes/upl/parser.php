 <?php
	defined('includeExist') || die("<div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>WARNING</strong></span><h2>Forbidden: Direct access prohibited</h2><em>sWADAH HTTP Response Code</em></div>");

	if ($index_pdf)
	{							
		include '../vendor/autoload.php';
		try {
			$parser = new \Smalot\PdfParser\Parser();
			$pdf = $parser->parseFile($affected_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension);	
			$pdftext_output = $pdf->getText();	
			
			if ($usePdfInfo) {
				$pdfTotalPages = getPDFPages($appendroot,$affected_directory.'/'.$idUpload.'_'.$timestampUpload.'.'.$affected_fileextension);
			}
			else {
				$pdfTotalPages= 0;
			}
		}
		catch (Exception $e) {
			if ($e->getMessage()) {
				echo "<br/><br/>PDF Parser Status: <span style='color:red;'>".$e->getMessage()." The PDF will not be available for content search.</span>";
				$pdftext_output = '';
				$pdfTotalPages= 0;
			}				
		}
	}
	else
	{
		$pdftext_output = '';
		$pdfTotalPages= 0;
	}

	if ($successbutnotparse == 'TRUENOT') {
		echo "<br/><br/>PDF Parser Status: <span style='color:orange;'>File is not parsed because it is too large.</span>";
	}
	mysqli_query($GLOBALS["conn"],"update eg_item set 41pdfattach_fulltext='".addslashes($pdftext_output)."',51_pagecount=$pdfTotalPages where id=$idUpload");

?>