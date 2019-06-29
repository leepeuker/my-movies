<?php

namespace App\Command;

use App\Letterboxd\Api;
use App\Letterboxd\Resources\Diary\Reader;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetDiary extends Command
{
    protected static $defaultName = 'app:get-diary';

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
        $movies = $this->movieRepository->findAll();

        foreach ($movies as $movie) {
            $output->write($movie->getLetterboxdId() . ': ' . PHP_EOL);

            foreach ($movie->getWatchDates() as $watchDate) {
                $output->write('- '. $watchDate->getDate()->format('Y-m-d') . ' | ' . str_repeat('*', $watchDate->getDiaryRating()->asInt()) . PHP_EOL . PHP_EOL);
            }
        }
    }
}
