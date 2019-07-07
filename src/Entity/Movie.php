<?php

namespace App\Entity;

use App\ValueObject\Date;
use App\ValueObject\Id;
use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use App\ValueObject\Title;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Genre", inversedBy="movies", cascade={"persist", "remove"})
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
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductionCompany", inversedBy="Movie", cascade={"persist", "remove"})
     */
    private $productionCompanies;

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
     * @ORM\OneToMany(targetEntity="App\Entity\WatchDate", mappedBy="movie", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $watchDates;

    public function __construct(
        Id $tmdbId,
        ?ImdbId $imdbId,
        ?LetterboxdId $letterboxdId,
        Title $title,
        Date $releaseDate,
        WatchDateList $watchDateList,
        GenreList $genreList,
        ProductionCompanyList $productionCompanyList
    ) {
        $this->tmdbId              = $tmdbId->getId();
        $this->imdbId              = ($imdbId !== null) ? $imdbId->getId() : null;
        $this->letterboxd_id       = ($letterboxdId !== null) ? $letterboxdId->getId() : null;
        $this->title               = (string)$title;
        $this->releaseDate         = $releaseDate->asDateTime();
        $this->watchDates          = $watchDateList->asArrayCollection();
        $this->genres              = $genreList->asArrayCollection();
        $this->productionCompanies = $productionCompanyList->asArrayCollection();
    }

    public function addGenre(Genre $genre) : self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function addGenreList(GenreList $genreList) : self
    {
        foreach ($genreList as $genre) {
            $this->addGenre($genre);
        }

        return $this;
    }

    public function addProductionCompanies(ProductionCompany $productionCompany) : self
    {
        if (!$this->productionCompanies->contains($productionCompany)) {
            $this->productionCompanies[] = $productionCompany;
            $productionCompany->addMovie($this);
        }

        return $this;
    }

    public function addProductionCompany(ProductionCompany $productionCompany) : self
    {
        if (!$this->productionCompanies->contains($productionCompany)) {
            $this->productionCompanies[] = $productionCompany;
            $productionCompany->addMovie($this);
        }

        return $this;
    }

    public function addWatchDate(WatchDate $watchDate) : self
    {
        if (!$this->watchDates->contains($watchDate)) {
            $this->watchDates[] = $watchDate;
        }

        return $this;
    }

    public function getGenres() : GenreList
    {
        $genreList = GenreList::create();
        /** @var Genre $genre */
        foreach ($this->genres as $genre) {
            $genreList->add($genre);
        }

        return $genreList;
    }

    public function getId() : ?Id
    {
        return ($this->id !== null) ? Id::createFromInt($this->id) : null;
    }

    public function getImdbId() : ?ImdbId
    {
        return ($this->imdbId !== null) ? ImdbId::createFromString($this->imdbId) : null;
    }

    public function getLetterboxdId() : ?LetterboxdId
    {
        return ($this->letterboxd_id !== null) ? LetterboxdId::createFromString($this->letterboxd_id) : null;
    }

    public function getProductionCompanies() : ProductionCompanyList
    {
        $productionCompanyList = ProductionCompanyList::create();
        /** @var ProductionCompany $productionCompany */
        foreach ($this->productionCompanies as $productionCompany) {
            $productionCompanyList->add($productionCompany);
        }

        return $productionCompanyList;
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

    public function getWatchDates() : WatchDateList
    {
        $watchDateList = WatchDateList::create();
        /** @var WatchDate $watchDate */
        foreach ($this->watchDates as $watchDate) {
            $watchDateList->add($watchDate);
        }

        return $watchDateList;
    }

    public function removeWatchDate(WatchDate $oldWatchDate) : self
    {
        /** @var WatchDate $watchDate */
        foreach ($this->watchDates as $key => $watchDate) {
            if ((string)$watchDate->getDate() === (string)$oldWatchDate->getDate()) {
                unset($this->watchDates[$key]);
            }
        }

        return $this;
    }
}
