<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\AbstractList;

class GenreList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public static function createFromArray(array $items) : self
    {
        $genreList = self::create();

        foreach ($items as $item) {
            $genreList->add(Genre::createFromArray($item));
        }

        return $genreList;
    }

    public function add(Genre $slug) : void
    {
        $this->data[] = $slug;
    }
}