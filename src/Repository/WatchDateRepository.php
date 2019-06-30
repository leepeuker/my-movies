<?php

namespace App\Repository;

use App\Entity\WatchDate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WatchDate|null find($id, $lockMode = null, $lockVersion = null)
 * @method WatchDate|null findOneBy(array $criteria, array $orderBy = null)
 * @method WatchDate[]    findAll()
 * @method WatchDate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WatchDateRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WatchDate::class);
    }
}
