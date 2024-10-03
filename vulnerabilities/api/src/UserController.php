<?php

namespace Src;

use OpenApi\Attributes as OAT;

# This is the definition for the whole file.
#[OAT\Info(title: "DVWA API", version: "0.1")]
#[OAT\Contact(email: "robin@digi.ninja", url: "https://github.com/digininja/DVWA/")]

# It would be good if this could be dynamic but the $_SERVER variables
# aren't available when the Swagger generator scripts run so I can't
# get the HTTP_HOST value from them. For now I'm hard coding it to make
# my dev life easier.
#[OAT\Server(url: 'http://dvwa.test', description: "API server")]

#[OAT\Tag(name: "user", description: "User operations.")]
#[OAT\Tag(name: "health", description: "Health operations.")]

class UserController
{
	private $data = array ();
	private $userId = null;
	private $requestMethod = "GET";

	public function __construct($requestMethod, $userId) {
		$this->data = array (
			1 => new User (1, "admin", 0),
			2 => new User (2, "robin", 1),
			3 => new User (3, "fred", 1),
		);
		$this->requestMethod = $requestMethod;
		$this->userId = $userId;
	}

	private function validateAdd($input)
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

	private function validateUpdate($input)
	{
		if (! isset($input['name'])) {
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
			$gc = new GenericController("notFound");
			$gc->processRequest();
			exit();
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode ($this->data[$id]->toArray());
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
		$all = array();
		foreach ($this->data as $user) {
			$all[] = $user->toArray();
		}
		$response['body'] = json_encode($all);
		return $response;
	}

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

	private function addUser()
	{
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (! $this->validateAdd($input)) {
			$gc = new GenericController("unprocessable");
			$gc->processRequest();
			exit();
		}
		$user = new User(null, $input['name'], intval ($input['level']));
		$this->data[] = $user;
		$response['status_code_header'] = 'HTTP/1.1 201 Created';
		$response['body'] = json_encode($user->toArray());
		return $response;
	}

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
	
	private function updateUser($id)
	{
		if (!array_key_exists ($id, $this->data)) {
			$gc = new GenericController("notFound");
			$gc->processRequest();
			exit();
		}
		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (! $this->validateUpdate($input)) {
			$gc = new GenericController("unprocessable");
			$gc->processRequest();
			exit();
		}
		if (array_key_exists ("name", $input)) {
			$this->data[$id]->name = $input['name'];
		}
		if (array_key_exists ("level", $input)) {
			$this->data[$id]->level = intval ($input['level']);
		}
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = json_encode ($this->data[$id]->toArray());
		return $response;
	}	

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
			$gc = new GenericController("notFound");
			$gc->processRequest();
			exit();
		}
		unset ($this->data[$id]);
		$response['status_code_header'] = 'HTTP/1.1 200 OK';
		$response['body'] = null;
		return $response;
	}

	private function options() {
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
				$response = $this->addUser();
				break;
			case 'PUT':
				$response = $this->updateUser($this->userId);
				break;
			case 'DELETE':
				$response = $this->deleteUser($this->userId);
				break;
			case 'OPTIONS':
				$response = $this->options();
				break;
			default:
				$gc = new GenericController("notSupported");
				$gc->processRequest();
				exit();
				break;
		}
		header($response['status_code_header']);
		if ($response['body']) {
			echo $response['body'];
		}
	}
}
