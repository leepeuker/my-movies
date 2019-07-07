<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\ValueObject\Id;
use App\ValueObject\Name;

class CastMember
{
    private $character;

    private $name;

    private $order;

    private $personId;

    private function __construct(string $character, Id $personId, Name $name, int $order)
    {
        $this->character = $character;
        $this->personId  = $personId;
        $this->name      = $name;
        $this->order     = $order;
    }

    public static function createFromArray($data) : self
    {
        return new self(
            $data['character'],
            Id::createFromString($data['id']),
            Name::createFromString($data['name']),
            $data['order']
        );
    }

    public function getCharacter() : string
    {
        return $this->character;
    }

    public function getName() : Name
    {
        return $this->name;
    }

    public function getOrder() : int
    {
        return $this->order;
    }

    public function getPersonId() : Id
    {
        return $this->personId;
    }
}