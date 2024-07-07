<!DOCTYPE HTML>
<?php 
		session_start();define('includeExist', TRUE);
		include 'core.php'; 	
		$thisPageTitle = "Frequently Asked Questions";
?>

<html lang='en'>

<head>
		<?php include 'sw_includes/header.php'; ?>
</head>
	
<body>		

	<?php include './sw_includes/loggedinfo.php'; ?>
	
	<table class=whiteHeaderNoCenter>			
		<tr>
			<td colspan=2 style='font-size:16px;'>
				<br/><br/><a href='#1' style='text-decoration:none'>1. The result list only shows up to 10 items but the result is more than that. How can I navigate to the next page ?</a>
				<br/><br/><a href='#2' style='text-decoration:none'>2. What is <em>exploded search term</em> ?</a>
				<br/><br/><a href='#3' style='text-decoration:none'>3. Is there any time-out when using the search function ?</a>
				<br/><br/><a href='#4' style='text-decoration:none'>4. Why some of words in my search terms did not being included in the search results ?</a>
				<br/><br/><a href='#5' style='text-decoration:none'>5. Examples of search term.</a>
				<br/><br/>
			</td>
		</tr>

		<tr class=greyHeaderCenter><td colspan=2><div style='text-align:center;font-size:14px;'><strong>Questions and Answers</strong></div></td></tr>
		<tr>
			<td colspan=2>
			
				<br/><a id='1'>1. The result list only shows up to 10 items but the result is more than that. How can I navigate to the next page ?</a>
				<br/><span style='color:green;'>At the bottom of the results list, you may see the navigation toolbar to browse to the next page of the resultset.
				Use 'Next' to navigate further or 'Previous' for prior pages.</span><br/>
				
				<br/><a id='2'>2. What is <em>exploded search term</em> ?</a>
				<br/><span style='color:green;'>Whenever you typed multiple value in the search box, the 'Exploded Search Term' function will be activated and 
				separate those multiple value into segmented search entries. Click on any of those segmented search term will redo the search function using the new search term.</span><br/>
				
				<br/><a id='3'>3. Is there any time-out when using the search function ?</a>
				<br/><span style='color:green;'>No. Normal user will be automatically be logged in as Guest. Guest mode do not have any specific access duration per session.</span><br/>
					
				<br/><a id='4'>4. Why some of words in my search terms did not being included in the search results ?</a>
				<br/><span style='color:green;'>Please take note that common words (often called 'noise terms') will be filtered and not to be included in the final search result.
				This is to ensure that you will get hold of accurate result with given search terms.</span><br/>
				
				<br/><a id='5'>5. Examples of search term.</a>
				<br/><span style='color:green;'>The following examples demonstrate some search terms that can be use by users of this system:</span>
				
				<ul>

					<li>
						<p><code class="literal">apple banana</code></p>
						<p>Find result(s) that contain at least one of the two words.</p>
					</li>
					
					<li>
						<p><code class="literal">+apple +juice</code></p>
						<p>Find result(s) that contain both words.</p>
					</li>

					<li>
						<p><code class="literal">+apple macintosh</code></p>
						<p>Find result(s) that contain the word <span class="quote">apple</span>, but rank result(s) higher if they also contain <span class="quote">macintosh</span>.</p>
					</li>
					
					<li>
						<p><code class="literal">+apple -macintosh</code></p>
						<p>Find result(s) that contain the word <span class="quote">apple</span> but not <span class="quote">macintosh</span>.</p>
					</li>
					
					<li>
						<p><code class="literal">+apple ~macintosh</code></p>
						<p>Find result(s) that contain the word <span class="quote">apple</span>, but if
						the row also contains the word <span class="quote">macintosh</span>,
						rate it lower than if row does not. This is
						<span class="quote">softer</span> than a search for <code class="literal">'+apple
						-macintosh'</code>, for which the presence of
						<span class="quote">macintosh</span> causes the row not to be returned
						at all.
					</p>
					</li>
					
					<li>
						<p><code class="literal">+apple +(&gt;turnover &lt;strudel)</code></p>
						<p>Find result(s) that contain the words <span class="quote">apple</span> and
						<span class="quote">turnover</span>, or <span class="quote">apple</span> and
						<span class="quote">strudel</span> (in any order), but rank <span class="quote">apple
						turnover</span> higher than <span class="quote">apple strudel</span>.
						</p>
					</li>
					
					<li>
						<p><code class="literal">apple*</code></p>
						<p>Find result(s) that contain words such as <span class="quote">apple</span>,
						<span class="quote">apples</span>, <span class="quote">applesauce</span>, or
						<span class="quote">applet</span>.
						</p>
					</li>
					
					<li>
						<p><code class="literal">"some words"</code></p>
						<p>         Find result(s) that contain the exact phrase <span class="quote">some
									words</span> (for example, result(s) that contain <span class="quote">some
									words of wisdom</span> but not <span class="quote">some noise
									words</span>). Note that the
									<span class="quote"><code class="literal">"</code></span> characters that enclose
									the phrase are operator characters that delimit the phrase.
									They are not the quotes that enclose the search string
									itself.
						</p>
					</li>
				
				</ul>

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