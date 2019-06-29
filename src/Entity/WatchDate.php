<?php

namespace App\Entity;

use App\ValueObject\Rating\DiaryRating;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WachtDatesRepository")
 */
class WatchDate
{
    /**
     * @ORM\Column(type="smallint", nullable=true, options={"default": null})
     */
    private $diaryRating;

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

    public function __construct(Movie $movie, \DateTimeInterface $watchDate, ?DiaryRating $diaryRating)
    {
        $this->movie       = $movie;
        $this->watchDate   = $watchDate;
        $this->diaryRating = ($diaryRating !== null) ? $diaryRating->asInt() : null;
    }

    public function date() : ?\DateTimeInterface
    {
        return $this->watchDate;
    }

    public function getDiaryRating() : ?DiaryRating
    {
        return ($this->diaryRating !== null) ? DiaryRating::createByInt($this->diaryRating) : null;
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

    public function setRating(?int $diaryRating) : self
    {
        $this->diaryRating = ($diaryRating !== null) ? DiaryRating::createByInt($diaryRating) : null;

        return $this;
    }

    public function setWatchDate(\DateTimeInterface $watchDate) : self
    {
        $this->watchDate = $watchDate;

        return $this;
    }
}
