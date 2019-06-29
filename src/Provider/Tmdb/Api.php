<?php declare(strict_types=1);

namespace App\Provider\Tmdb;

use App\ValueObject\TmdbId;
use Tmdb\Api\Movies;

class Api
{
    private $client;

    public function __construct()
    {
        $token        = new \Tmdb\ApiToken('785497e189cc92acb753dfe5d69da20d');
        $this->client = new \Tmdb\Client($token);
    }

    public function getMovie(TmdbId $tmdbId) : array
    {
        return $this->client->getMoviesApi()->getMovie($tmdbId);
    }
}