<?php declare(strict_types=1);

namespace App\Letterboxd\Resources\Diary;

use App\ValueObject\AbstractList;

class ItemList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(Item $item) : void
    {
        $this->data[] = $item;
    }
}