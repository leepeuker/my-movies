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
        $movies = $this->movieRepository->findBy([], ['title' => 'ASC']);

        foreach ($movies as $movie) {
            $genresString = '';
            foreach ($movie->getGenres() as $genre) {
                $genresString .= $genre->getName() . ', ';
            }
            $productionCompaniesString = '';
            foreach ($movie->getProductionCompanies() as $productionCompany) {
                $productionCompaniesString .= $productionCompany->getName() . ', ';
            }

            $output->write(
                $movie->getTitle() .
                ' (' .
                $movie->getReleaseDate()->getYear() .
                ') [' .
                rtrim($genresString, ', ') .
                '] {' .
                rtrim($productionCompaniesString, ', ') .
                '}: ' .
                PHP_EOL
            );

            foreach ($movie->getWatchDates() as $watchDate) {
                if ($watchDate->getDiaryRating() !== null) {
                    $ratingStars = $watchDate->getDiaryRating()->getAsStars();
                } else {
                    $ratingStars = ' - ';
                }

                $output->write(' - ' . $watchDate->getDate() . ' | ' . $ratingStars . PHP_EOL);
            }
        }
    }
}
