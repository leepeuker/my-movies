<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\AbstractList;

class PersonList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public static function createFromArray(array $items) : self
    {
        $personList = self::create();

        foreach ($items as $item) {
            $personList->add(Person::createFromArray($item));
        }

        return $personList;
    }

    public function add(Person $person) : void
    {
        $this->data[] = $person;
    }
}