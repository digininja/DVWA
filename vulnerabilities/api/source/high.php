<?php

$message = "";

$html .= "
	<p>
		Here is the <a href='openapi.yml'>OpenAPI</a> document, have a look the health functions and see if you can find one that has a vulnerability.
	</p>
	<p>
		You might be able to work out how to call the individual functions by hand, but it would be a lot easier to import it into an application such as <a href='https://swagger.io/tools/swagger-ui/'>Swagger UI</a>, <a href='https://portswigger.net/bappstore/6bf7574b632847faaaa4eb5e42f1757c'>Burp</a>, <a href='https://www.zaproxy.org/docs/desktop/addons/openapi-support/'>ZAP</a>, or <a href='https://www.postman.com/'>Postman</a> and let the tool do the hard work of setting the requests up for you.
	</p>
";

?>
