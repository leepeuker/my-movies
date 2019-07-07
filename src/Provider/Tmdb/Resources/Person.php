<?php declare(strict_types=1);

namespace App\Provider\Tmdb\Resources;

use App\ValueObject\Date;
use App\ValueObject\Id;
use App\ValueObject\ImdbId;
use App\ValueObject\Name;

class Person
{
    private $birthday;

    private $deathday;

    private $gender;

    private $imdbId;

    private $knownFor;

    private $name;

    private $placeOfBirth;

    private $tmdbId;

    private function __construct(Id $tmdbId, ?ImdbId $imdbId, Name $name, int $gender, ?string $knownFor, ?Date $birthday, ?Date $deathday, ?string $placeOfBirth)
    {
        $this->tmdbId       = $tmdbId;
        $this->imdbId       = $imdbId;
        $this->name         = $name;
        $this->birthday     = $birthday;
        $this->deathday     = $deathday;
        $this->gender       = $gender;
        $this->placeOfBirth = $placeOfBirth;
        $this->knownFor     = $knownFor;
    }

    public static function createFromArray($data) : self
    {
        return new self(
            Id::createFromInt($data['id']),
            empty($data['imdb_id']) ? null : ImdbId::createFromString($data['imdb_id']),
            Name::createFromString($data['name']),
            $data['gender'],
            empty($data['known_for_department']) ? null : (string)$data['known_for_department'],
            empty($data['birthday']) ? null : Date::createFromString($data['birthday']),
            empty($data['deathday']) ? null : Date::createFromString($data['deathday']),
            $data['place_of_birth']
        );
    }

    public function getBirthday() : ?Date
    {
        return $this->birthday;
    }

    public function getDeathday() : ?Date
    {
        return $this->deathday;
    }

    public function getGender() : int
    {
        return $this->gender;
    }

    public function getImdbId() : ?ImdbId
    {
        return $this->imdbId;
    }

    public function getKnownFor() : string
    {
        return $this->knownFor;
    }

    public function getName() : Name
    {
        return $this->name;
    }

    public function getPlaceOfBirth() : ?string
    {
        return $this->placeOfBirth;
    }

    public function getTmdbId() : Id
    {
        return $this->tmdbId;
    }
}