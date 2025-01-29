<?php

# Start the app with:
#
# php -S localhost:8000 -t public

namespace Src;

use OpenApi\Attributes as OAT;

class GenericController
{
	private $command = null;
	private $requestMethod = "GET";

	public function __construct($command) {
		$this->command = $command;
	}

	private function optionsResponse() {
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

	private function notFoundResponse() {
		$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
		$response['body'] = null;
		return $response;
	}
	
	private function methodNotSupported() {
		$response['status_code_header'] = 'HTTP/1.1 405 Method Not Supported';
		$response['body'] = null;
		return $response;
	}

	private function teapotResponse() {
		$response['status_code_header'] = "HTTP/1.1 418 I'm a teapot";
		$response['body'] = null;
		return $response;
	}

	public function processRequest() {
		switch ($this->command) {
			case "teapot":
				$response = $this->teapotResponse();
				break;
			case "notfound":
				$response = $this->notFoundResponse();
				break;
			case "notSupported":
				$response = $this->methodNotSupported();
				break;
			case "unprocessable":
				$response = $this->unprocessableEntityResponse();
				break;
			case "options":
				$response = $this->optionsResponse();
				break;
			default:
				$response = $this->notFoundResponse();
				break;
		};
		header($response['status_code_header']);
		if ($response['body']) {
			echo $response['body'];
		}
		exit();
	}
}
