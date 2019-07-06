<?php

namespace App\Entity;

use App\ValueObject\Id;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionCompanyRepository")
 */
class ProductionCompany
{
    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="productionCompanies")
     */
    private $movies;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function __construct(string $name, ArrayCollection $movies, string $country)
    {
        $this->name    = $name;
        $this->movies  = $movies;
        $this->country = $country;
    }

    public function addMovie(Movie $movie) : self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }

    public function getId() : Id
    {
        return Id::createFromInt($this->id);
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
