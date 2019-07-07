<?php

namespace App\Entity;

use App\ValueObject\Id;
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

    public function __construct(string $name, MovieList $movies, string $country)
    {
        $this->name    = $name;
        $this->movies  = $movies->asArrayCollection();
        $this->country = $country;
    }

    public function getCountry() : ?string
    {
        return $this->country;
    }

    public function getId() : ?Id
    {
        return ($this->id !== null) ? Id::createFromInt($this->id) : null;
    }

    public function getMovies() : MovieList
    {
        $movieList = MovieList::create();
        /** @var Movie $movie */
        foreach ($this->movies as $movie) {
            $movieList->add($movie);
        }

        return $movieList;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
