<?php declare(strict_types=1);

namespace App\Entity;

use App\AbstractList;
use Doctrine\Common\Collections\ArrayCollection;

class ProductionCompanyList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public function add(ProductionCompany $productionCompany) : void
    {
        $this->data[] = $productionCompany;
    }

    public function asArrayCollection() : ArrayCollection
    {
        $arrayCollection = new ArrayCollection();

        foreach ($this->data as $productionCompany) {
            $arrayCollection->add($productionCompany);
        }

        return $arrayCollection;
    }
}