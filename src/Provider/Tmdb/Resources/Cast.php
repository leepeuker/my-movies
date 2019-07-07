<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\AbstractList;

class Cast extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public static function createFromArray(array $items) : self
    {
        $genreList = self::create();

        foreach ($items as $item) {
            $genreList->add(CastMember::createFromArray($item));
        }

        return $genreList;
    }

    public function add(CastMember $slug) : void
    {
        $this->data[] = $slug;
    }
}