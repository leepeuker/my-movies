<?php

namespace App\Entity;

use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use App\ValueObject\TmdbId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
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
     * @ORM\Column(type="integer", unique=true, nullable=true)
     */
    private $tmdbId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WatchDate", mappedBy="movie", orphanRemoval=true, cascade={"persist"})
     */
    private $watchDate;

    public function __construct(?ImdbId $imdbId, ?LetterboxdId $letterboxdId, ?TmdbId $tmdbId)
    {
        $this->watchDate     = new ArrayCollection();
        $this->imdbId        = ($imdbId !== null) ? $imdbId->getId() : null;
        $this->letterboxd_id = ($letterboxdId !== null) ? $letterboxdId->getId() : null;
        $this->tmdbId        = ($tmdbId !== null) ? $tmdbId->getId() : null;
    }

    public function addWatchDate(WatchDate $watchDate) : self
    {
        if (!$this->watchDate->contains($watchDate)) {
            $this->watchDate[] = $watchDate;
        }

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getImdbId() : ?ImdbId
    {
        return ($this->imdbId !== null) ? ImdbId::createByString($this->imdbId) : null;
    }

    public function getLetterboxdId() : ?LetterboxdId
    {
        return ($this->letterboxd_id !== null) ? LetterboxdId::createByString($this->letterboxd_id) : null;
    }

    public function getTmdbId() : ?TmdbId
    {
        return ($this->tmdbId !== null) ? TmdbId::createByString((string)$this->tmdbId) : null;
    }

    /**
     * @return Collection|WatchDate[]
     */
    public function getWatchDates() : Collection
    {
        return $this->watchDate;
    }

    public function removeWatchDate(WatchDate $watchDate) : self
    {
        if ($this->watchDate->contains($watchDate)) {
            $this->watchDate->removeElement($watchDate);
        }

        return $this;
    }
}
