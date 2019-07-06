<?php

namespace App\Command;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\ProductionCompany;
use App\Entity\WatchDate;
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
            $movie = $this->movieRepository->findOneBy(['letterboxd_id' => $diaryItem->getLetterboxdId()]);

            if ($movie === null) {
                try {
                    $providerIds = $this->letterboxdApi->getProviderIdsByLetterboxdId($diaryItem->getLetterboxdId());
                } catch (\Exception $e) {
                    echo $e . PHP_EOL;
                    die(500);
                }

                $tmdbMovie = $this->tmdbApi->getMovie(Id::createFromString($providerIds['tmdb']));

                $movie = new Movie(
                    Id::createFromString($providerIds['tmdb']),
                    ImdbId::createFromString($providerIds['imdb']),
                    $diaryItem->getLetterboxdId(),
                    $tmdbMovie->getTitle(),
                    $tmdbMovie->getReleaseDate(),
                    new ArrayCollection(),
                    new ArrayCollection(),
                    new ArrayCollection()
                );

                /** @var Tmdb\Resources\Genre $genre */
                foreach ($tmdbMovie->getGenres() as $tmdbGenre) {
                    $movie->addGenre($this->getGenre($tmdbGenre));
                }

                /** @var Tmdb\Resources\ProductionCompany $genre */
                foreach ($tmdbMovie->getProductionCompanies() as $tmdbProductionCompany) {
                    $movie->addProductionCompany($this->getProductionCompany($tmdbProductionCompany));
                }
            }

            $watchDates = $movie->getWatchDates();

            if ($watchDates->count() === 0) {
                $newWatchDate = new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating());
                $movie->addWatchDate($newWatchDate);

                $output->write(
                    sprintf(
                        'Added: %s - %s - %s' . PHP_EOL,
                        $movie->getTitle(),
                        $newWatchDate->getDate(),
                        ($newWatchDate->getDiaryRating() !== null) ? $newWatchDate->getDiaryRating()->getAsStars() : null
                    )
                );
            } else {
                $exists = false;
                foreach ($watchDates as $watchDate) {
                    if ((string)$watchDate->getDate() === (string)$diaryItem->getWatchDate()) {
                        $exists = true;
                        continue;
                    }
                }

                if ($exists === false) {
                    $newWatchDate = new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating());
                    $movie->addWatchDate($newWatchDate);

                    $output->write(
                        sprintf(
                            'Added: %s - %s - %s' . PHP_EOL,
                            $movie->getTitle(),
                            $newWatchDate->getDate(),
                            ($newWatchDate->getDiaryRating() !== null) ? $newWatchDate->getDiaryRating()->getAsStars() : null
                        )
                    );
                }
            }

            $this->em->persist($movie);
            $this->em->flush();
        }
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
