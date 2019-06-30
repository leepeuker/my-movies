<?php

namespace App\Entity;

use App\ValueObject\Id;
use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use App\ValueObject\Date;
use App\ValueObject\Title;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="movies", cascade={"persist"})
     */
    private $genres;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true,length=16, nullable=true)
     */
    private $imdbId;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $letterboxd_id;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=true)
     */
    private $tmdbId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WatchDate", mappedBy="movie", orphanRemoval=true, cascade={"persist"})
     */
    private $watchDate;

    public function __construct(
        Id $tmdbId,
        ?ImdbId $imdbId,
        ?LetterboxdId $letterboxdId,
        Title $title,
        Date $releaseDate,
        ArrayCollection $watchDates,
        ArrayCollection $genres
    ) {
        $this->tmdbId        = $tmdbId->getId();
        $this->imdbId        = ($imdbId !== null) ? $imdbId->getId() : null;
        $this->letterboxd_id = ($letterboxdId !== null) ? $letterboxdId->getId() : null;
        $this->title         = (string)$title;
        $this->releaseDate   = $releaseDate->asDateTime();
        $this->watchDate     = $watchDates;
        $this->genres        = $genres;
    }

    public function addGenre(Genre $genre) : self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function addWatchDate(WatchDate $watchDate) : self
    {
        if (!$this->watchDate->contains($watchDate)) {
            $this->watchDate[] = $watchDate;
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres() : Collection
    {
        return $this->genres;
    }

    public function getId() : Id
    {
        return Id::createFromInt($this->id);
    }

    public function getImdbId() : ?ImdbId
    {
        return ($this->imdbId !== null) ? ImdbId::createFromString($this->imdbId) : null;
    }

    public function getLetterboxdId() : ?LetterboxdId
    {
        return ($this->letterboxd_id !== null) ? LetterboxdId::createFromString($this->letterboxd_id) : null;
    }

    public function getReleaseDate() : Date
    {
        return Date::createFromString($this->releaseDate->format('Y-m-d'));
    }

    public function getTitle() : Title
    {
        return Title::createFromString($this->title);
    }

    public function getTmdbId() : ?Id
    {
        return ($this->tmdbId !== null) ? Id::createFromString((string)$this->tmdbId) : null;
    }

    /**
     * @return Collection|WatchDate[]
     */
    public function getWatchDates() : Collection
    {
        return $this->watchDate;
    }

    public function removeGenre(Genre $genre) : self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
            $genre->removeMovie($this);
        }

        return $this;
    }

    public function removeWatchDate(WatchDate $watchDate) : self
    {
        if ($this->watchDate->contains($watchDate)) {
            $this->watchDate->removeElement($watchDate);
        }

        return $this;
    }
}
