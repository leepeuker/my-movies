<?php declare(strict_types=1);

namespace App\ValueObject;

class Regex
{
    private $pattern;

    private function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public static function createByString(string $pattern)
    {
        return new self($pattern);
    }

    public function getPattern() : string
    {
        return $this->pattern;
    }

    public function __toString()
    {
        return $this->pattern;
    }
}