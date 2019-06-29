<?php declare(strict_types=1);

namespace App\ValueObject;

class Title
{
    private $title;

    private function __construct(string $title)
    {
        $this->ensureValidFormat($title);

        $this->title = $title;
    }

    public static function createFromString(string $title) : self
    {
        return new self($title);
    }

    public function __toString() : string
    {
        return $this->title;
    }

    private function ensureValidFormat(string $title) : void
    {
        if (strlen($title) > 255) {
            throw new \Exception('Title too long, max length 255: ' . $title);
        }
    }
}