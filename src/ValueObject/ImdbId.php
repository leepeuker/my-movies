<?php declare(strict_types=1);

namespace App\ValueObject;

class ImdbId
{
    private $id;

    private function __construct(string $id)
    {
        $this->ensureValidImdbId($id);

        $this->id = $id;
    }

    public static function createFromString(string $id) : self
    {
        return new self($id);
    }

    public function getId() : string
    {
        return $this->id;
    }

    private function ensureValidImdbId(string $id)
    {
        if ((bool)preg_match('/^tt[0-9]{7}/', $id) === false && (bool)preg_match('/^nm[0-9]{7}/', $id) === false) {
            throw new \Exception('Format of id is invalid: ' . $id);
        }
    }

    public function __toString() : string
    {
        return $this->id;
    }
}