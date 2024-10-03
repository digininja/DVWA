<?php declare(strict_types=1);

namespace Src;

use OpenApi\Attributes as OAT;

/*
#[OA\Schema()]
enum UserLevel {
    case ADMIN;
    case USER;
}
*/
#[OAT\Schema()]
final class User
{
    #[OAT\Property(type: 'integer', example: 1)]
    public int $id;

    #[OAT\Property(example: "fred")]
    public string $name;

    #[OAT\Property(type: 'integer', example: 1)]
    public int $level;

	function __construct ($id, $name, $level) {
		if (is_null ($id)) {
			$id = mt_rand(50,100);
		}
		$this->id = $id;
		$this->name = $name;
		$this->level = $level;
	}

	public function toArray() {
		$a = array (
			"id" => $this->id,
			"name" => $this->name,
			"level" => $this->level,
		);
		return $a;
	}
}

#[OAT\Schema(required: ['level', 'name'])]
final class UserAdd
{
    #[OAT\Property(example: "fred")]
    public string $name;

    #[OAT\Property(type: 'integer', example: 1)]
    public int $level;
}

#[OAT\Schema()]
final class UserUpdate
{
    #[OAT\Property(example: "fred")]
    public string $name;
}
