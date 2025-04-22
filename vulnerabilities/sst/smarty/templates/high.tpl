{config_load file="dvwa.conf"}
<html>
  <head>
    <title>{#pageTitle#}</title>
  </head>
  <body>
	<h2>HTTP With Basic Authentication</h2>
	<p>The following content has been loaded from a local URL which is protected by basic authentication. If you try to <a href='http://dvwa.test/vulnerabilities/sst/protected/index.php'>access it directly</a>, it will ask you for your login credentials.</p>
	{capture assign=url}{$protocol}://admin:secret@{$host}/{$page}{/capture}
	{fetch file={$url} assign="fetched_file"}
	{$fetched_file}

	<h2>FTP Retrieval</h2>
	<p>Smarty can also retrieve files through FTP.</p>
	{fetch file='ftp://ftp:secretdvwaaccount@dvwa.com@ftp.scene.org/pub/index.txt' assign='ftp'}
	<p>Scene.org is an archive of ASCII art, demos, music and graphics, you can access it <a href="https://ftp.scene.org/pub/">here</a>.</p>
	<p>This is what you can expect to find on the site:</p>
  	<pre>{$ftp}</pre>
  </body>
</html>
