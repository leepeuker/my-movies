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

    // /**
    //  * @return WachtDates[] Returns an array of WachtDates objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WatchDates
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
