<?php declare(strict_types=1);

namespace App\ValueObject;

class DateTime
{
    private $dateTime;

    private function __construct(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function create() : self
    {
        return self::createFromString('now');
    }

    public static function createFromString(string $dateString) : self
    {
        return new self(new \DateTime($dateString, new \DateTimeZone('UTC')));
    }

    public function __toString() : string
    {
        return $this->dateTime->format('Y-m-d H:i:s');
    }

    public function format(string $format) : string
    {
        return $this->dateTime->format($format);
    }
}