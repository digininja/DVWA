<script>
function show_answer(which) {
	var x = document.getElementById(which + "_answer");
	if (x.style.display === "" || x.style.display === "none") {
		x.style.display = "block";
	} else {
		x.style.display = "none";
	}
}
</script>
<style>
	pre {
		overflow-x: auto;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
	#low_answer,#medium_answer,#high_answer {
		display: none;
	}
</style>

<div class="body_padded">
	<h1>Help - API Security</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
		Most modern web apps use some kind of API, either as Single Page Apps (SPAs) or to retrieve data to populate traditional apps. As these APIs are behind the scenes, developers sometimes feel they can cut corners in areas such as authentication, authorisation or data validation. As testers, we can get behind the curtains and directly access these seemingly hidden calls to take advantage of these weaknesses.
		</p>
		<p>
		This module will look at three weaknesses, versioning, mass assignment, and ..... 
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Each level has its own objective but the general idea is to exploit weak API implementations.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>The call being made by the JavaScript is for version 2 of the endpoint, could there be other, earlier, versions available?</p>
		<p>
		<button onclick="show_answer('low')">Show Answer</button>
		</p>
		<div id="low_answer">
		<p>Either by looking at the JavaScript or watching network traffic, you should notice that there is a call being made to <code>/vulnerabilities/api/v2/user/</code> to retrieve the data used to generate the user table.</p>
		<p>
			As the call is being made against version two (<code>v2</code>) of the endpoint, the obvious thing to try is to see if version one is available, and what it offers. The easiest way to do this is to access it directly in the browser by visiting <a href="//<?=$_SERVER['SERVER_NAME']?>/vulnerabilities/api/v1/user/">/vulnerabilities/api/v1/user/</a>, but sometimes API calls require extra headers or authentication tokens which it is easier to let the site add rather than trying to do it manually. Two ways to do this are to modify the URL used in the JavaScript as the page loads by setting a breakpoint on it and changing it before the request is made, or to intercept the call in a proxy, such as BurpSuite.
		</p>
		<p>
			Whatever approach you try, by accessing version one of the endpoint, you should be able to see the password hashes as part of the data.
		</p>
		</div>

		<h3>Medium Level</h3>
		<p>The tokens are encrypted using an Electronic Code Book based algorithm (aes-128-ecb). In this mode, the clear text is broken down into fixed sized blocks and each block is encrypted independently of the rest. This results in a cipher text that is made up from a number of individual blocks with no way to tie them together. Worse than this, any two blocks, from any two clear text inputs, are interchangeable as long as they have been encrypted with the same key. In our example, this means you can take blocks from the three different tokens to make your own token. </p>
		<p>
		<button onclick="show_answer('medium')">Show Answer</button>
		</p>
		<div id="medium_answer">
		<p>
			How do you know the block size? This is given in the algorithm name. aes-128-ebc is a 128 bit block cipher. 128 bits is 16 bytes, but to make things human readable, the bytes are represented as hex characters meaning each byte is two characters. This gives you a block size of 32 characters. Sooty's token is 192 characters long, 192 / 32 = 6 and so Sooty's token has six code blocks.
		</p>

		<p>
Let's start by breaking the tokens down into blocks.</p>
<p><strong>Sooty:</strong></p>
<pre><code>e287af752ed3f9601befd45726785bd9
b85bb230876912bf3c66e50758b222d0
837d1e6b16bfae07b776feb7afe57630
5aec34b41499579d3fb6acc8dc92fd5f
cea8743c3b2904de83944d6b19733cdb
48dd16048ed89967c250ab7f00629dba</code></pre>
</p>

<p><strong>Sweep:</strong></p>
<pre><code>3061837c4f9debaf19d4539bfa0074c1
b85bb230876912bf3c66e50758b222d0
83f2d277d9e5fb9a951e74bee57c77a3
caeb574f10f349ed839fbfd223903368
873580b2e3e494ace1e9e8035f0e7e07</code></pre>

