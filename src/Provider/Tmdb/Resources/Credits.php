<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

class Credits
{
    private $cast;

    private function __construct(Cast $cast)
    {
        $this->cast = $cast;
    }

    public static function createFromArray(array $data)
    {
        return new self(
            Cast::createFromArray($data['cast'])
        );
    }

    public function getCast() : Cast
    {
        return $this->cast;
    }
}