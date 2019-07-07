<?php declare(strict_types=1);

namespace App\Entity;

use App\AbstractList;
use Doctrine\Common\Collections\ArrayCollection;

class GenreList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(Genre $genre) : void
    {
        $this->data[] = $genre;
    }

    public function asArrayCollection() : ArrayCollection
    {
        $arrayCollection = new ArrayCollection();

        foreach ($this->data as $genre) {
            $arrayCollection->add($genre);
        }

        return $arrayCollection;
    }
}