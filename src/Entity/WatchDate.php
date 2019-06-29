<?php

namespace App\Entity;

use App\ValueObject\DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WachtDatesRepository")
 */
class WatchDate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="watchDate", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    /**
     * @ORM\Column(type="date")
     */
    private $watchDate;

    public function __construct(Movie $movie, \DateTimeInterface $watchDate)
    {
        $this->movie     = $movie;
        $this->watchDate = $watchDate;
    }

    public function date() : ?\DateTimeInterface
    {
        return $this->watchDate;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getMovie() : ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie) : self
    {
        $this->movie = $movie;

        return $this;
    }

    public function setWatchDate(\DateTimeInterface $watchDate) : self
    {
        $this->watchDate = $watchDate;

        return $this;
    }
}
