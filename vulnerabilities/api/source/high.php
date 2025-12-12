<?php

$message = "";

$html .= "
	<p>
		Here is the <a href='openapi.yml'>OpenAPI</a> document, have a look the health functions and see if you can find one that has a vulnerability.
	</p>
	<p>
		Note, this file assumes you are running DVWA out of the document root, if you have installed it into a subdirectory, such as DVWA, then you will need to update it. Look through the file for the paths, e.g.<br><br>
		<i>/vulnerabilities/api/v2/health/echo</i><br><br>
		and prepend your directory, so if you are in the DVWA directory you would change it to<br><br>
		<i>/DVWA/vulnerabilities/api/v2/health/echo</i>
	</p>
	<p>
		You might be able to work out how to call the individual functions by hand, but it would be a lot easier to import it into an application such as <a href='https://swagger.io/tools/swagger-ui/'>Swagger UI</a>, <a href='https://portswigger.net/bappstore/6bf7574b632847faaaa4eb5e42f1757c'>Burp</a>, <a href='https://www.zaproxy.org/docs/desktop/addons/openapi-support/'>ZAP</a>, or <a href='https://www.postman.com/'>Postman</a> and let the tool do the hard work of setting the requests up for you.
	</p>
";

?>