<p><strong>Soo:</strong></p>
<pre><code>5fec0b1c993f46c8bad8a5c8d9bb9698
174d4b2659239bbc50646e14a70becef
83f2d277d9e5fb9a951e74bee57c77a3
c9acb1f268c06c5e760a9d728e081fab
65e83b9f97e65cb7c7c4b8427bd44abc
16daa00fd8cd0105c97449185be77ef5</code></pre>

		<p>
		Each token has broken down nicely into blocks so we are on the right track.
		</p>
		<p>
		If you look carefully at the blocks you will see that there are some that repeat over the different tokens, this means that the same clear text has been encrypted to create the block. If we look at the description we can try to map these to the JSON object.
		</p>
		<p>
		Taking Sooty as an example:
		</p>
<p><strong>Sooty:</strong></p>
<pre><code>e287af752ed3f9601befd45726785bd9 <- Username
b85bb230876912bf3c66e50758b222d0 <- Expiry
837d1e6b16bfae07b776feb7afe57630 <- Level
5aec34b41499579d3fb6acc8dc92fd5f <- Bio
cea8743c3b2904de83944d6b19733cdb
48dd16048ed89967c250ab7f00629dba</code></pre>
</p>
		<p>
		Assuming we are right with our mappings, if you compare the blocks that match you can see that Sooty and Sweep both have the same expiry block (b85bb230876912bf3c66e50758b222d0) and both Sweep and Soo have the same level block (83f2d277d9e5fb9a951e74bee57c77a3). This matches with what we know about the tokens as both Sooty and Sweep have expired tokens and both Sweep and Soo are users, not admins.
		</p>
		<p>
		Knowing all this, we can now create our forged session token. We need to take the username block from Sweep, the expiry block from Soo and the level block from Sooty. We can then finish the token off with the remaining blocks from any of the tokens. This gives us:
		</p>
<pre><code>3061837c4f9debaf19d4539bfa0074c1 <- Sweep as username
174d4b2659239bbc50646e14a70becef <- Soo's expiry time
837d1e6b16bfae07b776feb7afe57630 <- Sooty's admin privileges
caeb574f10f349ed839fbfd223903368 <- Finish off with Sweep's bio
873580b2e3e494ace1e9e8035f0e7e07</code></pre>
		<p>
			Which gives us...
		</p>
		<p>
<textarea style="width:746px; height: 35px">3061837c4f9debaf19d4539bfa0074c1174d4b2659239bbc50646e14a70becef837d1e6b16bfae07b776feb7afe57630caeb574f10f349ed839fbfd223903368873580b2e3e494ace1e9e8035f0e7e07</textarea>
		</p>
		<p>
		This is a very contrived setup with the tokens tweaked to force blocks to map to the JSON object so manipulation is easier to do, in the real world it is unlikely to be this easy however as data is often formed from fixed sized blocks overlaps can happen in a way that mixing blocks up results in valid data. Sometimes just being able to pass invalid data is enough so all that is needed is to swap blocks in a way that they can be decrypted and then passed on to the rest of the system where they will cause errors.
		</p>
		<p>
		If you want to play with this some more, there is a script called <a href="cryptography/source/download_ecb_attack.php" download>ecb_attack.php</a> in the sources directory which shows how the tokens were generated and lets you combine them in different ways to create custom tokens.
		</p>
		</div>

		<h3>High Level</h3>
		<p>The system is using AES-128-CBC which means it is vulnerable to a padding oracle attack.</p>

		<p>
		<button onclick="show_answer('high')">Show Answer</button>
		</p>

		<div id="high_answer">
		<p>Rather than try to explain this here, go read this excelent write up on the attack by Eli Sohl.</p>
		<p><a target="_blank" href="https://www.nccgroup.com/uk/research-blog/cryptopals-exploiting-cbc-padding-oracles/">Cryptopals: Exploiting CBC Padding Oracles</a></p>
		<p>
		If you want to play with this some more, there is a script called <a href="cryptography/source/download_oracle_attack.php" download>oracle_attack.php</a> in the sources directory which runs through the full attack with debug. You can run this either against the DVWA site or it will run locally against its own pretend web server.
		</p>
		</div>

		<h3>Impossible Level</h3>
		<p>You can never say impossible in crypto as something that would take years today could take minutes in the future when a new attack is found or when processing power takes a giant leam forward.</p>
		<p>
		The current recommended alternative to AES-CBC is AES-GCM and so the system uses that here. 256 bit blocks rather than 128 bit blocks are used, and a unique IV used for every message. This may be secure today but who knows what tomorrow brings?
		</p>
	</div></td>
	</tr>
	</table>

	</div>
	
</div>
