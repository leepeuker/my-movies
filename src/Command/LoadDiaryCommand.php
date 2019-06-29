<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\WatchDate;
use App\Letterboxd\Api;
use App\Letterboxd\Resources\Diary\Item;
use App\Letterboxd\Resources\Diary\Reader;
use App\Repository\MovieRepository;
use App\ValueObject\ImdbId;
use App\ValueObject\LetterboxdId;
use App\ValueObject\TmdbId;
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

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository, Reader $diaryReader, Api $letterboxApi)
    {
        parent::__construct();

        $this->letterboxdApi   = $letterboxApi;
        $this->diaryReader     = $diaryReader;
        $this->movieRepository = $movieRepository;
        $this->em              = $em;
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

                $movie = new Movie(
                    ImdbId::createByString($providerIds['imdb']),
                    LetterboxdId::createByString($diaryItem->getLetterboxdId()),
                    TmdbId::createByString($providerIds['tmdb'])
                );

//                $output->write('Add new movie: ' . $diaryItem->getTitle() . PHP_EOL);
            }

            $watchDates = $movie->getWatchDates();

            if (sizeof($watchDates) === 0) {
                $movie->addWatchDate(
                    new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating())
                );

                $output->write(
                    sprintf(
                        'Added: %s - %s - %s' . PHP_EOL,
                        $diaryItem->getTitle(),
                        $diaryItem->getWatchDate()->format('Y-m-d'),
                        str_repeat('*', $diaryItem->getRating()->asInt())
                    )
                );
            } else {
                foreach ($watchDates as $watchDate) {
                    if ($watchDate->getDate()->format('Y-m-d') === $diaryItem->getWatchDate()->format('Y-m-d')) {
                        continue;
                    }

                    $movie->addWatchDate(
                        new WatchDate($movie, $diaryItem->getWatchDate(), $diaryItem->getRating())
                    );

                    $output->write(
                        sprintf(
                            'Added: %s - %s - %s' . PHP_EOL,
                            $diaryItem->getTitle(),
                            $diaryItem->getWatchDate()->format('Y-m-d'),
                            str_repeat('*', $diaryItem->getRating()->asInt())
                        )
                    );
                }
            }

            $this->em->persist($movie);
            $this->em->flush();
        }
    }
}
