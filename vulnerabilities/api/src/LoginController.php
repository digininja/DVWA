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

	#
	# Add one of these for refresh
	#
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

	private function loginJSON() {
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
				$response['body'] = json_encode (array ("token" => Login::create_token()));
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

	# This is an attempt at an OAUTH2 client password authentication flow
	private function login() {
		# Default fail, just in case.
		$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
		$response['body'] = json_encode (array ("status" => "Authentication failed"));

		if (array_key_exists ("PHP_AUTH_USER", $_SERVER) && 
			array_key_exists ("PHP_AUTH_PW", $_SERVER)) {
			$client_id = $_SERVER['PHP_AUTH_USER'];
			$client_secret = $_SERVER['PHP_AUTH_PW'];

			# App auth check
			if ($client_id == "1471.dvwa.digi.ninja" && $client_secret == "ABigLongSecret") {

				if (array_key_exists ("grant_type", $_POST)) {
					switch ($_POST['grant_type']) {
						case "password":
							if (array_key_exists ("username", $_POST) && 
								array_key_exists ("password", $_POST)) {
								$username = $_POST['username'];
								$password = $_POST['password'];

								if ($username == "mrbennett" && $password == "becareful") {
									$response['status_code_header'] = 'HTTP/1.1 200 OK';
									$response['body'] = Login::create_token();
								} else {
									$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
									$response['body'] = json_encode (array ("status" => "Invalid user credentials"));
								}
							} else {
								$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
								$response['body'] = json_encode (array ("status" => "Missing user credentials"));
							}
							break;
						case "refresh_token":
							if (array_key_exists ("refresh_token", $_POST)) {
								$refresh_token = $_POST['refresh_token'];

								# Because this is sent in a POST body, any + characters
								# get replaced by a space when the URL decode happens. This
								# puts them back to plus characters.
								$ref = str_replace (" ", "+", $refresh_token);

								if (Login::check_refresh_token($ref)) {
									$response['status_code_header'] = 'HTTP/1.1 200 OK';
									$response['body'] = Login::create_token();
								} else {
									$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
									$response['body'] = json_encode (array ("status" => "Invalid refresh token"));
								}
							} else {
								$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
								$response['body'] = json_encode (array ("status" => "Missing refresh token"));
							}
							break;
						default:
							$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
							$response['body'] = json_encode (array ("status" => "Unknown grant type"));
					}
				} else {
					$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
					$response['body'] = json_encode (array ("status" => "Missing grant type"));
				}
			} else {
				$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
				$response['body'] = json_encode (array ("status" => "Invalid clientid/clientsecret credentials"));
			}
		} else {
			$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
			$response['body'] = json_encode (array ("status" => "Missing clientid/clientsecret credentials"));
		}

		return $response;
	}

	private function refresh() {
	/*
    echo "<p>Hello {$_SERVER['PHP_AUTH_USER']}.</p>";
    echo "<p>You entered {$_SERVER['PHP_AUTH_PW']} as your password.</p>";
	*/
		$ret = Helpers::check_content_type();
		if ($ret !== true) {
			return $ret;
		}

		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (array_key_exists ("refresh_token", $input)) {
			if (array_key_exists ("grant_type", $input)) {
				$token = $input['token'];
				if (Login::check_access_token($token)) {
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
		} else {
			$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
			$response['body'] = json_encode (array ("status" => "Missing token"));
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
			if (Login::check_access_token($token)) {
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
					case "refresh":
						$response = $this->login();
						break;
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

/*
Moving this to its own thing
#[OAT\Schema(required: ['token'])]
final class Token {
    #[OAT\Property(example: "11111")]
    public string $token;
}
*/
