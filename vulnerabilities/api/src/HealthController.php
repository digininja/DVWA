<?php

# Start the app with:
#
# php -S localhost:8000 -t public

namespace Src;

use OpenApi\Attributes as OAT;

class HealthController
{
	private $command = null;
	private $requestMethod = "GET";

	public function __construct($requestMethod, $version, $command) {
		$this->requestMethod = $requestMethod;
		$this->command = $command;
	}

    #[OAT\Get(
		tags: ["health"],
        path: '/vulnerabilities/api/health/status',
        operationId: 'getHealthStatus',
		description: 'Get the health of the system.',
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
        ]
    )   
    ]
	
	private function getStatus() {
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode (array ("status" => "OK"));
		return $response;
	}

    #[OAT\Get(
		tags: ["health"],
        path: '/vulnerabilities/api/health/ping',
        operationId: 'ping',
		description: 'Simple ping/pong to check connectivity.',
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
        ]
    )   
    ]
	private function ping() {
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode (array ("Ping" => "Pong"));
		return $response;
	}

	public function processRequest() {
		switch ($this->requestMethod) {
			case 'GET':
				switch ($this->command) {
					case "status":
						$response = $this->getStatus();
						break;
					case "ping":
						$response = $this->ping();
						break;
					default:
						$gc = new GenericController("notFound");
						$gc->processRequest();
						exit();
				};
				break;
			default:
				$gc = new GenericController("notSupported");
				$gc->processRequest();
				exit();
		}
		header($response['status_code_header']);
		if ($response['body']) {
			echo $response['body'];
		}
	}
}
