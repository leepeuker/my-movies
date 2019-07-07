<?php declare(strict_types=1);

namespace App\Entity;

use App\AbstractList;
use Doctrine\Common\Collections\ArrayCollection;

class MovieList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(Movie $movie) : void
    {
        $this->data[] = $movie;
    }

    public function asArrayCollection() : ArrayCollection
    {
        $arrayCollection = new ArrayCollection();

        foreach ($this->data as $movie) {
            $arrayCollection->add($movie);
        }

        return $arrayCollection;
    }
}