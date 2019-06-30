<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\ValueObject\Id;
use App\ValueObject\Title;

class Movie
{
    private $genreList;

    private $id;

    private $rating;

    private $releaseDate;

    private $revenue;

    private $runtime;

    private $status;

    private $title;

    private function __construct(Id $id, Title $title, string $releaseDate, GenreList $genreList, int $revenue, ?int $runtime, string $status, float $rating)
    {
        $this->id          = $id;
        $this->title       = $title;
        $this->releaseDate = $releaseDate;
        $this->genreList   = $genreList;
        $this->runtime     = $runtime;
        $this->status      = $status;
        $this->rating      = $rating;
        $this->revenue     = $revenue;
    }

    public static function createFromArray(array $data) : self
    {
        return new self(
            Id::createFromInt($data['id']),
            Title::createFromString($data['title']),
            $data['release_date'],
            GenreList::createFromArray($data['genres']),
            $data['revenue'],
            empty($data['runtime']) ? null : $data['runtime'],
            $data['status'],
            $data['vote_average']
        );
    }

    public function getGenreList() : GenreList
    {
        return $this->genreList;
    }

    public function getId() : Id
    {
        return $this->id;
    }

    public function getRating() : float
    {
        return $this->rating;
    }

    public function getReleaseDate() : string
    {
        return $this->releaseDate;
    }

    public function getRevenue() : int
    {
        return $this->revenue;
    }

    public function getRuntime() : ?int
    {
        return $this->runtime;
    }

    public function getStatus() : string
    {
        return $this->status;
    }

    public function getTitle() : Title
    {
        return $this->title;
    }
}