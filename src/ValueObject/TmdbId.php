<?php declare(strict_types=1);

namespace App\ValueObject;

class TmdbId
{
    private $id;

    private function __construct(int $id)
    {
        $this->ensureValidFormat($id);

        $this->id = $id;
    }

    public static function createByString(string $id) : self
    {
        return new self((int)$id);
    }

    public function getId() : int
    {
        return $this->id;
    }

    private function ensureValidFormat(int $id)
    {
        if ($id < 0) {
            throw new \Exception('Format of id is invalid: ' . $id);
        }
    }

    public function __toString() : string
    {
        return (string)$this->id;
    }
}