<?php declare(strict_types=1);

namespace App\Letterboxd;

use App\Provider\Imdb;
use App\Provider\Provider;
use App\Provider\Tmdb;
use App\ValueObject\Id;
use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use Symfony\Component\HttpClient\HttpClient;

class Api
{
    private const RESOURCE_URL = 'https://letterboxd.com/film/';

    public function getProviderIdsByLetterboxdId(LetterboxdId $letterboxdId) : array
    {
        $page = $this->fetchPageById($letterboxdId);

        return [
            'imdb' => ImdbId::createFromString(
                $this->getProviderIdByPageAndPattern($page, Imdb\Provider::create())
            ),
            'tmdb' => Id::createFromString(
                $this->getProviderIdByPageAndPattern($page, Tmdb\Provider::create())
            ),
        ];
    }

    private function fetchPageById(LetterboxdId $letterboxdId) : string
    {
        try {
            $response    = HttpClient::create()->request('GET', self::RESOURCE_URL . $letterboxdId);
            $pageContent = $response->getContent(true);
        } catch (\Throwable $t) {
            throw new \Exception('Could not fetch page by id: ' . $letterboxdId);
        }

        return $pageContent;
    }

    private function getProviderIdByPageAndPattern(string $page, Provider $provider) : string
    {
        preg_match($provider::getPageIdPattern(), $page, $matches);

        if (count($matches) !== 2) {
            throw new \Exception('No id found in page.');
        }

        return $matches[1];
    }
}