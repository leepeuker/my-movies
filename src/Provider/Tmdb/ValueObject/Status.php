<?php declare(strict_types=1);

namespace App\Provider\Tmdb\ValueObject;

class Status
{
    private const RUMORED         = 'Rumored';
    private const PLANNED         = 'Planned';
    private const IN_PRODUCTION   = 'In Production';
    private const POST_PRODUCTION = 'Post Production';
    private const RELEASED        = 'Released';
    private const CANCELED        = 'Canceled';
    private const VALID_FORMATS   = [
        self::RUMORED,
        self::PLANNED,
        self::IN_PRODUCTION,
        self::POST_PRODUCTION,
        self::RELEASED,
        self::CANCELED
    ];

    private $status;

    private function __construct(string $status)
    {
        $this->ensureValidFormat($status);

        $this->status = $status;
    }

    public function __toString() : string
    {
        return $this->status;
    }

    public static function createFromString(string $status) : self
    {
        return new self($status);
    }

    private function ensureValidFormat(string $status)
    {
        if (in_array($status, self::VALID_FORMATS) === false) {
            throw new \Exception('Format of status is invalid: ' . $status);
        }
    }
}