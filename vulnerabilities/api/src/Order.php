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
final class Order
{
    #[OAT\Property(type: 'integer', example: 1)]
    public int $id;

    #[OAT\Property(type: "string", example: "Tony Hart")]
    public string $name;

    #[OAT\Property(type: "string", example: "BBC Television Centre, London W3 6XZ")]
    public string $address;

    #[OAT\Property(type: "string", example: "1 * brush, 2 * paints, 1 * easel")]
    public string $items;

    #[OAT\Property(type: 'integer', example: 1)]
    public int $status;

	function __construct ($id, $name, $address, $items, $status) {
		if (is_null ($id)) {
			$id = mt_rand(50,100);
		}
		$this->id = $id;
		$this->name = $name;
		$this->address = $address;
		$this->items = $items;
		$this->status = $status;
	}

	public function toArray($version) {
		$a = array (
			"id" => $this->id,
			"name" => $this->name,
			"address" => $this->address,
			"items" => $this->items,
			"status" => $this->status,
		);

		return $a;
	}
}

#[OAT\Schema(required: ['level', 'name'])]
final class OrderAdd
{
    #[OAT\Property(example: "fred")]
    public string $name;

    #[OAT\Property(example: "1 High Street, Atown")]
    public string $address;

    #[OAT\Property(example: "2 * brushes")]
    public string $items;
}

#[OAT\Schema()]
final class OrderUpdate
{
    #[OAT\Property(example: "fred")]
    public string $name;

    #[OAT\Property(example: "1 High Street, Atown")]
    public string $address;

    #[OAT\Property(example: "2 * brushes")]
    public string $items;
}
