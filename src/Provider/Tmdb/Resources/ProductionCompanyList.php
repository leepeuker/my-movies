<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\AbstractList;

class ProductionCompanyList extends AbstractList
{
    public static function create() : self
    {
        return new self();
    }

    public static function createFromArray(array $items) : self
    {
        $productionCompanyList = self::create();

        foreach ($items as $item) {
            $productionCompanyList->add(ProductionCompany::createFromArray($item));
        }

        return $productionCompanyList;
    }

    public function add(ProductionCompany $productionCompany) : void
    {
        $this->data[] = $productionCompany;
    }
}