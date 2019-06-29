<?php declare(strict_types=1);

namespace App\Letterboxd\Resources\Diary;

use App\ValueObject\DateTime;
use App\ValueObject\LetterboxdId;
use App\ValueObject\Rating\DiaryRating;
use App\ValueObject\Title;
use App\ValueObject\Year;

class Mapper
{
    public function mapToList($itemListData) : ItemList
    {
        $itemList = ItemList::create();

        foreach ($itemListData as $itemData) {
            $itemList->add(
                $this->mapToItem($itemData)
            );
        }

        return $itemList;
    }

    private function extractId(string $letterboxedUri) : string
    {
        $uriPath = parse_url($letterboxedUri, PHP_URL_PATH);

        if ($uriPath == null) {
            throw new \Exception('Broken letterboxed uri: ' . $letterboxedUri);
        }

        return explode('/', $uriPath)['3'];
    }

    private function mapToItem($itemData) : Item
    {
        return Item::createByParameters(
            LetterboxdId::createByString($this->extractId($itemData['Letterboxd URI'])),
            Title::createFromString($itemData['Name']),
            Year::createFromInt((int)$itemData['Year']),
            DiaryRating::createByInt((int)$itemData['Rating']),
            (bool)$itemData['Rewatch'],
            DateTime::createFromString($itemData['Watched Date'])
        );
    }
}