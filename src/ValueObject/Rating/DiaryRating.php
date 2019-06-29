<?php declare(strict_types=1);

namespace App\ValueObject\Rating;

class DiaryRating implements Rating
{
    private $rating;

    private function __construct(int $rating)
    {
        $this->ensureValidRating($rating);

        $this->rating = $rating;
    }

    public static function createByInt(int $rating) : self
    {
        return new self($rating);
    }

    public function __toString() : string
    {
        return (string)$this->rating;
    }

    public function asInt() : int
    {
        return $this->rating;
    }

    private function ensureValidRating(int $rating)
    {
        if ($rating < 0 || $rating > 5) {
            throw new \Exception('Format of rating is invalid: ' . $rating);
        }
    }

    public function getAsStars() : string
    {
        return str_repeat('*', $this->asInt());
    }
}