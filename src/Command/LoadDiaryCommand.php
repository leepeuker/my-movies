<?php

namespace App\Command;

use App\Entity\Genre;
use App\Entity\GenreList;
use App\Entity\Movie;
use App\Entity\ProductionCompany;
use App\Entity\ProductionCompanyList;
use App\Entity\WatchDate;
use App\Entity\WatchDateList;
use App\Letterboxd;
use App\Letterboxd\Resources\Diary\Item;
use App\Letterboxd\Resources\Diary\Reader;
use App\Provider\Tmdb;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ProductionCompanyRepository;
use App\ValueObject\Id;
use App\ValueObject\ImdbId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadDiaryCommand extends Command
{
    protected static $defaultName = 'app:load-diary';

    private          $diaryReader;

    private          $em;

    private          $genreRepository;

    private          $letterboxdApi;

    private          $movieRepository;

    private          $productionCompanyRepository;

    private          $tmdbApi;

    public function __construct(
        EntityManagerInterface $em,
        MovieRepository $movieRepository,
        GenreRepository $genreRepository,
        ProductionCompanyRepository $productionCompanyRepository,
        Reader $diaryReader,
        Letterboxd\Api $letterboxApi,
        Tmdb\Api $tmdbApi
    ) {
        parent::__construct();

        $this->letterboxdApi               = $letterboxApi;
        $this->diaryReader                 = $diaryReader;
        $this->movieRepository             = $movieRepository;
        $this->em                          = $em;
        $this->tmdbApi                     = $tmdbApi;
        $this->genreRepository             = $genreRepository;
        $this->productionCompanyRepository = $productionCompanyRepository;
    }

    protected function configure()
    {
        $this->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diaryItemList = $this->diaryReader->loadFromCsv(__DIR__ . '/../../tmp/resources/diary.csv');

        /** @var Item $diaryItem */
        foreach ($diaryItemList as $diaryItem) {
            try {
                $movie = $this->createMovieFromDiaryItem($diaryItem);
            } catch (\Exception $e) {
                echo $e . PHP_EOL;
                die(500);
            }

            $watchDate = new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating());

            $messageFormat = 'Created: %s - %s - %s' . PHP_EOL;
            if ($movie->getWatchDates()->has($watchDate) === true) {
                $messageFormat = 'Updated: %s - %s - %s' . PHP_EOL;

                $movie->removeWatchDate($watchDate);
                $this->em->flush();
            }

            $movie->addWatchDate($watchDate);
            $this->em->flush();

            $output->write(
                sprintf(
                    $messageFormat,
                    $movie->getTitle(),
                    $watchDate->getDate(),
                    ($watchDate->getDiaryRating() !== null) ? $watchDate->getDiaryRating()->getAsStars() : null
                )
            );
        }
    }

    private function createGenreList(Tmdb\Resources\Movie $tmdbMovie) : GenreList
    {
        /** @var Tmdb\Resources\Genre $genre */
        $genreList = GenreList::create();
        foreach ($tmdbMovie->getGenres() as $tmdbGenre) {
            $genreList->add($this->getGenre($tmdbGenre));
        }

        return $genreList;
    }

    private function createMovie(array $providerIds, Item $diaryItem, Tmdb\Resources\Movie $tmdbMovie) : Movie
    {
        $movie = new Movie(
            Id::createFromString($providerIds['tmdb']),
            ImdbId::createFromString($providerIds['imdb']),
            $diaryItem->getLetterboxdId(),
            $tmdbMovie->getTitle(),
            $tmdbMovie->getReleaseDate(),
            WatchDateList::create(),
            $this->createGenreList($tmdbMovie),
            $this->createProductionCompanyList($tmdbMovie)
        );

        return $movie;
    }

    private function createMovieFromDiaryItem(Item $diaryItem) : Movie
    {
        $movie = $this->movieRepository->findOneBy(['letterboxd_id' => $diaryItem->getLetterboxdId()]);

        if ($movie === null) {
            $providerIds = $this->letterboxdApi->getProviderIdsByLetterboxdId($diaryItem->getLetterboxdId());
            $tmdbMovie   = $this->tmdbApi->getMovie(Id::createFromString($providerIds['tmdb']));
            $movie       = $this->createMovie($providerIds, $diaryItem, $tmdbMovie);
        }

        $this->em->persist($movie);

        return $movie;
    }

    private function createProductionCompanyList(Tmdb\Resources\Movie $tmdbMovie) : ProductionCompanyList
    {
        /** @var Tmdb\Resources\ProductionCompany $genre */
        $productionCompanyList = ProductionCompanyList::create();
        foreach ($tmdbMovie->getProductionCompanies() as $tmdbProductionCompany) {
            $productionCompanyList->add($this->getProductionCompany($tmdbProductionCompany));
        }

        return $productionCompanyList;
    }

    private function getGenre(Tmdb\Resources\Genre $gtmdbGenre) : Genre
    {
        $genre = $this->genreRepository->findOneBy(['name' => $gtmdbGenre->getName()]);

        if ($genre === null) {
            $genre = new Genre(
                $gtmdbGenre->getName(),
                new ArrayCollection()
            );
        }

        return $genre;
    }

    private function getProductionCompany(Tmdb\Resources\ProductionCompany $tmdbProductionCompany) : ProductionCompany
    {
        $productionCompany = $this->productionCompanyRepository->findOneBy(['name' => $tmdbProductionCompany->getName()]);

        if ($productionCompany === null) {
            $productionCompany = new ProductionCompany(
                $tmdbProductionCompany->getName(),
                new ArrayCollection(),
                $tmdbProductionCompany->getOriginCountry()
            );
        }

        return $productionCompany;
    }
}
