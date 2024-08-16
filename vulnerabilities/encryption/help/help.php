<div class="body_padded">
	<h1>Help - Encryption Problems</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3>About</h3>
		<p>
		Cryptography is key area of security and is used to keep secrets secret. When implemented badly these secrets can be leaked or the crypto manipulated to bypass protections.
		</p>
		<p>
		This module will look at three weaknesses, using encoding istead of crypto, using algorithms with known weaknesses, and padding oracle attacks.
		</p>

		<br /><hr /><br />

		<h3>Objective</h3>
		<p>Each level has its own objective but the general idea is to exploit weak cryptographic implementaions.</p>

		<br /><hr /><br />

		<h3>Low Level</h3>
		<p>Low level will not check the requested input, before including it to be used in the output text.</p>
		<pre>Spoiler: <span class="spoiler">?name=&lt;script&gt;alert("XSS");&lt;/script&gt;</span>.</pre>

		<br />

		<h3>Medium Level</h3>
		<p>The tokens are encrypted using an Electronic Code Book based algorithm (aes-128-ecb). In this mode, the clear text is broken down into fixed sized blocks and each block is encrypted independantly of the rest. This results in a ciphertext block that is made up from a number of individual blocks with no way to tie them together. Worse than this, any two blocks, from any two clear text inputs, are interchangable as long as they have been ecrypted with the same key. In our example, this means you can take blocks from the three different tokens to make your own token. </p>
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
		If you want to play with this some more, there is a script calle ecb_theory.php in the sources directory which shows how the tokens were generated and lets you combine them in different ways to create custom tokens.
		</p>
		<pre>Spoiler: <span class="spoiler">



		</span></pre>

		<br />

		<h3>High Level</h3>
		<p>The developer now believes they can disable all JavaScript by removing the pattern "&lt;s*c*r*i*p*t".</p>
		<pre>Spoiler: <span class="spoiler">HTML events</span>.</pre>

		<br />

		<h3>Impossible Level</h3>
		<p>Using inbuilt PHP functions (such as "<?php echo dvwaExternalLinkUrlGet( 'https://secure.php.net/manual/en/function.htmlspecialchars.php', 'htmlspecialchars()' ); ?>"),
			its possible to escape any values which would alter the behaviour of the input.</p>
	</div></td>
	</tr>
	</table>

	</div>

	<br />

	<p>Reference: <?php echo dvwaExternalLinkUrlGet( 'https://www.owasp.org/index.php/Cross-site_Scripting_(XSS)' ); ?></p>
</div>

