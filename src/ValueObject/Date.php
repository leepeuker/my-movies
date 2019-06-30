<?php declare(strict_types=1);

namespace App\ValueObject;

class Date
{
    private $dateTime;

    private function __construct(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function createFromString(string $dateString) : self
    {
        return new self(new \DateTime($dateString, new \DateTimeZone('UTC')));
    }

    public function __toString() : string
    {
        return $this->dateTime->format('Y-m-d');
    }

    public function getYear() : string
    {
        return $this->dateTime->format('Y');
    }

    public function asDateTime() : \DateTime
    {
        return $this->dateTime;
    }
}