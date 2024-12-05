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
#[OAT\Tag(name: "order", description: "Order operations.")]
#[OAT\Tag(name: "login", description: "Login operations.")]

class UserController
{
	private $data = array ();
	private $userId = null;
	private $version = null;
	private $requestMethod = "GET";

	public function __construct($requestMethod, $version, $userId) {
		$this->data = array (
			1 => new User (1, "tony", 0, '1c8bfe8f801d79745c4631d09fff36c82aa37fc4cce4fc946683d7b336b63032'),
			2 => new User (2, "morph", 1, 'e5326ba4359f77c2623244acb04f6ac35c4dfca330ebcccdf9b734e5b1df90a8'),
			3 => new User (3, "chas", 1, 'a89237fc1f9dd8d424d8b8b98b890dbc4a817bfde59af17c39debcc4a14c21de'),
		);
		$this->requestMethod = $requestMethod;
		$this->userId = $userId;
		$this->version = $version;
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
        path: '/vulnerabilities/api/v2/user/{id}',
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
		$response['body'] = json_encode ($this->data[$id]->toArray($this->version));
		return $response;
	}	

    #[OAT\Get(
		tags: ["user"],
        path: '/vulnerabilities/api/v2/user/',
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
			$all[] = $user->toArray($this->version);
		}
		$response['body'] = json_encode($all);
		return $response;
	}

    #[OAT\Post(
		tags: ["user"],
        path: '/vulnerabilities/api/v2/user/',
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
		$ret = Helpers::check_content_type();
		if ($ret !== true) {
			return $ret;
		}

		$input = (array) json_decode(file_get_contents('php://input'), TRUE);
		if (! $this->validateAdd($input)) {
			$gc = new GenericController("unprocessable");
			$gc->processRequest();
			exit();
		}
		$user = new User(null, $input['name'], intval ($input['level']), hash ("sha256", "password"));
		$this->data[] = $user;
		$response['status_code_header'] = 'HTTP/1.1 201 Created';
		$response['body'] = json_encode($user->toArray($this->version));
		return $response;
	}

    #[OAT\Put(
		tags: ["user"],
        path: '/vulnerabilities/api/v2/user/{id}',
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
		$response['body'] = json_encode ($this->data[$id]->toArray($this->version));
		return $response;
	}	

    #[OAT\Delete(
		tags: ["user"],
        path: '/vulnerabilities/api/v2/user/{id}',
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
				$gc = new GenericController("options");
				$gc->processRequest();
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
