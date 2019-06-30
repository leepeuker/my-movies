<?php declare(strict_types=1);

namespace App\ValueObject;

class Id
{
    private $id;

    private function __construct(int $id)
    {
        $this->ensureValidFormat($id);

        $this->id = $id;
    }

    public static function createFromInt(int $id) : self
    {
        return new self($id);
    }

    public static function createFromString(string $id) : self
    {
        return new self((int)$id);
    }

    public function __toString() : string
    {
        return (string)$this->id;
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
}