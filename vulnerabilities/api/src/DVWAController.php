<?php

# Start the app with:
#
# php -S localhost:8000 -t public

namespace Src;

use OpenApi\Attributes as OA;

# This is the definition for the whole file.
#[OA\Info(title: "DVWA Sample API", version: "0.1")]

class DVWAController
{
	private $data = array (
		1 => array ("name" => "Admin", "level" => 1),
		2 => array ("name" => "Robin", "level" => 0),
		3 => array ("name" => "Fred", "level" => 0)
	);
	private $userId = null;
	private $requestMethod = "GET";

	public function __construct($requestMethod, $userId) {
		$this->requestMethod = $requestMethod;
		$this->userId = $userId;
	}

	public function getData()
	{
		return $this->data;
	}
	
	#[OA\Post(path: '/api/data.json')]
	#[OA\Response(response: '200', description: 'Data updated')]
	public function setData($data)
	{
		$this->data = $data;
	}

	private function getAllUsers() {
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($this->data);
		return $response;
	}

	#[OA\Get(path: '/dvwaapi/data.json')]
	#[OA\Response(response: '200', description: 'The data')]
	private function getUser($id) {
		if (!array_key_exists ($id, $this->data)) {
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($this->data[$id]);
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

	private function validatePerson($input)
	{
		if (! isset($input['name'])) {
			return false;
		}
		if (! isset($input['level'])) {
			return false;
		}
		if (!is_numeric ($input['level'])) {
			return false;
		}
		return true;
	}

	# curl -X POST --data '{"name": "sue", "level": 2}'  http://localhost:8000/dvwaapi -i
	private function createUserFromRequest()
	{
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (! $this->validatePerson($input)) {
			return $this->unprocessableEntityResponse();
		}
		$this->data[] = $input;
		$response['status_code_header'] = 'HTTP/1.1 201 Created';
		$response['body'] = null;
		return $response;
	}

	# curl -X PUT --data '{"name": "sue", "level": 2}'  http://localhost:8000/dvwaapi/3 -i
	private function updateUserFromRequest($id)
	{
		if (!array_key_exists ($id, $this->data)) {
			return $this->notFoundResponse();
		}
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (! $this->validatePerson($input)) {
			return $this->unprocessableEntityResponse();
		}
		$this->data[$id] = $input;
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}	

	# curl -X DELETE http://localhost:8000/dvwaapi/2 -i
	private function deleteUser($id) {
		if (!array_key_exists ($id, $this->data)) {
			return $this->notFoundResponse();
		}
		unset ($this->data[$id]);
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	public function processRequest() {
		switch ($this->requestMethod) {
			case 'GET':
				if ($this->userId) {
					$response = $this->getUser($this->userId);
				} else {
					$response = $this->getAllUsers();
				};
				break;
			case 'POST':
				$response = $this->createUserFromRequest();
				break;
			case 'PUT':
				$response = $this->updateUserFromRequest($this->userId);
				break;
			case 'DELETE':
				$response = $this->deleteUser($this->userId);
				break;
			default:
				$response = $this->notFoundResponse();
				break;
		}
		header($response['status_code_header']);
		if ($response['body']) {
			echo $response['body'];
		}
	}

	private function notFoundResponse() {
		$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
		$response['body'] = null;
		return $response;
	}
}
