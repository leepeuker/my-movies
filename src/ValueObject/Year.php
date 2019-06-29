<?php declare(strict_types=1);

namespace App\ValueObject;

class Year
{
    private $year;

    private function __construct(int $year)
    {
        $this->ensureValidFormat($year);

        $this->year = $year;
    }

    public static function createFromInt(int $year) : self
    {
        return new self($year);
    }

    public function getYear() : int
    {
        return $this->year;
    }

    public function __toString() : string
    {
        return (string)$this->year;
    }

    private function ensureValidFormat($year) : void
    {
        if ($year < 1901 || $year > 2155) {
            throw new \Exception('Year has to be between 1901-2155. Year given: ' . $year);
        }
    }
}