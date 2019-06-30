<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\ValueObject\Id;
use App\ValueObject\Name;

class Genre
{
    private $id;

    private $name;

    private function __construct(Id $id, Name $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public static function createFromArray(array $data)
    {
        return new self(
            Id::createFromInt($data['id']),
            Name::createFromString($data['name'])
        );
    }

    public function getName() : Name
    {
        return $this->name;
    }

    public function getId() : Id
    {
        return $this->id;
    }
}