<?php	
	//create apache writeable directories for sWADAH if not exist
	function createDirectoryForsWADAH($createdDir)
	{
		if (!is_dir($createdDir))
		{
			mkdir($createdDir,0755,true);
			file_put_contents("$createdDir"."/index.php", "<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>");
			file_put_contents("$createdDir"."/.htaccess", "<Files *.php>\ndeny from all\n</Files>\nErrorDocument 403 \"<html lang='en'><head><title>403 Forbidden</title></head><body><div style='text-align:center;margin-top:100px;'><span style='font-size:40px;color:blue;'><strong>403</strong></span><h2>Forbidden: Access prohibited</h2><em>sWADAH HTTP Response Code</em></div></body></html>\"");		
		}
	}
	createDirectoryForsWADAH($system_docs_directory);
	createDirectoryForsWADAH($system_pdocs_directory);
	createDirectoryForsWADAH($system_txts_directory);
	createDirectoryForsWADAH($system_albums_directory);
	createDirectoryForsWADAH($system_albums_thumbnail_directory);
	createDirectoryForsWADAH($system_albums_watermark_directory);
	createDirectoryForsWADAH($system_dfile_directory);
	createDirectoryForsWADAH($system_pfile_directory);
	createDirectoryForsWADAH($blocked_file_location);
	createDirectoryForsWADAH($system_statcache_directory);
?>