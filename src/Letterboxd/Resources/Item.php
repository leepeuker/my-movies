<?php declare(strict_types=1);

namespace App\Letterboxd\Resources;

use App\ValueObject\LetterboxdId;
use App\ValueObject\Title;
use App\ValueObject\Year;

abstract class Item
{
    protected $letterboxdId;

    protected $title;

    protected $year;

    protected function __construct(LetterboxdId $letterboxdLetterboxdId, Title $title, Year $year)
    {
        $this->letterboxdId = $letterboxdLetterboxdId;
        $this->title        = $title;
        $this->year         = $year;
    }

    public function getLetterboxdId() : LetterboxdId
    {
        return $this->letterboxdId;
    }

    public function getTitle() : Title
    {
        return $this->title;
    }

    public function getYear() : Year
    {
        return $this->year;
    }
}