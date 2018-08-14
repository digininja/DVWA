<div class="body_padded">
	<h1>Help - Client Side JavaScript</h1>

	<div id="code" style="padding: 3px; border: 2px #C0C0C0 solid;>">
		<h3>About</h3>
		<p>The attacks in this section are designed to help you learn about how JavaScript is used in the browser and how it can be manipulated. The attacks could be carried out by just analysing network traffic, but that isn't the point and it would also probably be a lot harder.</p>

		<hr />
		
		<h3>Objective</h3>
		<p>Simply submit the phrase "success" to win the level. Obviously, it isn't quite that easy, each level implements different protection mechanisms, the JavaScript included in the pages has to be analysed and then manipulated to bypass the protections.</p>

		<hr />
		<h3>Low Level</h3>
		<p>All the JavaScript is included in the page. Read the source and work out what function is being used to generate the token required to match with the phrase and then call the function manually.</p>
		<pre>Spoiler: <span class="spoiler">Change the phrase to success and then use the function generate_token() to update the token.</span></pre>

		<p><br /></p>

		<h3>Medium Level</h3>
		<p>
			The JavaScript has been broken out into its own file and then minimized. You need to view the source for the included file and then work out what it is doing. Both Firefox and Chrome have a Pretty Print feature which attempts to reverse the compression and display code in a readable way.
		</p>
		<pre>Spoiler: <span class="spoiler">The file uses the setTimeout function to run the do_elsesomething function which generates the token.</span></pre>

		<p><br /></p>

		<h3>High Level</h3>
		<p>
			The JavaScript has been obfuscated by at least one engine. You are going to need to step through the code to work out what is useful, what is garbage and what is needed to complete the mission.
		</p>
		<pre>Spoiler: <span class="spoiler">If it helps, two packers have been used, the first is from <a href="https://www.danstools.com/javascript-obfuscate/index.php">Dan's Tools</a> and the second is the <a href="https://javascriptobfuscator.herokuapp.com/">JavaScript Obfuscator Tool</a>.</span></pre>
		<pre>Spoiler 2: <span class="spoiler">This deobfuscation tool seems to work the best on this code <a href="http://deobfuscatejavascript.com/">deobfuscate javascript</a>.</span></pre>
		<pre>Spoiler 3: <span class="spoiler">This is one way to do it... run the obfuscated JS through a deobfuscation app, intercept the response for the obfuscated JS and swap in the readable version. Work out the flow and you will see three functions that need to be called in order. Call the functions at the right time with the right parameters.</pre>

		<p><br /></p>

		<h3>Impossible Level</h3>
		<p>You can never trust the user and have to assume that any code sent to the user can be manipulated or bypassed and so there is no impossible level.</p>

	</div>

	<br />

	<p>Reference:</p>
	<ul>
		<li><?php echo dvwaExternalLinkUrlGet( 'https://www.youtube.com/watch?v=8UqHCrGdxOM' )?></li>
		<li><?php echo dvwaExternalLinkUrlGet( 'https://www.w3schools.com/js/' )?></li>
		<li><?php echo dvwaExternalLinkUrlGet( 'https://www.youtube.com/watch?v=cs7EQdWO5o0&index=17&list=WL' )?></li>
		<li><?php echo dvwaExternalLinkUrlGet( 'https://www.youtube.com/playlist?list=PLC9K7uaDMdAUNktlDTxsmj6rJBf4Q9TR5' )?></li>
	</ul>
</div>
