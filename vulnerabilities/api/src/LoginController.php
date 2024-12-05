<?php

namespace Src;

use OpenApi\Attributes as OAT;

class LoginController
{
	private $command = null;
	private $requestMethod = "GET";

	public function __construct($requestMethod, $version, $command) {
		$this->requestMethod = $requestMethod;
		$this->command = $command;
	}

    #[OAT\Post(
		tags: ["login"],
        path: '/vulnerabilities/api/v2/login/login',
        operationId: 'login',
		description: 'Login as user.',
        parameters: [
                new OAT\RequestBody (
					description: 'The login credentials.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: Credentials::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
            new OAT\Response(
                response: 401,
                description: 'Invalid credentials.',
            ),
        ]
    )   
    ]

	private function login() {
		$ret = Helpers::check_content_type();
		if ($ret !== true) {
			return $ret;
		}

		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (array_key_exists ("username", $input) && 
			array_key_exists ("password", $input)) {
			$username = $input['username'];
			$password = $input['password'];

			if ($username == "mrbennett" && $password == "becareful") {
				$response['status_code_header'] = 'HTTP/1.1 200 OK';
				$response['body'] = json_encode (array ("token" => "12345"));
			} else {
				$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
				$response['body'] = json_encode (array ("status" => "Invalid credentials"));
			}
		} else {
			$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
			$response['body'] = json_encode (array ("status" => "Missing credentials"));
		}
		return $response;
	}

    #[OAT\Post(
		tags: ["login"],
        path: '/vulnerabilities/api/v2/login/check_token',
        operationId: 'check_token',
		description: 'Check a token is valid.',
        parameters: [
                new OAT\RequestBody (
					description: 'The token to test.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: Token::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
            new OAT\Response(
                response: 401,
                description: 'Token is invalid.',
            ),
        ]
    )   
    ]
	
	private function check_token() {
		$ret = Helpers::check_content_type();
		if ($ret !== true) {
			return $ret;
		}

		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (array_key_exists ("token", $input)) {
			$token = $input['token'];
			if ($token == "12345") {
				$response['status_code_header'] = 'HTTP/1.1 200 OK';
				$response['body'] = json_encode (array ("token" => "Valid"));
			} else {
				$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
				$response['body'] = json_encode (array ("status" => "Invalid"));
			}
		} else {
			$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
			$response['body'] = json_encode (array ("status" => "Missing token"));
		}
		return $response;
	}

	public function processRequest() {
		switch ($this->requestMethod) {
			case 'POST':
				switch ($this->command) {
					case "login":
						$response = $this->login();
						break;
					case "check_token":
						$response = $this->check_token();
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

#[OAT\Schema(required: ['username', 'password'])]
final class Credentials {
    #[OAT\Property(example: "user")]
    public string $username;
    #[OAT\Property(example: "password")]
    public string $password;
}

#[OAT\Schema(required: ['token'])]
final class Token {
    #[OAT\Property(example: "11111")]
    public string $token;
}
