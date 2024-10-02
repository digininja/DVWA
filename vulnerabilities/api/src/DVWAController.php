<?php

# Start the app with:
#
# php -S localhost:8000 -t public

namespace Src;

use OpenApi\Attributes as OAT;

# This is the definition for the whole file.
#[OAT\Info(title: "DVWA Sample API", version: "0.1")]
#[OAT\Contact(email: "robin@digi.ninja", url: "https://github.com/digininja/DVWA/")]

#[OAT\Tag(name: "user", description: "User operations.")]
#[OAT\Tag(name: "health", description: "Health operatons.")]

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

	private function notFoundResponse() {
		$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
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

    #[OAT\Get(
		tags: ["user"],
        path: '/vulnerabilities/api/user/{id}',
        operationId: 'getUserByID',
		description: 'Get a user by ID.',
        parameters: [
            new OAT\Parameter(name: 'id', in: 'path', required: true, schema: new OAT\Schema(type: 'integer')),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
                content: new OAT\JsonContent (ref: '#/components/schemas/User'),

            ),
            new OAT\Response(
                response: 404,
                description: 'User not found.',
            ),
        ]
    )   
    ]  
	
	private function getUser($id)
	{
		if (!array_key_exists ($id, $this->data)) {
			return $this->notFoundResponse();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode ($this->data[$id]);
		return $response;
	}	

    #[OAT\Get(
		tags: ["user"],
        path: '/vulnerabilities/api/user/',
        operationId: 'getUsers',
		description: 'Get all users.',
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(ref: '#/components/schemas/User')
                )
            ),
        ]
    )   
    ]  
	private function getAllUsers() {
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode($this->data);
		return $response;
	}


	# curl -X POST --data '{"name": "sue", "level": 2}'  http://localhost:8000/user -i
	
    #[OAT\Post(
		tags: ["user"],
        path: '/vulnerabilities/api/user/',
        operationId: 'addUser',
		description: 'Create a new user.',
        parameters: [
                new OAT\RequestBody (
					description: 'User data.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: UserAdd::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
                content: new OAT\JsonContent (ref: '#/components/schemas/User'),
            ),
            new OAT\Response(
                response: 422,
                description: 'Invalid user object provided',
            ),
        ]
    )   
    ]  

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

	# curl -X PUT --data '{"name": "sue", "level": 2}'  http://localhost:8000/user/3 -i

    #[OAT\Put(
		tags: ["user"],
        path: '/vulnerabilities/api/user/{id}',
        operationId: 'updateUser',
		description: 'Update a user by ID.',
        parameters: [
            new OAT\Parameter(name: 'id', in: 'path', required: true, schema: new OAT\Schema(type: 'integer')),
                new OAT\RequestBody (
					description: 'New user data.',
                    content: new OAT\MediaType(
                        mediaType: 'application/json',
                        schema: new OAT\Schema(ref: UserUpdate::class)
                    )
                ),

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
                content: new OAT\JsonContent (ref: '#/components/schemas/User'),
            ),
            new OAT\Response(
                response: 404,
                description: 'User not found',
            ),
            new OAT\Response(
                response: 422,
                description: 'Invalid user object provided',
            ),
        ]
    )   
    ]  
	
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

	# curl -X DELETE http://localhost:8000/user/2 -i

    #[OAT\Delete(
		tags: ["user"],
        path: '/vulnerabilities/api/user/{id}',
        operationId: 'deleteUserById',
		description: 'Delete user by ID.',
        parameters: [
            new OAT\Parameter(name: 'id', in: 'path', required: true, schema: new OAT\Schema(type: 'integer')),
        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Successful operation.',
            ),
            new OAT\Response(
                response: 404,
                description: 'User not found',
            ),
        ]
    )   
    ]  
	
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
}
