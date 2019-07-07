<?php declare(strict_types=1);

namespace App\Entity;

use App\AbstractList;
use Doctrine\Common\Collections\ArrayCollection;

class PersonList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(Person $person) : void
    {
        $this->data[] = $person;
    }

    public function asArrayCollection() : ArrayCollection
    {
        $arrayCollection = new ArrayCollection();

        foreach ($this->data as $person) {
            $arrayCollection->add($person);
        }

        return $arrayCollection;
    }
}