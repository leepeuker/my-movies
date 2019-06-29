<?php

namespace App\Entity;

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
    private $imdb_id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $letterboxd_id;

    /**
     * @ORM\Column(type="integer", unique=true, nullable=true)
     */
    private $tmdb_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WatchDate", mappedBy="movie", orphanRemoval=true, cascade={"persist"})
     */
    private $watchDate;

    public function __construct()
    {
        $this->watchDate = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getImdbId() : ?string
    {
        return $this->imdb_id;
    }

    public function getLetterboxdId() : ?string
    {
        return $this->letterboxd_id;
    }

    public function getTmdbId() : ?int
    {
        return $this->tmdb_id;
    }

    public function setImdbId(?string $imdb_id) : self
    {
        $this->imdb_id = $imdb_id;

        return $this;
    }

    public function setLetterboxdId(?string $letterboxd_id) : self
    {
        $this->letterboxd_id = $letterboxd_id;

        return $this;
    }

    public function setTmdbId(?int $tmdb_id) : self
    {
        $this->tmdb_id = $tmdb_id;

        return $this;
    }

    /**
     * @return Collection|WatchDate[]
     */
    public function getWatchDates(): Collection
    {
        return $this->watchDate;
    }

    public function addWatchDate(WatchDate $watchDate): self
    {
        if (!$this->watchDate->contains($watchDate)) {
            $this->watchDate[] = $watchDate;
            $watchDate->setMovie($this);
        }

        return $this;
    }

    public function removeWatchDate(WatchDate $watchDate): self
    {
        if ($this->watchDate->contains($watchDate)) {
            $this->watchDate->removeElement($watchDate);
            // set the owning side to null (unless already changed)
            if ($watchDate->getMovie() === $this) {
                $watchDate->setMovie(null);
            }
        }

        return $this;
    }
}
