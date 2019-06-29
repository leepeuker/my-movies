<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\WatchDate;
use App\Letterboxd;
use App\Letterboxd\Resources\Diary\Item;
use App\Letterboxd\Resources\Diary\Reader;
use App\Provider\Tmdb;
use App\Repository\MovieRepository;
use App\ValueObject\DateTime;
use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use App\ValueObject\Title;
use App\ValueObject\TmdbId;
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

    private          $letterboxdApi;

    private          $movieRepository;

    private          $tmdbApi;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, Reader $diaryReader, Letterboxd\Api $letterboxApi, Tmdb\Api $tmdbApi)
    {
        parent::__construct();

        $this->letterboxdApi   = $letterboxApi;
        $this->diaryReader     = $diaryReader;
        $this->movieRepository = $movieRepository;
        $this->em              = $em;
        $this->tmdbApi         = $tmdbApi;
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

                $tmdbMovie = $this->tmdbApi->getMovie(TmdbId::createByString($providerIds['tmdb']));

                $movie = new Movie(
                    TmdbId::createByString($providerIds['tmdb']),
                    ImdbId::createByString($providerIds['imdb']),
                    LetterboxdId::createByString($diaryItem->getLetterboxdId()),
                    Title::createFromString($tmdbMovie['title']),
                    DateTime::createFromString($tmdbMovie['release_date']),
                    new ArrayCollection()
                );
            }

            $watchDates = $movie->getWatchDates();

            if ($watchDates->count() === 0) {
                $newWatchDate = new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating());
                $movie->addWatchDate($newWatchDate);

                $output->write(
                    sprintf(
                        'Added: %s - %s - %s' . PHP_EOL,
                        $movie->getTitle(),
                        $newWatchDate->getDate()->format('Y-m-d'),
                        ($newWatchDate->getDiaryRating() !== null) ? str_repeat('*', $newWatchDate->getDiaryRating()->asInt()) : null
                    )
                );
            } else {
                $exists = false;
                foreach ($watchDates as $watchDate) {
                    if ($watchDate->getDate()->format('Y-m-d') === $diaryItem->getWatchDate()->format('Y-m-d')) {
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
                            $newWatchDate->getDate()->format('Y-m-d'),
                            ($newWatchDate->getDiaryRating() !== null) ? str_repeat('*', $newWatchDate->getDiaryRating()->asInt()) : null
                        )
                    );
                }
            }

            $this->em->persist($movie);
            $this->em->flush();
        }
    }
}
