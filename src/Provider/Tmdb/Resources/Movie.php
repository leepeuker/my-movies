<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\Provider\Tmdb\ValueObject\Status;
use App\ValueObject\Id;
use App\ValueObject\Date;
use App\ValueObject\Title;

class Movie
{
    private $budget;

    private $genreList;

    private $id;

    private $originalTitle;

    private $original_language;

    private $overview;

    private $rating;

    private $releaseDate;

    private $revenue;

    private $runtime;

    private $status;

    private $tagline;

    private $title;

    private function __construct(
        Id $id,
        Title $title,
        Title $originalTitle,
        string $originalLanguage,
        string $tagline,
        string $overview,
        Date $releaseDate,
        int $budget,
        GenreList $genreList,
        int $revenue,
        ?int $runtime,
        Status $status,
        float $rating
    ) {
        $this->id                = $id;
        $this->title             = $title;
        $this->originalTitle     = $originalTitle;
        $this->original_language = $originalLanguage;
        $this->releaseDate       = $releaseDate;
        $this->budget            = $budget;
        $this->genreList         = $genreList;
        $this->runtime           = $runtime;
        $this->status            = $status;
        $this->rating            = $rating;
        $this->revenue           = $revenue;
        $this->tagline           = $tagline;
        $this->overview          = $overview;
    }

    public static function createFromArray(array $data) : self
    {
        return new self(
            Id::createFromInt($data['id']),
            Title::createFromString($data['title']),
            Title::createFromString($data['original_title']),
            $data['original_language'],
            $data['tagline'],
            $data['overview'],
            Date::createFromString($data['release_date']),
            $data['budget'],
            GenreList::createFromArray($data['genres']),
            $data['revenue'],
            empty($data['runtime']) ? null : $data['runtime'],
            Status::createFromString($data['status']),
            $data['vote_average']
        );
    }

    public function getBudget() : int
    {
        return $this->budget;
    }

    public function getGenres() : GenreList
    {
        return $this->genreList;
    }

    public function getId() : Id
    {
        return $this->id;
    }

    public function getOriginalLanguage() : string
    {
        return $this->original_language;
    }

    public function getOriginalTitle() : Title
    {
        return $this->originalTitle;
    }

    public function getOverview() : string
    {
        return $this->overview;
    }

    public function getRating() : float
    {
        return $this->rating;
    }

    public function getReleaseDate() : Date
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

    public function getStatus() : Status
    {
        return $this->status;
    }

    public function getTagline() : string
    {
        return $this->tagline;
    }

    public function getTitle() : Title
    {
        return $this->title;
    }
}