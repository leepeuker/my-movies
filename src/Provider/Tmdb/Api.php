<?php declare(strict_types=1);

namespace App\Provider\Tmdb;

use App\Provider\Tmdb;
use App\ValueObject\TmdbId;

class Api
{
    private $client;

    public function __construct()
    {
        $token        = new \Tmdb\ApiToken($_ENV['TMDB_TOKEN']);
        $this->client = new \Tmdb\Client($token);
    }

    public function getMovie(TmdbId $tmdbId) : Tmdb\Resources\Movie
    {
        return Tmdb\Resources\Movie::createFromArray($this->client->getMoviesApi()->getMovie($tmdbId));
    }
}