<?php declare(strict_types=1);

namespace App\Entity;

use App\AbstractList;
use Doctrine\Common\Collections\ArrayCollection;

class WatchDateList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(WatchDate $watchDate) : void
    {
        $this->data[] = $watchDate;
    }

    public function asArrayCollection() : ArrayCollection
    {
        $arrayCollection = new ArrayCollection();

        foreach ($this->data as $watchDate) {
            $arrayCollection->add($watchDate);
        }

        return $arrayCollection;
    }

    public function has(WatchDate $newWatchDate) : bool
    {
        /** @var WatchDate $watchDate */
        foreach ($this->data as $watchDate) {
            if ((string)$watchDate->getDate() === (string)$newWatchDate->getDate()) {
                return true;
            }
        }

        return false;
    }

    public function remove(WatchDate $oldWatchDate) : void
    {
        /** @var WatchDate $watchDate */
        foreach ($this->data as $key => $watchDate) {
            if ((string)$watchDate->getDate() === (string)$oldWatchDate->getDate()) {
                unset($this->data[$key]);
            }
        }
    }
}