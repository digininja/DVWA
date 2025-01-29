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

    #[OAT\Post(
		tags: ["health"],
        path: '/vulnerabilities/api/v2/health/echo',
        operationId: 'echo',
		description: 'Echo, echo, cho, cho, o o ....',
        parameters: [
                new OAT\RequestBody (
					description: 'Your words.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: Words::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
        ]
    )   
    ]
	
	private function echo() {
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (array_key_exists ("words", $input)) {
			$words = $input['words'];

			$response['status_code_header'] = 'HTTP/1.1 200 OK';
			$response['body'] = json_encode (array ("reply" => $words));
		} else {
			$response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
			$response['body'] = json_encode (array ("status" => "Words not specified"));
		}
		return $response;
	}

    #[OAT\Post(
		tags: ["health"],
        path: '/vulnerabilities/api/v2/health/connectivity',
        operationId: 'checkConnectivity',
		description: 'The server occasionally loses connectivity to other systems and so this can be used to check connectivity status.',
        parameters: [
                new OAT\RequestBody (
					description: 'Remote host.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: Target::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
        ]
    )   
    ]
	
	private function checkConnectivity() {
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (array_key_exists ("target", $input)) {
			$target = $input['target'];

			exec ("ping -c 4 " . $target, $output, $ret_var);

			if ($ret_var == 0) {
				$response['status_code_header'] = 'HTTP/1.1 200 OK';
				$response['body'] = json_encode (array ("status" => "OK"));
			} else {
				$response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
				$response['body'] = json_encode (array ("status" => "Connection failed"));
			}
		} else {
			$response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
			$response['body'] = json_encode (array ("status" => "Target not specified"));
		}
		return $response;
	}

    #[OAT\Get(
		tags: ["health"],
        path: '/vulnerabilities/api/v2/health/status',
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
        path: '/vulnerabilities/api/v2/health/ping',
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
			case 'POST':
				switch ($this->command) {
					case "echo":
						$response = $this->echo();
						break;
					case "connectivity":
						$response = $this->checkConnectivity();
						break;
					default:
						$gc = new GenericController("notFound");
						$gc->processRequest();
						exit();
				};
				break;
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
			case 'OPTIONS':
				$gc = new GenericController("options");
				$gc->processRequest();
				break;
			default:
				$gc = new GenericController("notSupported");
				$gc->processRequest();
				break;
		}
		header($response['status_code_header']);
		if ($response['body']) {
			echo $response['body'];
		}
	}
}

#[OAT\Schema(required: ['target'])]
final class Target {
    #[OAT\Property(example: "digi.ninja")]
    public string $target;
}

#[OAT\Schema(required: ['words'])]
final class Words {
    #[OAT\Property(example: "Hello World")]
    public string $words;
}

