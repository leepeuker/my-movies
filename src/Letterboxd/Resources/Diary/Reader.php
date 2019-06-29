<?php declare(strict_types=1);

namespace App\Letterboxd\Resources\Diary;

class Reader implements \App\Letterboxd\Resources\Reader
{
    private $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper  = $mapper;
    }

    public function loadFromCsv(string $csvPath) : ItemList
    {
        return $this->mapper->mapToList(
            $this->readRows($csvPath)
        );
    }

    private function readRows(string $csvPath) : \Iterator
    {
        $csv = \League\Csv\Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        return $csv->getRecords();
    }
}