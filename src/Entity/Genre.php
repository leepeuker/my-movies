<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreRepository")
 */
class Genre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="genres")
     */
    private $movies;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct(string $name, ArrayCollection $movies)
    {
        $this->name   = $name;
        $this->movies = $movies;
    }

    public function addMovie(Movie $movie) : self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies() : Collection
    {
        return $this->movies;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function removeMovie(Movie $movie) : self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
        }

        return $this;
    }
}
