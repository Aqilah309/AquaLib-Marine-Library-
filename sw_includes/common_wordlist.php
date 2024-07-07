<?php

	$scstr_term = " ".$scstr_term." ";//put spaces in front and at the end to let the filter work properly

	$remove = array(
				"and","as","at","an","a","about","all","after","also","among",
				"by","be","before","between","because",
				"could","can","cause",
				"delete","do","day","del",
				"entry","echo",
				"from","for","false",
				"go","get",
				"have","has","had","how",
				"i","if","in","if","is","its",
				"join",
				"me","may","most","more",
				"no","nope","none",
				"or","out","over","on","of",
				"print",
				"rmdir","rm",
				"select",
				"the","to","there","their","they","then","than","true",
				"up","update","unlink","union",
				"we","with","would","which","who","whom","whose","what","want",
				"yes","yet",
				"ada","apa","akan",
				"bagaimana","bentuk",
				"di","dan","dalam","dari","daripada","dapat",
				"pada",
				"ini","ia","itu",
				"kalangan","ke","kepada","kerana",
				"lagi",
				"mana","menerusi",
				"oleh",
				"perlu",				
				"siapa","sana","sini",
				"tetapi",
				"untuk",
				"yang","ya"
				);
				
	foreach($remove as $word) {
		$scstr_term = preg_replace("/\s". $word ."\s/i", " ", $scstr_term);// '/i' for case insensitive replace
	}
		
	$scstr_term = trim($scstr_term);//trim to remove white spaces
	$scstr_term = str_replace('\'s', '', $scstr_term);//remove single quote
?>