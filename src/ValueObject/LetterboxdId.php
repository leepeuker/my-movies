<?php declare(strict_types=1);

namespace App\ValueObject;

class LetterboxdId
{
    private $id;

    private function __construct(string $id)
    {
        $this->ensureValidLetterboxedId($id);

        $this->id = $id;
    }

    public static function createByString(string $id) : self
    {
        return new self($id);
    }

    public function getId() : string
    {
        return $this->id;
    }

    private function ensureValidLetterboxedId(string $id)
    {
        if ((bool)preg_match('/^[a-z0-9][-a-z0-9]*$/', $id) === false) {
            throw new \Exception('Format of id is invalid: ' . $id);
        }
    }

    public function __toString() : string
    {
        return $this->id;
    }
}