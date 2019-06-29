<?php declare(strict_types=1);

namespace App\Letterboxd\Resources\Diary;

use App\ValueObject\DateTime;
use App\ValueObject\LetterboxdId;
use App\ValueObject\Rating\DiaryRating;
use App\ValueObject\Title;
use App\ValueObject\Year;

class Item extends \App\Letterboxd\Resources\Item
{
    private $isRewatch;

    private $rating;

    private $watchDate;

    protected function __construct(LetterboxdId $letterboxdId, Title $title, Year $year, DiaryRating $rating, bool $isRewatch, DateTime $watchDate)
    {
        parent::__construct($letterboxdId, $title, $year);

        $this->rating    = $rating;
        $this->isRewatch = $isRewatch;
        $this->watchDate = $watchDate;
    }

    public static function createByParameters(LetterboxdId $letterboxdId, Title $title, Year $year, DiaryRating $rating, bool $isRewatch, DateTime $watchDate) : self
    {
        return new self ($letterboxdId, $title, $year, $rating, $isRewatch, $watchDate);
    }

    public function getRating() : DiaryRating
    {
        return $this->rating;
    }

    public function getWatchDate() : DateTime
    {
        return $this->watchDate;
    }

    public function isRewatch() : bool
    {
        return $this->isRewatch;
    }
}